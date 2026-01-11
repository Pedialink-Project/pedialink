<?php

namespace App\Services;

use App\Models\User;

class EmailVerificationService
{
    /**
     * Base 64 encode
     * @param string $data
     * @return string
     */
    private function base64url_encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base 64 decode
     * @param string $data
     * @return bool|string
     */
    private function base64url_decode(string $data): string
    {
        $pad = 4 - (strlen($data) % 4);
        if ($pad < 4)
            $data .= str_repeat('=', $pad);
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Generate signed verification token
     * @param User $user
     * @param string $secret
     * @param int $ttlSeconds
     * @return string
     */
    private function createSignedVerificationToken(User $user, string $secret, int $ttlSeconds = 3600): string
    {
        // payload: include user id and a revocation factor (email hash)
        $payload = [
            'uid' => (int) $user->id,
            'exp' => time() + $ttlSeconds,
            'eh' => hash('sha256', $user->email),
            'nonce' => bin2hex(random_bytes(6)) // optional uniqueness
        ];
        $payloadJson = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $payloadB = $this->base64url_encode($payloadJson);

        // signature: HMAC-SHA256 over payload
        $sig = hash_hmac('sha256', $payloadB, $secret, true);
        $sigB = $this->base64url_encode($sig);

        // token as payload.signature
        return $payloadB . '.' . $sigB;
    }

    /**
     * Generate verification link
     * @param User $user
     * @param string $secret
     * @return string
     */
    public function createVerificationUrl(User $user, string $secret): string
    {
        $token = $this->createSignedVerificationToken($user, $secret, 3600);
        // Example: https://example.com/verify-email?token=...
        return rtrim(
            config('app.url'), '/') . '/verify-email?token=' . urlencode($token
        );
    }

    /**
     * Verify signed url verification link
     * @param string $token
     * @param string $secret
     * @return bool
     */
    public function verifySignedTokenAndMarkVerified(string $token, string $secret): bool
    {
        // split
        $parts = explode('.', $token, 2);
        if (count($parts) !== 2)
            return false;

        [$payloadB, $sigB] = $parts;

        // recompute expected signature
        $expectedSigB = $this->base64url_encode(
            hash_hmac('sha256', $payloadB, $secret, true)
        );

        if (!hash_equals($expectedSigB, $sigB)) {
            return false;
        }

        $payloadJson = $this->base64url_decode($payloadB);
        $payload = json_decode($payloadJson, true);
        if (!is_array($payload) || empty($payload['uid']) || empty($payload['exp'])) {
            return false;
        }

        if (time() > (int) $payload['exp']) {
            return false;
        }

        /**
         * @var User
         */
        $user = User::find($payload['uid']);
        if (!$user)
            return false;

        // optional: check email hash for revocation
        if (isset($payload['eh'])) {
            $expectedEh = hash('sha256', $user->email);
            if (!hash_equals($expectedEh, $payload['eh'])) {
                return false;
            }
        }

        // If already verified, treat as used
        if ($user->email_verified) {
            return false;
        }

        // Mark verified (atomic)
        $user->email_verified = true;
        $updated = $user->save();

        if ($updated) {
            return true;
        } else {
            return false;
        }
    }
}
<?php

namespace App\Helpers;

use App\Models\User;

class SignedToken
{
    /**
     * Base 64 encode
     * @param string $data
     * @return string
     */
    public static function base64url_encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base 64 decode
     * @param string $data
     * @return bool|string
     */
    public static function base64url_decode(string $data): string
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
    public static function createSignedVerificationToken(
        User $user,
        string $secret,
        int $ttlSeconds = 3600
    ): string
    {
        // payload: include user id and a revocation factor (email hash)
        $payload = [
            'uid' => (int) $user->id,
            'exp' => time() + $ttlSeconds,
            'eh' => hash('sha256', $user->email),
            'nonce' => bin2hex(random_bytes(6)) // optional uniqueness
        ];
        $payloadJson = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $payloadB = self::base64url_encode($payloadJson);

        // signature: HMAC-SHA256 over payload
        $sig = hash_hmac('sha256', $payloadB, $secret, true);
        $sigB = self::base64url_encode($sig);

        // token as payload.signature
        return $payloadB . '.' . $sigB;
    }

    public static function verifySignedToken(string $token, string $secret): array
    {
        $result = [
            null, // user
            false, // verified
        ];

        // split
        $parts = explode('.', $token, 2);
        if (count($parts) !== 2)
            return $result;

        [$payloadB, $sigB] = $parts;

        // recompute expected signature
        $expectedSigB = self::base64url_encode(
            hash_hmac('sha256', $payloadB, $secret, true)
        );

        if (!hash_equals($expectedSigB, $sigB)) {
            return $result;
        }

        $payloadJson = self::base64url_decode($payloadB);
        $payload = json_decode($payloadJson, true);
        if (!is_array($payload) || empty($payload['uid']) || empty($payload['exp'])) {
            return $result;
        }

        if (time() > (int) $payload['exp']) {
            return $result;
        }

        /**
         * @var User
         */
        $user = User::find($payload['uid']);
        $result[0] = $user;
        if (!$user)
            return $result;

        // optional: check email hash for revocation
        if (isset($payload['eh'])) {
            $expectedEh = hash('sha256', $user->email);
            if (!hash_equals($expectedEh, $payload['eh'])) {
                return $result;
            }
        }

        $result[1] = true;
        return $result;
    }
}
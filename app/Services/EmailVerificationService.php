<?php

namespace App\Services;

use App\Helpers\SignedToken;
use App\Models\User;

class EmailVerificationService
{

    /**
     * Generate verification link
     * @param User $user
     * @param string $secret
     * @return string
     */
    public function createVerificationUrl(User $user, string $secret): string
    {
        $token = SignedToken::createSignedVerificationToken($user, $secret, 3600);
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
        [$user, $verified] = SignedToken::verifySignedToken($token, $secret);

        // If already verified, treat as used
        if ($user->email_verified) {
            return false;
        }

        if ($verified) {
            // Mark verified (atomic)
            $user->email_verified = true;
            $updated = $user->save();
    
            if ($updated) {
                return true;
            }
        }

        return false;
    }
}
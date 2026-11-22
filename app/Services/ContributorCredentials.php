<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class ContributorCredentials
{
    public function createPassword(User $user)
    {
        // 24 random characters; only the hash is used for authentication.
        $password = rtrim(strtr(base64_encode(random_bytes(18)), '+/', '-_'), '=');
        $user->password = bcrypt($password);
        // Encrypted solely for manual handoff by an authorized admin.
        $user->temporary_password_ciphertext = Crypt::encryptString($password);
        $user->must_change_password = true;
        $user->setRememberToken(Str::random(60));
        $user->save();

        return $password;
    }

    public function availablePassword(User $user)
    {
        return $user->temporary_password_ciphertext
            ? Crypt::decryptString($user->temporary_password_ciphertext)
            : null;
    }

    public function generatedEmail($first, $last)
    {
        $base = Str::slug(trim($first.' '.$last)) ?: 'contributor';
        do {
            $email = $base.'-'.Str::lower(Str::random(8)).'@nollyflix.local';
        } while (User::where('email', $email)->exists());
        return $email;
    }

    public function generatedUsername($first, $last)
    {
        $base = Str::slug(trim($first.' '.$last)) ?: 'contributor';
        $username = $base;
        while (User::where('username', $username)->exists()) {
            $username = $base.'-'.Str::lower(Str::random(5));
        }
        return $username;
    }
}

<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class ContributorCredentials
{
    public function createPassword(User $user)
    {
        // Eight easy-to-transcribe characters: four lowercase letters,
        // one digit and three symbols. Uses cryptographically secure randomness.
        // Example of the format only: m3@.hdn! (never a fixed password).
        $characters = [];
        foreach ([
            ['abcdefghjkmnpqrstuvwxyz', 4],
            ['23456789', 1],
            ['@.!#?$', 3],
        ] as $group) {
            for ($i = 0; $i < $group[1]; $i++) {
                $characters[] = $group[0][random_int(0, strlen($group[0]) - 1)];
            }
        }
        // Secure Fisher-Yates shuffle so the position of each type varies.
        for ($i = count($characters) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$characters[$i], $characters[$j]] = [$characters[$j], $characters[$i]];
        }
        $password = implode('', $characters);
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

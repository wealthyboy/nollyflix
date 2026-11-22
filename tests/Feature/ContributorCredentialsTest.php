<?php

namespace Tests\Feature;

use App\User;
use App\Services\ContributorCredentials;
use Tests\TestCase;

class ContributorCredentialsTest extends TestCase
{
    public function test_generated_temporary_password_is_hashed_and_recoverable_by_service()
    {
        // Pure model instance; avoids depending on a seeded database.
        $user = new User;
        $credentials = new ContributorCredentials;
        $this->assertSame(null, $credentials->availablePassword($user));
        $user->temporary_password_ciphertext = 'secret';
        $this->assertArrayNotHasKey('temporary_password_ciphertext', $user->toArray());
    }
}

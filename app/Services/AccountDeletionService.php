<?php

namespace App\Services;

use App\AccountDeletionRequest;
use App\Mail\ConfirmAccountDeletion;
use App\Mail\AccountDeletionReceived;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AccountDeletionService
{
    const MESSAGE = 'If that email belongs to a NollyFlix account, we will send a confirmation link. Check your inbox and spam folder. If it does not arrive, contact support@nollyflix.tv.';

    public function sendConfirmation($email)
    {
        $email = trim($email);
        // Apply the same cooldown to known/unknown emails and both entry points.
        $key = 'account-deletion-mail:'.hash('sha256', strtolower($email));
        if (!Cache::add($key, true, now()->addMinute())) {
            return;
        }

        $user = User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();
        if (!$user) {
            return;
        }

        $url = URL::temporarySignedRoute('account-deletion.confirm', now()->addHour(), [
            'user' => $user->id,
            'fingerprint' => $this->fingerprint($user),
        ]);

        try {
            // Do not depend on a background worker for identity verification.
            Mail::to($user->email)->send(new ConfirmAccountDeletion($url));
        } catch (\Throwable $exception) {
            Cache::forget($key);
            // Do not log an email address, signed link, password or transport secrets.
            Log::warning('account_deletion_confirmation_failed', [
                'reference' => (string) Str::uuid(),
                'exception_type' => get_class($exception),
            ]);
        }
    }

    public function fingerprint(User $user)
    {
        // Updating the email or password invalidates previously issued links.
        return hash_hmac('sha256', $user->email."\0".$user->password, config('app.key'));
    }

    public function confirm(User $user)
    {
        $deletion = DB::transaction(function () use ($user) {
            // Serialize concurrent confirmations for this account.
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();

            return AccountDeletionRequest::firstOrCreate(['user_id' => $user->id], [
                'reference' => (string) Str::uuid(),
                'confirmed_at' => now(),
            ]);
        });

        if ($deletion->wasRecentlyCreated) {
            try {
                Mail::to('support@nollyflix.tv')->send(new AccountDeletionReceived($deletion->reference));
            } catch (\Throwable $exception) {
                // The durable admin worklist remains the source of truth if email is down.
                Log::warning('account_deletion_support_notice_failed', [
                    'reference' => $deletion->reference,
                    'exception_type' => get_class($exception),
                ]);
            }
        }

        return $deletion;
    }
}

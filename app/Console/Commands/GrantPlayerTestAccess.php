<?php

namespace App\Console\Commands;

use App\Services\MovieAccessGrantService;
use App\User;
use App\Video;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class GrantPlayerTestAccess extends Command
{
    protected $signature = 'movie-access:grant-player-test
        {--admin= : Existing administrator email for the grant audit record}
        {--yes : Confirm this specific complimentary Buy grant without prompting}';

    protected $description = 'Grant jacob.atam@gmail.com complimentary Buy access to movie 43 for app player testing';

    public function handle(MovieAccessGrantService $service)
    {
        $customer = User::customers()->where('email', 'jacob.atam@gmail.com')->first();
        $video = Video::active()->whereNull('deleted_at')->find(43);
        if (! $customer || ! $video) {
            $this->error('The existing customer jacob.atam@gmail.com and active movie 43 must both exist. Nothing was changed.');
            return 1;
        }

        if (! $video->link && ! $video->episodes()->whereNotNull('link')->where('link', '<>', '')->exists()) {
            $this->error('Movie 43 has no playable video or episode. Nothing was changed.');
            return 1;
        }

        $email = trim((string) $this->option('admin'));
        if ($email === '' && $this->input->isInteractive()) {
            $email = trim((string) $this->ask('Your existing administrator email (for the access audit)'));
        }
        $administrator = $email !== '' ? User::where('email', $email)->first() : null;
        $codes = $administrator
            ? preg_split('/[\s,]+/', (string) data_get($administrator, 'users_permission.permission.code'), -1, PREG_SPLIT_NO_EMPTY)
            : [];
        if (! $administrator || ! in_array('3', $codes, true)) {
            $this->error('Provide --admin with an existing administrator email that has movie-action permission. Nothing was changed.');
            return 1;
        }

        $this->line('Customer: '.$customer->email);
        $this->line('Movie: 43 — '.$video->title);
        $this->line('Access: Buy (no expiry). Complimentary order: 0. No payment, receipt, email or push will be sent.');
        if (! $this->option('yes') && (! $this->input->isInteractive()
            || ! $this->confirm('Grant this movie access?', false))) {
            $this->warn('Cancelled. Nothing was changed.');
            return 1;
        }

        $grant = $service->grant($administrator, $customer, [
            // The same UUID makes reruns reuse the grant as well as its order.
            'grant_token' => Uuid::uuid5(Uuid::NAMESPACE_URL, 'nollyflix/player-test/'.$customer->id.'/43/buy')->toString(),
            'video_id' => 43,
            'purchase_type' => 'buy',
            'reason' => 'Complimentary app video-player test requested for jacob.atam@gmail.com, movie 43.',
        ], ['user_agent' => 'Artisan movie-access:grant-player-test']);

        if (! $grant->order || ! $service->orderHasAccess($grant->order)) {
            $this->error('The previous test grant was revoked or its order is unavailable. Use Customers > Grant movie access to review it; no replacement was created.');
            return 1;
        }
        $this->info('Buy access is ready. Order #'.$grant->order_id.'; grant #'.$grant->id.'. Rerunning this command will not create another order.');
        $this->line('Sign in as jacob.atam@gmail.com, reopen movie 43 (or refresh My Library), and tap Play.');
        return 0;
    }
}

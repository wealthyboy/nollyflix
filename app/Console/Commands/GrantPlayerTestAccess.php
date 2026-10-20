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
        {--admin= : Legacy option, no longer required or used by this targeted grant}
        {--yes : Confirm this specific complimentary Buy grant without prompting}';

    protected $description = 'Grant jacob.atam@gmail.com complimentary Buy access to movie 43 for app player testing';

    public function handle(MovieAccessGrantService $service)
    {
        $customer = User::where('email', 'jacob.atam@gmail.com')->first();
        if (! $customer) {
            $this->error('Account jacob.atam@gmail.com was not found in this database. Nothing was changed.');
            return 1;
        }
        $video = Video::active()->whereNull('deleted_at')->find(43);
        if (! $video) {
            $this->error('Movie 43 was not found, is inactive or was deleted. Nothing was changed.');
            return 1;
        }

        if (! $video->link && ! $video->episodes()->whereNotNull('link')->where('link', '<>', '')->exists()) {
            $this->error('Movie 43 has no playable video or episode. Nothing was changed.');
            return 1;
        }

        $this->line('Account: '.$customer->email.' (role unchanged)');
        $this->line('Approved one-off CLI test grant; no admin permission record required.');
        $this->line('Movie: 43 — '.$video->title);
        $this->line('Access: Buy (no expiry). Complimentary order: 0. No payment, receipt, email or push will be sent.');
        if (! $this->option('yes') && (! $this->input->isInteractive()
            || ! $this->confirm('Grant this movie access?', false))) {
            $this->warn('Cancelled. Nothing was changed.');
            return 1;
        }

        $grant = $service->grant($customer, $customer, [
            // The same UUID makes reruns reuse the grant as well as its order.
            'grant_token' => Uuid::uuid5(Uuid::NAMESPACE_URL, 'nollyflix/player-test/'.$customer->id.'/43/buy')->toString(),
            'video_id' => 43,
            'purchase_type' => 'buy',
            'reason' => 'Complimentary app video-player test requested for jacob.atam@gmail.com, movie 43.',
        ], [
            'user_agent' => 'Artisan movie-access:grant-player-test',
            // The service limits this exception to Jacob + movie 43 + Buy.
            'player_test_recipient' => true,
        ]);

        if (! $grant->order || ! $service->orderHasAccess($grant->order)) {
            $this->error('The previous test grant was revoked or its order is unavailable. Use Customers > Grant movie access to review it; no replacement was created.');
            return 1;
        }
        $this->info('Buy access is ready. Order #'.$grant->order_id.'; grant #'.$grant->id.'. Rerunning this command will not create another order.');
        $this->line('Sign in as jacob.atam@gmail.com, reopen movie 43 (or refresh My Library), and tap Play.');
        return 0;
    }
}

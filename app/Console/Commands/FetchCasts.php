<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\User;

class FetchCasts extends Command
{
    protected $signature = 'fetch:casts';
    protected $description = 'Fetch casts and filmers from API';

    public function handle()
    {
        $this->info('Fetching casts from API...');
        
        $response = Http::get('https://nollyflix.tv/api/browse/filmers');

        if ($response->failed()) {
            $this->error('Failed to fetch data!');
            return 1;
        }

        $data = $response->json('data');

        foreach ($data as $item) {

            foreach ($item as $user) {

            // Save cast
            User::updateOrCreate(
                ['id' => $user['id']],
                [
                    'name' => $user['name'],
                    'last_name' => $user['last_name'],
                    'phone_number' => $user['phone_number'],
                    'description' => $user['description'],
                    'image' => $user['image'],
                    'type' => $user['type'],
                    'email' => $user['email'],
                    'email_verified_at' => $user['email_verified_at'],
                    'username' => $user['username'],
                    'cart_total' => $user['cart_total'],
                    'iso_code' => $user['iso_code'],
                    'profile_picture' => $user['profile_picture']
                ]
            );
        }

            // Optional: Save filmers with same data structure
        
        }

        $this->info('Casts and filmers synced successfully!');
        return 0;
    }
}

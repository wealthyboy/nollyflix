<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncCasts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:casts-filmers {url}';
    protected $description = 'Import videos and sections from API';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = $this->argument('url');
        $response = Http::get($url);

        if ($response->failed()) {
            $this->error("Failed to fetch data from API");
            return;
        }

        $data = $response->json('data');



    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixNigeriaCurrencyIsoCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:fix-nigeria-iso';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the iso_code3 value to NGN for the Nigeria record in the currencies table.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $updated = DB::table('currencies')
            ->where('country', 'Nigeria')
            ->update(['iso_code3' => 'NGN']);

        if ($updated > 0) {
            $this->info('Updated Nigeria iso_code3 to NGN.');
        } else {
            $this->warn('No Nigeria currency row was updated.');
            $this->line('Check that the `currencies` table contains a row where `country` = "Nigeria" and `iso_code3` is not already NGN.');
        }

        return 0;
    }
}

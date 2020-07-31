<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* `ohramhealth`.`currencies` */
        $currencies = array(
            array('id' => '19','created_at' => '2020-02-15 20:02:16','updated_at' => '2020-02-15 20:02:16','country' => 'Australian','symbol' => '$','iso_code2' => NULL,'iso_code3' => 'AUD'),
            array('id' => '20','created_at' => '2020-02-15 20:02:16','updated_at' => '2020-02-15 20:02:16','country' => 'Nigeria','symbol' => '₦','iso_code2' => NULL,'iso_code3' => 'NGR'),
            array('id' => '21','created_at' => '2020-02-15 20:02:16','updated_at' => '2020-02-15 20:02:16','country' => 'United States','symbol' => '$','iso_code2' => NULL,'iso_code3' => 'USD'),
            array('id' => '22','created_at' => '2020-02-15 20:02:16','updated_at' => '2020-02-15 20:02:16','country' => 'Europe','symbol' => '€','iso_code2' => NULL,'iso_code3' => 'EUR'),
            array('id' => '23','created_at' => '2020-02-15 20:02:16','updated_at' => '2020-02-15 20:02:16','country' => 'United Kingdom','symbol' => '£','iso_code2' => NULL,'iso_code3' => 'GBP')
        );

        foreach( $currencies as $key => $currency) 
        {    
            \DB::table('currencies')->insert([
                'country' => $currency['country'],
                'symbol' => $currency['symbol'],
                'iso_code2' => $currency['iso_code2'],
                'iso_code3' => $currency['iso_code3'],
            ]);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use App\Currency;
use App\CurrencyRate;
use App\SystemSetting;
use App\Http\Helper;
use Stevebauman\Location\Facades\Location;
use Carbon\Carbon;


class CurrencyByIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $rate = [];
        $position = '';
        $position = Location::get('169.197.85.171');
        $request->session()->put('country_name', optional($position)->countryName);
        $settings = SystemSetting::first();
        $nigeria = Currency::where('country', 'Nigeria')->first();
        $usa = Currency::where('country', 'United States')->first();
        $position = Location::get('169.197.85.171');
        $query = request()->all();
        $currentDate = Carbon::now();
        $startDate = Carbon::createFromDate(null, 12, 1);
        $endDate = Carbon::createFromDate(null, 12, 31);


        if (optional($settings)->allow_multi_currency) {

            if (isset($query['currency']) && strtok($query['currency'], '?') === 'USD') {
                $rate = ['rate' => 1, 'country' => $usa->country, 'code' => $usa->iso_code3, 'symbol' => $usa->symbol];
                $request->session()->put('rate', json_encode(collect($rate)));
                $request->session()->put('switch', 'USD');
                return $next($request);
            }

            if (isset($query['currency']) && strtok($query['currency'], '?')  === 'NGN') {
                $rate = ['rate' => $exchaange_rate, 'country' => 'Nigeria', 'code' => $nigeria->iso_code3,  'symbol' => $nigeria->symbol];
                $request->session()->put('rate', json_encode(collect($rate)));
                $request->session()->put('userLocation',  json_encode($position));
                return $next($request);
            }

            if ($request->session()->has('userLocation')) {

                if ($request->session()->has('switch') && empty($query)) {
                    return $next($request);
                }

                $user_location = json_decode(session('userLocation'));

                try {

                    $country = Currency::where('country', $position->countryName)->first();
                    $rate = null;

                    if ($position->countryName === 'Nigeria') {
                        $rate = ['rate' => $exchaange_rate, 'country' => $position->countryName, 'code' => $nigeria->iso_code3,  'symbol' => $nigeria->symbol];
                        $request->session()->put('switch', 'NGN');
                    } else {
                        $rate = ['rate' => 1, 'country' => $usa->country, 'symbol' => $usa->symbol];
                        $request->session()->put('switch', 'USD');
                    }
                    $request->session()->put('rate', json_encode(collect($rate)));
                    $request->session()->put('userLocation',  json_encode($position));

                    if ($user_location && $user_location->ip !== request()->ip()) {
                        $country = Currency::where('country', $position->countryName)->first();
                        $rate = null;
                        if ($position->countryName === 'Nigeria') {
                            $rate = ['rate' => $exchaange_rate, 'country' => $position->countryName, 'code' => $nigeria->iso_code3,  'symbol' => $nigeria->symbol];
                            $request->session()->put('switch', 'NGN');
                        } else {
                            $rate = ['rate' => 2, 'country' => $usa->country, 'symbol' => $usa->symbol];
                            $request->session()->put('switch', 'USD');
                        }
                        $request->session()->put('rate', json_encode(collect($rate)));
                        $request->session()->put('userLocation',  json_encode($position));
                    }
                } catch (\Throwable $th) {
                    //throw $th;

                }
            } else {



                try {

                    $country = Currency::where('country', $position->countryName)->first();
                    $rate = null;

                    if ($position->countryName === 'Nigeria') {
                        $rate = ['rate' => $exchaange_rate, 'country' => $position->countryName, 'code' => $country->iso_code3,  'symbol' => $country->symbol];
                        $request->session()->put('switch', 'NGN');
                    } else {
                        $country = Currency::where('country', 'United States')->first();
                        $rate = ['rate' => 3, 'country' => $country->name, 'symbol' => $country->symbol];
                        $request->session()->put('switch', 'USD');
                    }

                    $request->session()->put('rate', json_encode(collect($rate)));
                    $request->session()->put('userLocation',  json_encode($position));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        } else {

            // $request->session()->put('switch', 'NGN');
            $request->session()->forget(['rate']);
        }




        return $next($request);
    }
}

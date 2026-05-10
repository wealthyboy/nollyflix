<?php

namespace App\Http\Controllers\CurrencySwitcher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Currency;

class CurrencySwitcherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currency = Currency::find($request->currency_id);

        if ($currency->country == 'Nigeria') {
            $rate = ['rate' => 1, 'country' => $currency->country,  'code' => $currency->iso_code3, 'symbol' => $currency->symbol];
            $switch = 'NGN';
        } else {
            $rate = ['code' =>  optional($currency)->iso_code3, 'rate' => optional($currency->rate)->rate, 'country' => $currency->country, 'symbol' => $currency->symbol];
            $switch = 'USD';
        }
        $request->session()->put('rate', json_encode(collect($rate)));
        $request->session()->put('switch', $switch);
        return back();
    }
}

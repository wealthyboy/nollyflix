<?php

namespace App\Http\Controllers\CurrencySwitcher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Currency;

class CurrencySwitcherController extends Controller
{
    /**
     * Manually select the storefront currency.
     *
     * Nollyflix stores NGN and USD prices independently. Switching currency
     * never converts an amount and never uses the live currency-rate table.
     */
    public function index(Request $request)
    {
        $code = strtoupper(trim((string) $request->query('currency')));

        // Keep the legacy currency_id request working for any older UI that
        // still posts a Currency record id.
        if (! in_array($code, ['NGN', 'USD'], true) && $request->filled('currency_id')) {
            $currency = Currency::find($request->currency_id);
            $code = strtoupper((string) optional($currency)->iso_code3);
        }

        abort_unless(in_array($code, ['NGN', 'USD'], true), 422, 'Unsupported currency.');

        $symbol = $code === 'USD' ? '$' : '₦';
        $country = $code === 'NGN' ? 'Nigeria' : 'Manual selection';

        // Mark this as an explicit customer choice so CurrencyByIp does not
        // replace it on the redirect back to the movie page.
        $request->session()->put('currency_manual', $code);
        $request->session()->put('switch', $code);
        $request->session()->put('rate', json_encode(collect([
            'rate' => 1,
            'country' => $country,
            'code' => $code,
            'symbol' => $symbol,
        ])));

        return back();
    }
}

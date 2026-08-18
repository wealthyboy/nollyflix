<?php

namespace App\Http\Controllers\WebHook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\User;
use App\Order;
use App\Cart;
use App\Currency;
use App\Voucher;
use App\Mail\OrderReceipt;
use App\SystemSetting;
use App\PaymentTransaction;
use Illuminate\Support\Facades\Http;



class WebHookController extends Controller
{

    public  $settings;

    public function __construct()
    {
        $this->settings = SystemSetting::first();
    }


    public function payment(Request $request, Order $order)
    {
        abort_unless($this->validFlutterwaveSignature($request), 401, 'Invalid webhook signature.');

        $transactionId = data_get($request->all(), 'data.id');
        abort_unless($transactionId, 422, 'Missing transaction ID.');

        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 250)
            ->get('https://api.flutterwave.com/v3/transactions/'.$transactionId.'/verify');
        $verified = $response->json('data', []);
        $payment = PaymentTransaction::where('tx_ref', $verified['tx_ref'] ?? '')->first();

        abort_unless(
            $response->successful()
            && $payment
            && ($verified['status'] ?? null) === 'successful'
            && strtoupper($verified['currency'] ?? '') === $payment->currency
            && (float) ($verified['charged_amount'] ?? $verified['amount'] ?? 0) >= (float) $payment->amount,
            422,
            'Payment verification failed.'
        );

        // Persist the verified payload. The authenticated checkout confirmation
        // endpoint performs the idempotent order/entitlement creation.
        $payment->update(['verification_payload' => $verified]);

        return response()->json(['received' => true]);
    }

    private function validFlutterwaveSignature(Request $request)
    {
        $secretHash = (string) config('services.flutterwave.secret_hash');
        if ($secretHash === '') {
            return false;
        }

        $hmacSignature = (string) $request->header('flutterwave-signature');
        if ($hmacSignature !== '') {
            $expected = base64_encode(hash_hmac('sha256', $request->getContent(), $secretHash, true));
            return hash_equals($expected, $hmacSignature);
        }

        $legacySignature = (string) $request->header('verif-hash');
        return $legacySignature !== '' && hash_equals($secretHash, $legacySignature);
    }

    public function gitHub()
    {
        $output = shell_exec('sh /home/forge/nollyflix.tv/deploy.sh');
        return  $output;
    }
}

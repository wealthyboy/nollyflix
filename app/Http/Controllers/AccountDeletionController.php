<?php

namespace App\Http\Controllers;

use App\Services\AccountDeletionService;
use App\User;
use Illuminate\Http\Request;

class AccountDeletionController extends Controller
{
    public function show()
    {
        return $this->page();
    }

    public function store(Request $request, AccountDeletionService $service)
    {
        $data = $request->validate(['email' => 'required|email|max:128']);
        $service->sendConfirmation($data['email']);

        return redirect()->route('account-deletion.show')->with('status', AccountDeletionService::MESSAGE);
    }

    public function mobile(Request $request, AccountDeletionService $service)
    {
        // The caller cannot nominate a different account.
        $service->sendConfirmation($request->user()->email);

        return response()->json(['message' => AccountDeletionService::MESSAGE], 202);
    }

    public function confirm(Request $request, $user, AccountDeletionService $service)
    {
        $account = User::find($user);
        if (!$request->hasValidSignature() || !$account || !hash_equals($service->fingerprint($account), (string) $request->query('fingerprint'))) {
            return $this->page(['invalidLink' => true], 403);
        }

        // Email scanners/prefetchers may GET this URL. Only an explicit CSRF-protected POST records consent.
        if ($request->isMethod('get')) {
            return $this->page(['confirmationUrl' => $request->fullUrl()]);
        }

        $request->validate(['acknowledge' => 'accepted']);
        $deletion = $service->confirm($account);

        return redirect()->route('account-deletion.show')->with('status',
            'Your verified deletion request has been received. Reference: '.$deletion->reference.
            '. Your account has not been deleted yet. Contact support@nollyflix.tv with this reference for updates.');
    }

    private function page(array $data = [], $status = 200)
    {
        return response()->view('account-deletion', $data, $status)
            ->header('Cache-Control', 'no-store, private')
            ->header('Referrer-Policy', 'no-referrer')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}

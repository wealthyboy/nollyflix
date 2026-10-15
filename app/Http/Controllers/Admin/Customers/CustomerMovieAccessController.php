<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Controller;
use App\MovieAccessGrant;
use App\Services\MovieAccessGrantService;
use App\User;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerMovieAccessController extends Controller
{
    public function create($customer)
    {
        $customer = User::customers()->findOrFail($customer);
        $videos = Video::active()->whereNull('deleted_at')->orderBy('title')->get(['id', 'title']);
        $grants = MovieAccessGrant::with('video', 'order.cart', 'administrator')
            ->where('customer_id', $customer->id)->latest()->limit(20)->get();

        return view('admin.customers.movie-access', compact('customer', 'videos', 'grants'));
    }

    public function store(Request $request, MovieAccessGrantService $service, $customer)
    {
        $customer = User::customers()->findOrFail($customer);
        $data = $request->validate([
            'grant_token' => 'required|uuid',
            'video_id' => ['required', 'integer', Rule::exists('videos', 'id')->where('is_active', true)->whereNull('deleted_at')],
            'purchase_type' => 'required|in:rent,buy',
            'reason' => 'required|string|max:1000',
            'payment_reference' => 'nullable|string|max:128',
            'confirmed' => 'accepted',
        ]);
        $grant = $service->grant($request->user(), $customer, $data, [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        $sent = $service->sendEmail($grant);

        return redirect()->route('customers.movie-access.create', ['customer' => $customer->id])
            ->with($sent ? 'access_success' : 'access_warning', $sent
                ? 'Movie access is ready and the login-to-watch email has been sent to '.$customer->email.'.'
                : 'Movie access has been saved, but the email could not be sent. Use “Resend email” below; do not create another order.');
    }

    public function resend(Request $request, MovieAccessGrantService $service, $customer, $grant)
    {
        $customer = User::customers()->findOrFail($customer);
        $grant = MovieAccessGrant::where('customer_id', $customer->id)->findOrFail($grant);
        if (! $grant->order || ! $service->orderHasAccess($grant->order)) {
            return redirect()->route('customers.movie-access.create', ['customer' => $customer->id])
                ->with('access_warning', 'This access has expired or been revoked. Grant new access if needed; resending cannot renew an order.');
        }
        $sent = $service->sendEmail($grant, true);

        return redirect()->route('customers.movie-access.create', ['customer' => $customer->id])
            ->with($sent ? 'access_success' : 'access_warning', $sent
                ? 'Access email sent to '.$customer->email.'. The order and rental expiry were not changed.'
                : 'The email could not be sent. The existing order and access have not been changed. Please retry.');
    }
}

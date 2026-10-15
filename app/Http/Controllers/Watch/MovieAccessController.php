<?php

namespace App\Http\Controllers\Watch;

use App\Http\Controllers\Controller;
use App\MovieAccessGrant;
use App\Services\MovieAccessGrantService;
use Illuminate\Http\Request;

class MovieAccessController extends Controller
{
    public function show(Request $request, MovieAccessGrant $grant, MovieAccessGrantService $service)
    {
        // This link is not a login token. Forwarding it never gives another account access.
        abort_unless((int) $grant->customer_id === (int) $request->user()->id, 403, 'Please sign in with the account that received this movie.');
        abort_unless($grant->video && $grant->video->is_active && ! $grant->video->deleted_at, 404, 'This title is currently unavailable.');
        if (! $grant->order || ! $service->orderHasAccess($grant->order)) {
            return redirect()->route('browse.show', ['video' => $grant->video->slug])
                ->with('error', 'This movie access has expired or been revoked. Please contact support if you need help.');
        }

        return redirect()->route('watch', ['video' => $grant->video->slug]);
    }
}

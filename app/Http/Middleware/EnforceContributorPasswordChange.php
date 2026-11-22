<?php

namespace App\Http\Middleware;

use Closure;

class EnforceContributorPasswordChange
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if ($user && in_array($user->type, ['casts', 'filmakers'], true)
            && $user->must_change_password
            && !$request->is('contributor/password')
            && !$request->is('logout')
            && !$request->is('admin/logout')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Please change your temporary password first.', 'redirect' => route('contributor.password.edit')], 403);
            }
            return redirect()->route('contributor.password.edit');
        }
        return $next($request);
    }
}

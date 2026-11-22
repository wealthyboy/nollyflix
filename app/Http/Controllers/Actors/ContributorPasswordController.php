<?php

namespace App\Http\Controllers\Actors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ContributorPasswordController extends Controller
{
    public function edit(Request $request)
    {
        abort_unless(in_array($request->user()->type, ['casts', 'filmakers'], true), 403);
        return view('actors.change-password', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        abort_unless(in_array($user->type, ['casts', 'filmakers'], true), 403);
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:12|max:72|confirmed|different:current_password',
        ]);
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        $user->password = Hash::make($request->password);
        $user->temporary_password_ciphertext = null;
        $user->must_change_password = false;
        $user->setRememberToken(\Illuminate\Support\Str::random(60));
        $user->save();
        return redirect()->route('actors.dashboard')->with('success', 'Your password has been updated.');
    }
}

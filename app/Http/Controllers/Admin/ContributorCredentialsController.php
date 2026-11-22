<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContributorCredentials;
use App\User;
use Illuminate\Http\Request;

class ContributorCredentialsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function revealCast($id, ContributorCredentials $credentials)
    {
        return $this->reveal($id, 'casts', $credentials);
    }

    public function revealFilmer($id, ContributorCredentials $credentials)
    {
        return $this->reveal($id, 'filmakers', $credentials);
    }

    private function reveal($id, $type, ContributorCredentials $credentials)
    {
        $user = User::where('type', $type)->findOrFail($id);
        $password = $credentials->availablePassword($user);
        return response()->json(['available' => $password !== null, 'password' => $password])
            ->header('Cache-Control', 'no-store, private')
            ->header('Pragma', 'no-cache');
    }

    public function resetCast($id, ContributorCredentials $credentials)
    {
        return $this->reset($id, 'casts', 'casts.show', $credentials);
    }

    public function resetFilmer($id, ContributorCredentials $credentials)
    {
        return $this->reset($id, 'filmakers', 'filmers.show', $credentials);
    }

    private function reset($id, $type, $route, ContributorCredentials $credentials)
    {
        $user = User::where('type', $type)->findOrFail($id);
        $credentials->createPassword($user);
        return redirect()->route($route, $user->id)
            ->with('success', 'New temporary password generated. Reveal and copy it securely from this page. Previous password is now invalid.');
    }
}

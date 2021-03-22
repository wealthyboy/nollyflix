<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function action(RegisterRequest $request)
    {
        $user = User::create($request->only('email', 'name', 'password'));

        return new PrivateUserResource($user);
    }
}

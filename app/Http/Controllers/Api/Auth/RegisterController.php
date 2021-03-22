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
        $user = User::create([
                        'name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email'=> $request->email,
                        'password' => bcrypt($request->password)
                    ]);

        return new PrivateUserResource($user);
    }
}

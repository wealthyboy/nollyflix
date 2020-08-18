<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Hash;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $user = auth()->user();
        $active = "profile";
        return view('profile.index',compact('user','active'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ActorsAndFilMakers($username)
    {   
        $user = User::where('username',$username)->firstOrFail();
        if ($user){
            session(['content_owner_id' => $user->id]);
        }
        dd(session('content_owner_id'));
        return view('profile.profile',compact('user'));
    }

    public function changePassword(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|max:255',
            'password' => 'bail|required|confirmed|min:6',
        ]);
        if (Hash::check($request->old_password, $request->user()->password)) {
            $request->user()->fill([
                'password' => Hash::make($request->password)
            ])->save();
            //event(new ChangePassword($request->user()));
            return back()->with('success','Password Updated.');
        }  else { 
            //     $validator->after(function ($validator) {
            //     $validator->errors()->add('password', 'Your old password does not match our records.');
            // });
            // event(new ChangePassword($request->user()));
            
            return back()->with('errors','Your old password does not match our records.');
        } 
        return false;
    }


     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {   

        $user = User::find($id);
        $user->name = $request->name;
        $user->last_name  = $request->last_name;
        $user->description = $request->description;
        $user->save();
        return back()->with('success','Profile Updated.');
    }
}

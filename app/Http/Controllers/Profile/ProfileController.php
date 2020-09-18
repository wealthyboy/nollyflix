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
       $this->middleware('auth',['except' => [
        'ActorsAndFilMakers'
            ]
        ]);
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




    public function updateImage(Request $request)
    {   
        if($request->hasFile('file')){

            //when the user clicks change remove the previuos image
            request()->validate([
               'file' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            $path = $request->file('file')->store('images/users');
            $file = basename($path);
            $path =  public_path('images/users/'.$file);
            $img  = \Image::make($path)->fit(200, 200)->save(
                public_path('images/users/m/'.$file)
            );
         
            $path = asset('images/users/'.$file);

            $user = auth()->user();
            $user->image = $path;
            $user->save();
            return $path = asset('images/users/'.$file);
        }
    }
}

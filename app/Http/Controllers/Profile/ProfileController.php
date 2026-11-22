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
        $page_title = "Buy ,Rent Movies, Profile for {$user->fullname()}";
        $page_meta_description = "Buy nollywood movies, {$user->fullname()}";
        return view('profile.index',compact('user','active','page_title','page_meta_description'));
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
        $user->setRelation('profile_videos', $user->profile_videos()->active()->get());

        if ($user){
            session(['content_owner_id' => $user->id]);
        }

        $page_title = "Buy ,Rent Movies, Profile for {$user->fullname()}";
        $page_meta_description = "Buy nollywood movies, {$user->fullname()}";
        return view('profile.profile',compact('user','page_title','page_meta_description'));
    
    }

    public function changePassword(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|max:255',
            'password' => 'bail|required|confirmed|min:6',
        ]);
        if (Hash::check($request->old_password, $request->user()->password)) {
            $user = $request->user();
            $user->password = Hash::make($request->password);
            $user->temporary_password_ciphertext = null;
            $user->must_change_password = false;
            $user->save();
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
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:8192',
        ]);

        // The configured local disk already points to public_path(). Use it explicitly
        // so uploads do not unexpectedly switch to S3 or another default disk.
        $file = $request->file('file');
        $storedPath = $file->store('images/users', 'local');
        if (!$storedPath) {
            return response()->json(['message' => 'Unable to save profile photo.'], 500);
        }

        $absolutePath = public_path($storedPath);
        $thumbnailDir = public_path('images/users/m');
        if (!is_dir($thumbnailDir) && !@mkdir($thumbnailDir, 0755, true) && !is_dir($thumbnailDir)) {
            \Log::warning('Profile image thumbnail directory is not writable.');
        }

        // Thumbnail generation is optional. The original file is always available,
        // even on servers lacking the GD/Imagick image extension.
        if (is_dir($thumbnailDir) && is_writable($thumbnailDir)) {
            try {
                \Image::make($absolutePath)->fit(300, 300)->save($thumbnailDir . '/' . basename($storedPath));
            } catch (\Throwable $exception) {
                \Log::warning('Profile thumbnail creation failed: ' . $exception->getMessage());
            }
        }

        $user = $request->user();
        $user->image = asset($storedPath);
        $user->save();

        return response()->json(['url' => $user->m_path(), 'message' => 'Profile image updated.']);
    }
}

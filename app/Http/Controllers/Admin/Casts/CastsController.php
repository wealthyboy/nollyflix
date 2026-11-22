<?php

namespace App\Http\Controllers\Admin\Casts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Validation\Rule;
use App\Services\ContributorCredentials;




class CastsController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin'); 
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        $casts = (new User())->castings()->latest()->get();
        return  view('admin.casts.index', compact('casts'));  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('admin.casts.create');  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ContributorCredentials $credentials)
    {   

        request()->validate([
            'email'       => 'nullable|email|max:255|unique:users',
            'first_name'  => 'required|min:1|max:100',
            'username'    => 'nullable|string|max:255|unique:users',
            'description' => 'required|min:1|max:1000',
        ]);

        $user = new User;
        $user->name = $request->first_name;
        $user->last_name=$request->last_name;
        $user->slug=str_slug($request->first_name.' '.$request->last_name);
        $user->email  = $request->filled('email') ? $request->email : $credentials->generatedEmail($request->first_name, $request->last_name);
        $user->username = $request->filled('username') ? str_slug($request->username) : $credentials->generatedUsername($request->first_name, $request->last_name);
        $user->description  =$request->description;
        $user->image  =$request->image;
        $user->type   ='casts';
        $user->password = bcrypt(\Illuminate\Support\Str::random(32));
        $user->save();
        $credentials->createPassword($user);
        return redirect()->route('casts.show', $user->id)->with('success', 'Actor created. Reveal and copy the temporary password below. No email was sent.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $cast = User::where('type', 'casts')->findOrFail($id);
        return  view('admin.casts.show',compact('cast'));  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cast = User::where('type', 'casts')->findOrFail($id);

        return view('admin.casts.edit', compact('cast'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cast = User::where('type', 'casts')->findOrFail($id);

        $request->validate([
            'first_name'  => 'required|min:1|max:100',
            'last_name'   => 'nullable|max:100',
            'email'       => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($cast->id)],
            'username'    => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($cast->id)],
            'description' => 'required|min:1|max:1000',
            'image'       => 'nullable|string',
            'password'    => 'nullable|string|min:8|max:72|confirmed',
        ]);

        $cast->name = $request->first_name;
        $cast->last_name = $request->last_name;
        $cast->slug = str_slug(trim($request->first_name.' '.$request->last_name));
        $cast->email = $request->email;
        $cast->username = str_slug($request->username);
        $cast->description = $request->description;
        $cast->image = $request->image ?: null;
        $cast->type = 'casts';
        if ($request->filled('password')) {
            $cast->password = bcrypt($request->password);
            $cast->temporary_password_ciphertext = null;
            $cast->must_change_password = false;
            $cast->setRememberToken(str_random(60));
        }
        $cast->save();

        return redirect()->route('casts.show', ['cast' => $cast->id])
            ->with('success', 'Cast updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

   
    
    public function destroy(Request $request) 
	{ 
		$rules = array(
			'_token' => 'required',
		);
		$validator = \Validator::make($request->all(),$rules);
		
		if ( empty ( $request->selected)) {
			$validator->getMessageBag()->add('Selected', 'Nothing to Delete');    
			return \Redirect::back()
						->withErrors($validator)
						->withInput();
        }
        
        $users = User::find($request->selected);
        
        foreach($users as $user){
            $user->forcedelete();
        }
       
				
		return redirect()->back()->with('success','Deleted');
	}
}

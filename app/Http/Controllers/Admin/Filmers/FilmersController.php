<?php

namespace App\Http\Controllers\Admin\Filmers
;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Events\FilmerCreated;
use Illuminate\Support\Facades\Validator;
use App\Notifications\FilmerEmailNotification;
use Illuminate\Support\Facades\Notification;




class FilmersController extends Controller
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
        $user = User::withTrashed()->where(["email"=>'sneezefilmes@yahoo.com'])->get();
        dd($user);
        $filmers = (new User())->filmers()->latest()->get();
        return   view('admin.filmers.index', compact('filmers'));  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('admin.filmers.create');  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        
        Validator::make($request->all(), [
            'first_name'   => 'required|min:1|max:100',
            'email'=>['required','email',\Rule::unique('users','email')->whereNotNull('deleted_at')],
            'username'    => 'required|string|max:255|unique:users',
            'last_name'    => 'required|min:1|max:200',
            'description'  => 'required|min:1|max:1000',
        ]);

        $data  = collect($request->except('uimage'));
        $password = str_random(8);
        $user = new User;
        $user->name         =  $request->first_name;
        $user->last_name    =  $request->last_name;
        $user->email        =  $request->email;
        $user->slug=str_slug($request->first_name.' '.$request->last_name);
        $user->description  =  $request->description;
        $user->username  =     $request->username;
        $user->image        =  $request->image;
        $user->type         =  'filmakers';
        $user->password= bcrypt($password); 
        $user->save();
        $data['password'] = $password;

        /**
         * Send  Email Notification 
         */
        Notification::route('mail', $request->email)
           ->notify(new FilmerEmailNotification($data));
        
        return redirect()->route('filmers.index')->with('success','An email has been sent to '.$request->email);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $filmer = User::find($id);
        return  view('admin.filmers.show',compact('filmer'));  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
		// dd(get_class(\new Validator));
		$validator = \Validator::make($request->all(),$rules);
		
		if ( empty ( $request->selected)) {
			$validator->getMessageBag()->add('Selected', 'Nothing to Delete');    
			return \Redirect::back()
						->withErrors($validator)
						->withInput();
		}
				
		User::destroy($request->selected);
		return redirect()->back();
	}
}

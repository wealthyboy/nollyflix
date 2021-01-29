<?php

namespace App\Http\Controllers\Admin\Casts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\Notification;
use App\Notifications\CastsEmailNotification;




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
        $users  = User::whereNotNull('deleted_at')->get();
        dd($users);


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
    public function store(Request $request)
    {   

        request()->validate([
            'email'       => 'required|email|max:255|unique:users',
            'first_name'  => 'required|min:1|max:100',
            'username'    => 'required|string|max:255|unique:users',
            'description' => 'required|min:1|max:1000',
        ]);

        $data  = collect($request->except('uimage'));
        $password = str_random(8);
        $user = new User;
        $user->name = $request->first_name;
        $user->last_name=$request->last_name;
        $user->slug=str_slug($request->first_name.' '.$request->last_name);
        $user->email  =$request->email;
        $user->username  =str_slug($request->username);
        $user->description  =$request->description;
        $user->image  =$request->image;
        $user->type   ='casts';
        $user->password= bcrypt($password); 
        $user->save();
        $data['password'] = $password;
        /**
         * Send Email Notification
        */

        Notification::route('mail', $request->email)
        ->notify(new CastsEmailNotification($data));


        return redirect()->route('casts.index')->with('success','An email has been sent to '.$request->email);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $cast = User::find($id);
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

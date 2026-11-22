<?php

namespace App\Http\Controllers\Admin\Filmers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Events\FilmerCreated;
use Illuminate\Support\Facades\Validator;
use App\Notifications\FilmerEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use App\Services\ContentAttributionStats;
use App\Services\ContributorCredentials;





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
        $filmers = (new User())->filmers()->withCount('attributed_orders')->latest()->get();
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
    public function store(Request $request, ContributorCredentials $credentials)
    {   

        $this->validate($request,[           
            'first_name'   => 'required|min:1|max:100',
            'email'    => 'nullable|email|max:255|unique:users',
            'username'    => 'nullable|string|max:255|unique:users',
            'last_name'    => 'required|min:1|max:200',
            'description'  => 'required|min:1|max:1000',
        ]);

        $user = new User;
        $user->name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->filled('email') ? $request->email : $credentials->generatedEmail($request->first_name, $request->last_name);
        $user->slug = str_slug($request->first_name.' '.$request->last_name);
        $user->description = $request->description;
        $user->username = $request->filled('username') ? str_slug($request->username) : $credentials->generatedUsername($request->first_name, $request->last_name);
        $user->image = $request->image;
        $user->type = 'filmakers';
        $user->password = bcrypt(\Illuminate\Support\Str::random(32));
        $user->save();
        $credentials->createPassword($user);

        return redirect()->route('filmers.show', $user->id)
            ->with('success', 'Filmmaker created. Reveal and copy the temporary password below. No email was sent.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, ContentAttributionStats $attributionStats)
    {
        $filmer = User::where('type', 'filmakers')->with('filmer_videos')->findOrFail($id);
        $attribution = $attributionStats->forFilmer($filmer);
        $attributedOrders = $attribution['orders'];
        $movieBreakdown = $attribution['movieBreakdown'];
        $stats = $attribution['stats'];

        return view('admin.filmers.show', compact('filmer', 'attributedOrders', 'movieBreakdown', 'stats'));  
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
				
        $users = User::find($request->selected);
        
        foreach($users as $user){
            $user->forcedelete();
        }
        
		return redirect()->back();
	}
}

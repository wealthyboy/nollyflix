<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Helper;
use Illuminate\Auth\Events\Registered;
use App\Mail\ConfirmationEmail;
use App\Voucher;
use App\Mail\NewUserCoupon;
use App\State;
use App\Permission;
use App\Activity;
use App\Newsletter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
	
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {   
	    //check to SEE IF WE ARE IN ADMIN
        if ( strpos($request->url() ,'admin') !== false ) { 
		    $this->middleware('admin'); 
		} else{
			$this->middleware('guest');
		}
    }
	
	
	/**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {   
        $this->validator($request->all())->validate();
		event(new Registered($user = $this->create($request->all())));
		
	    if ( $request->is ('admin/*') ) { 
		    return redirect('/admin/users')->with('status','User Created');
		}

		$this->guard()->login($user);

		if ( $request->ajax() ) { 

		    return response()->json([
				'loggedIn'=>true,
				'user' => auth()->user()
			],200);
		}
        
		return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
	
	public function showRegistrationForm(Request $request)
    {   
		if ( \Auth::check()) { 
		   return redirect('/browse');
		}
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {    
        $request = new Request();
        			
		if ( isset($data['admin'] )){
			return Validator::make($data, [
				'name' => ['required', 'string', 'max:255'],
				'last_name' => ['required', 'string', 'max:255'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'password' => ['required', 'string', 'min:6'],
			]);
		}  

		return Validator::make($data, [
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
		  
    }
	
	
	public function verifyEmail($token='') { 
	  
	  $id = User::whereToken($token)->firstOrFail();
	  
	    if ($id) { 
	 
			User::whereToken($token)->firstOrFail()->hasVerified();
			// Login and "remember" the given user...
			\Auth::loginUsingId($id->id, true);
			//send coupon
			$code = str_random(6);
			Voucher::newUserCoupon(5,$code);
			$user = \Auth::user();
			//mail the user 
			\Mail::to($user->email)->send(new NewUserCoupon($user,$code));
			//create coupon
			$cookie=\Cookie::get('cart');
			 
		    if (\Cookie::get('cart')!== null) {
				 /* CHECK IF we have a record*/ 
			    $check = Cart::where('remember_token',\Cookie::get('cart'))->get();
			    /*Redirect if we dont have a record*/
			if ( $check->count() ) { 
			     return redirect('/checkout');
			    }
		    }
 
		return \Redirect::to('/')->with('status',"Hello  {$id->name} You can continue to explore our services");
		
	  }
	   return view('auth.login');
	}


	public function verify(Request $request) { 
        if ( $request->session()->has('user_email')) { 
	      return view('auth.verify');
	    }	   
	   return view('errors.404');
	}
	
	/**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
		return redirect()->intended($this->redirectPath());
    }
	
	
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {    
		
		$user = new User();
		
		dd($data);
        
        $request = new Request();
        
        if ( isset($data['admin'] ))
        {
			$user->name = $data['name'];
			$user->last_name=$data['last_name'];
            $user->email=$data['email'];
            $user->type = 'admin';
			$user->password=bcrypt($data['password']);
			$user->save();
			$user->users_permission()->create([
				'permission_id'=>$data['permission_id']
			]);
			return $user;
		}

		// Newsletter::create([
		// 	'email'=> $request->email
		// ]);

		$user->name=$data['first_name'];
		$user->last_name=$data['last_name'];
		$user->email=$data['email'];
		$user->type  = 'subscriber';
		$user->password=bcrypt($data['password']);
		$user->save();
		return $user;
    }
	
	
	
	 
}

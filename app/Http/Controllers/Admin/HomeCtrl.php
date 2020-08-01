<?php 

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Video;



class HomeCtrl extends Controller
{
	
	protected $redirectTo = '/admin/login';

	/**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct()
     {
          $this->middleware('admin'); 
     }

	public function index() { 
        
        $casts = (new User())->castings()->latest()->count();
        $users = (new User())->customers()->latest()->count();
        $videos = Video::count();
        $filmers = (new User())->filmers()->latest()->count();
        return view('admin.index',\compact('casts','users','filmers','videos')); 
     }
     
	
}
?>
<?php  namespace App\Http\Controllers\Admin\Activity;

use Illuminate\Http\Request;




use App\Activity;


use App\Http\Controllers\Controller;


class ActivityController extends Controller
{
    //
	 
	 
	public function __construct()
    {
		$this->middleware('admin');
    }

	public function index()
	{
		$activities = Activity::all();
	    return view('admin.activity.index',compact('activities'));
    }

	protected function delete($id)
    {   
	    $users = Activity::find($id);
	    $users->delete();  
		return redirect()->back();
    }
	
	
	 
}

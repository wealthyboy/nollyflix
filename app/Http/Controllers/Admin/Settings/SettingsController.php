<?php  namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;

use App\Http\Requests\SystemSettingsRequest;

use App\SystemSetting;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Flash;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Currency;
use App\Payment;



class SettingsController extends Controller
{
    //
	
	
	public function __construct()
    {
        $this->middleware('admin'); 
    }

	public function index()
	{
		$setting = SystemSetting::first();
		if ( $setting) { 
		  	 return view('admin.settings.index',compact('setting'));
		}
		$currencies = Currency::all();
		User::canTakeAction(5);
	    return view('admin.settings.create',compact('setting','currencies'));
    }


	public function edit($id)
	{
		User::canTakeAction(4);
		$setting    = SystemSetting::find($id); 
		$currencies = Currency::all();
		return view('admin.settings.create', compact('setting','currencies'));	
    }
	

	public function update(Request $request,$id)
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$settings = SystemSetting::find($id); 
		$file_logo = '';
		$logo = '';	
	
		if ( $request->file('image') )  { 
			$file_logo = $request->file('image');
			$logo      =  !empty($file_logo->getClientOriginalName()) ?  time().$file_logo->getClientOriginalName() :  '' ;
			$file_logo->move('images/logo',$logo);
			$settings->store_logo = $logo;
		    $settings->store_icon =  $logo;
		}

		$settings->image =  null;
		$settings->site_name             =$request->site_name;
		$settings->address               =$request->address;
		$settings->site_email            =$request->site_email;
		$settings->site_phone            =$request->site_phone;
		$settings->meta_title            =$request->meta_title;
		$settings->alert_email           =$request->alert_email;
		$settings->invoice_prefix            =$request->invoice_prefix;
		$settings->allow_multi_currency      = $request->allow_multi_currency ? true : false;
		$settings->allow_multiple_logins    = $request->allow_multiple_logins ? true : false;
		$settings->logins         = $request->logins;
		$settings->meta_description             =$request->meta_description;
		$settings->meta_tag_keywords            =$request->meta_tag_keywords;
		$settings->facebook_link                =$request->facebook_link;
		$settings->instagram_link               =$request->instagram_link;
		$settings->twitter_link                 =$request->twitter_link;
		$settings->youtube_link                 =$request->youtube_link;
		$settings->currency_id                  =$request->currency_id;
		$settings->items_per_page      =$request->tems_per_page;
		$settings->save();
		return \Redirect::to('/admin/settings')->with('success','Settings Added');
    }
	
	 
	public function store(Request $request)
	{
		//Check the databse for the owner
		$ip = $_SERVER['REMOTE_ADDR'];
		$settings =  new SystemSetting;
		$file_logo = '';
		$logo = '';	
		if ( $request->file('image') )  { 
			$file_logo = $request->file('image');
			$logo      =  !empty($file_logo->getClientOriginalName()) ?  time().$file_logo->getClientOriginalName() :  '' ;
			$file_logo->move('images/logo',$logo);
		}
		$settings->store_logo = $logo;
		$settings->store_icon =  $logo;
		$settings->image =  null;
		$settings->site_name             =$request->site_name;
		$settings->address               =$request->address;
		$settings->site_email            =$request->site_email;
		$settings->site_phone            =$request->site_phone;
		$settings->meta_title            =$request->meta_title;
		$settings->alert_email           =$request->alert_email;
		$settings->invoice_prefix            =$request->invoice_prefix;
		$settings->allow_multi_currency      = $request->allow_multi_currency ? true : false;
		$settings->allow_multiple_logins    = $request->allow_multiple_logins ? true : false;
		$settings->logins         = $request->logins;
		$settings->meta_description             =$request->meta_description;
		$settings->meta_tag_keywords            =$request->meta_tag_keywords;
		$settings->facebook_link                =$request->facebook_link;
		$settings->instagram_link               =$request->instagram_link;
		$settings->twitter_link                 =$request->twitter_link;
		$settings->youtube_link                 =$request->youtube_link;
		$settings->currency_id                  =$request->currency_id;
		$settings->items_per_page      =$request->tems_per_page;
		//$settings->max_file_size                =$request->max_file_size;
		$settings->save();
		//$flash = app('App\Http\Flash');
		//$flash->success("Success","Inserted");
		return \Redirect::to('/admin/settings')->with('success','Settings Added');
	}
	    
}

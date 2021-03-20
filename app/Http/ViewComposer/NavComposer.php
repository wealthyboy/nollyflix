<?php  

namespace App\Http\ViewComposer;

use  App\User;
use Illuminate\View\View;

use Auth;
use App\Category;
use App\SystemSetting;
use App\Currency;
use App\Information;

class   NavComposer { 
   
   
    public function compose (View $view) { 
		$global_categories = Category::parents()->orderBy('id', 'asc')->get();
		$system_settings = SystemSetting::first();
		$currencies = Currency::all();
		$footer_info = Information::parents()->get(); 


	    $view->with([
		   	'global_categories'=> $global_categories,
			'system_settings'=>$system_settings,
			'currencies' =>$currencies,
			'footer_info' =>$footer_info
	    ]);
    

}




}
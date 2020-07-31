<?php  

namespace App\Http\Viewcomposer;

use  App\User;
use Illuminate\View\View;

use Auth;
use App\Category;
use App\SystemSetting;
use App\Currency;

class   NavComposer { 
   
   
    public function compose (View $view) { 
		$global_categories = Category::parents()->orderBy('id', 'asc')->get();
		$system_settings = SystemSetting::first();
		$currencies = Currency::all();

	    $view->with([
		   	'global_categories'=> $global_categories,
			'system_settings'=>$system_settings,
			'currencies' =>$currencies
	    ]);
    

}




}
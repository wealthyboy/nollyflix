<?php

namespace App\Http\Controllers\Admin\Featured;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DefaultBanner;

class FeaturedController extends Controller
{
    public function add(Request $request,$id){
       $featured  =  DefaultBanner::where(['video_id' => $id])->first();
       if ($featured !== null){
            $featured->delete();
       } else {
            DefaultBanner::create([
                'video_id' => $id, 
            ]);
       }
       return back()->with('success','Featured added.');
    }
}

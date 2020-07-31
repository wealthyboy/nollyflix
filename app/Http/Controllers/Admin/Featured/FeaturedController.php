<?php

namespace App\Http\Controllers\Admin\Featured;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DefaultBanner;

class FeaturedController extends Controller
{
    public function add(Request $request,$id){
       $featured  =  DefaultBanner::first();
       if ($featured !== null){
            $featured->update([
               'video_id' => $id, 
            ]);
       } else {
            DefaultBanner::create([
                'video_id' => $id, 
            ]);
       }
       return back()->with('success','Featured added.');
    }
}

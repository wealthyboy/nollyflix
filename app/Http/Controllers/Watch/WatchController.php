<?php

namespace App\Http\Controllers\Watch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart;
use App\Video;
use App\Order;


class WatchController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except' => [
                'expired'
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Video $video)
    {    
        if ($request->watch === 'free'){
            $video = $video;
        } else {

            $order = Order::where('video_id',$video->id)->firstOrFail();

            // if ($order->purchase_type == 'Rent') {

            // }


            // if ( $video->created_at->isFuture() ){
            //     return redirect()->route('watch.expired',['id' => $request->id]);
            // }

            $video = $order->video;
        }

        $title = "You are watching " .$video->title;
        return view('watch.index',compact('video','title'));
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expired(Video $video)
    {
        $video = Cart::where('video_id',$video->id)->firstOrFail();
        $video = $video->video;
        return view('watch.video_expired',compact('video'));
    }

   


}

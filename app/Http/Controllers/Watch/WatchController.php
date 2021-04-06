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

            $order->video_rent_expires = '2020-10-27 15:55:08';
            $order->save();


            if ($order->cart->purchase_type == 'Rent'  &&  !$order->video_rent_expires->isFuture()) {
                return redirect()->route('watch.expired',['video' => $video->slug]);
            }

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

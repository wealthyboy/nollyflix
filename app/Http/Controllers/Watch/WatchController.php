<?php

namespace App\Http\Controllers\Watch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart;
use App\Video;
use App\Order;
use App\View;


class WatchController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Video $video,Request $request)
    {   
        dd($video);
        if ($video->access_type != 'is_free') {

            if ($request->user_id) {
                \Auth::loginUsingId($request->user_id);
            }
    
            $this->middleware('auth',['except' => [
                    'expired'
                ]
            ]);
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Video $video)
    {     
        
        if ($video->access_type !== 'is_free') {
            $video = $video;
        } else {
            $order = Order::where(['video_id'=>$video->id , 'user_id' => $request->user_id])->latest()->firstOrFail();
            if ($order->cart->purchase_type === 'Rent' &&  !$order->video_rent_expires->isFuture()) {
                return redirect()->route('watch.expired',['video' => $video->slug]);
            }
            $video = $order->video;
        }

        $this->viwed($video);
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


    protected function viwed($video)
    {
        /**
         * Check if user has already viewed the video
         */
        $user  = auth()->user();

        $view = View::where([
            'user_id' => 1,
            'video_id' => $video->id,
        ])->first();

        /**
         * Create view if user has not viewed the video 
         */
        if (!$view){
            $view = new View;
            $view->user_id = 1;
            $view->video_id = $video->id;
            $view->save();
        }
    }


    public function log(Request $request){
       \Log::info($request->dom);
    }


   


}

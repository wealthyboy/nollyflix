<?php

namespace App\Http\Controllers\Browse;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Section;
use App\DefaultBanner;
use App\Video;
use App\User;
use App\Http\Helper;
use App\Live;
use Illuminate\Support\Str;
use App\Order;
use App\WatchProgress;


class BrowseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   

        //dd(     \Schema::getColumnListing("orders")    );
        
        $site_status =Live::first();
        $sections = Section::whereHas('videos', function ($query) {
                $query->active();
            })
            ->with(['videos' => function ($query) {
                $query->active();
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        $featured_videos = DefaultBanner::whereHas('video', function ($query) {
                $query->active();
            })
            ->with(['video' => function ($query) {
                $query->active();
            }])
            ->orderBy('id', 'DESC')
            ->get();

        $continueWatching = collect();

        if (auth()->check()) {
            $progressRows = WatchProgress::with(['video.episodes', 'episode'])
                ->where('user_id', auth()->id())
                ->where('completed', false)
                ->whereNotNull('last_watched_at')
                ->orderBy('last_watched_at', 'desc')
                ->limit(20)
                ->get();

            if ($progressRows->count()) {
                $orders = Order::with('cart')
                    ->where('user_id', auth()->id())
                    ->whereIn('video_id', $progressRows->pluck('video_id')->unique()->values())
                    ->orderBy('id', 'desc')
                    ->get()
                    ->groupBy('video_id');

                $continueWatching = $progressRows->filter(function ($progress) use ($orders) {
                    if (!$progress->video || $progress->video->isBlockedInCurrentRegion()) {
                        return false;
                    }

                    $videoOrders = $orders->get($progress->video_id, collect());

                    return $videoOrders->contains(function ($order) {
                        $purchaseType = strtolower((string) (optional($order->cart)->purchase_type ?: $order->purchase_type));

                        if ($purchaseType === 'buy') {
                            return true;
                        }

                        return $purchaseType === 'rent'
                            && $order->video_rent_expires
                            && $order->video_rent_expires->isFuture();
                    });
                })->take(12)->values();
            }
        }

        $page_title = "Welcome to NollyFlix";
        $page_meta_description = "Buy nollywood movies, african movies, rent movies, rent nollywood movies";


        if ( empty($site_status->make_live) ) {
            return view('browse.index',compact('sections','featured_videos','continueWatching','page_title','page_meta_description')); 
        } else {
            //Show site if admin is logged in
            if ( auth()->check()  && auth()->user()->isAdmin()){
                return view('browse.index',compact('sections','featured_videos','continueWatching','page_title','page_meta_description')); 
            }
            return view('welcome');
        }
         
    }


    public function show(Video $video,User $user)
    {   
        $video->load('episodes', 'related_videos.video');
        $video->setRelation('related_videos', $video->related_videos->filter(function ($related) {
            return (bool) $related->video && (bool) $related->video->is_active;
        })->values());

        $page_title = "Buy ,Rent , {$video->title}";
        $page_meta_description = "Buy nollywood movies, $video->description";
        $blocked = $video->isBlockedInCurrentRegion();
        return view('browse.show',compact('blocked','video','page_title','user','page_meta_description'));   
    }
    
}

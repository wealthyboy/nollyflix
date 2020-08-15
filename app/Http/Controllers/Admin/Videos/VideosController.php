<?php 


namespace App\Http\Controllers\Admin\Videos;


use App\User;
use App\Image;
use App\Video;
use App\Activity;
use App\Category;
use App\Section;

use App\Http\Helper;
use App\SystemSetting;
use App\RelatedVideo;
use App\Genre;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;


class VideosController extends Controller
{
    
    protected $settings;

    public function __construct()
    {	  
	  $this->settings =  SystemSetting::first();
    }

    /**
     * Display a listing of the resource.
     *
     * return \Illuminate\Http\Response
     */
    public function index()
    {   
        $videos = Video::orderBy('created_at','desc')->paginate(30);
        $system_settings =  SystemSetting::first();
        return view('admin.videos.index',compact('system_settings','videos'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * return \Illuminate\Http\Response
     */
    public function create()
    {
        User::canTakeAction(2);
        $categories = Category::parents()->get();
        $genres  = Genre::latest()->get();
        $sections = Section::latest()->get();

        $filmers = (new User())->filmers()->latest()->get();
        $casts   = (new User())->castings()->latest()->get();
        return view('admin.videos.create',compact('genres','sections','filmers','casts','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * param  \Illuminate\Http\Request  $request
     * return \Illuminate\Http\Response
     */
    public function store(Request $request,Video $video)
    {   

        //
        $this->validate($request,[
            'buy_price'  =>  'required',
            'rent_price' =>  'required',
            "cast_id"    =>  "required|array",
            "genre_id"    =>  "required|array",
            "filmer_id"  =>  "required|array",
            'poster'     =>  'required',
            'tn_poster'  =>  'required',
            'title'=>[
                'required',
                    Rule::unique('videos')->where(function ($query) use ($request) {
                        $query->where('deleted_at','=',null);
                    }) 
            ],
        ]);

        $video->title           = $request->title;
        $video->slug            = str_slug($request->title);
        $video->preview_link    = $request->preview_link;
        $video->duration        = $request->duration;
        $video->poster          = $request->poster;
        $video->tn_poster       = $request->tn_poster;
        $video->duration        = $request->duration;
        $video->buy_price       = $request->buy_price;
        $video->rent_price      = $request->rent_price;
        $video->film_rating     = $request->film_rating;
        $video->description     = $request->description;
        $video->resolution      = $request->resolution;
        $video->release_date        =  Helper::getFormatedDate($request->release_date);//Format data
        $video->link                =  $request->link;
        $video->iframe              =  $request->iframe;
        $video->featured            =  $request->featured_video ? 1 : 0;
        $video->allow_restriction   =  $request->allow_restriction ? 1 : 0;
        $video->allow_rent          =  $request->allow_rent ? 1 : 0;
        $video->allow_buy           =  $request->allow_buy ? 1 : 0;
        $video->save();

        if(!empty($request->category_id)){
            $video->categories()->sync($request->category_id);
            $categories = Category::find($request->category_id);
            foreach ($categories as $category) {
                $category->sections()->sync($request->section_id);
            }
        }

        if(!empty($request->cast_id)){
            $video->users()->sync($request->cast_id);
        }

        if(!empty($request->section_id)){
            $video->sections()->sync($request->section_id);
        }

        if(!empty($request->genre_id)){
            $video->genres()->sync($request->genre_id);
        }

        if(!empty($request->filmer_id)){
            $video->users()->sync($request->filmer_id);
        }

        if(!empty($request->related_videos)){
            foreach ($request->related_videos as $key => $video_id) {
                $video->related_videos()->create([
                    'related_id' =>  $video_id,
                    'sort_order' =>$request->sort_order[$key],
                ]);
            }
        }

        (new Activity)->Log("Created a new video {$request->title}");
        return redirect()->route('videos.index')->with('success','Video  added');
    }

    public function destroyRelatedVideo(Request $request,$id)
    {
        RelatedVideo::destroy( $id );
        return response('done',200);
    }

    public function search(Request $request){
        $filtered_array = $request->only(['q', 'field']);
		if (empty( $filtered_array['q'] ) )  { 
			return redirect('/errors');
        }
		if($request->has('q')){
			$filtered_array = array_filter($filtered_array);
			$query = Video::select('videos.*')->
						with('categories')->
						join('category_video', function($join) { 
							$join->on('category_video.video_id','=','videos.id');
						})->
						join('categories', function($join) { 
							$join->on('category_video.category_id','=','categories.id');
						})
						->where(function ($query) use ($filtered_array) {
							$query->where('categories.name','like','%' .$filtered_array['q'] . '%')
								->orWhere('videos.title', 'like', '%' .$filtered_array['q'] . '%');

                        });
        }
			
        $videos = $query->groupBy('videos.id')->get();
        return view('admin.videos.index',compact('videos'));  
    }

    /**
     * Display the specified resource.
     *
     * param  \App\Video  $video
     * return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product=Video::find($id);
        //$product_images = $product->product_images->slice(1);
        return view('admin.videos.show',compact('product'));
    }


    public function getRelatedVideos(Request $request){
        if ($request->filled('title')){
            $videos = Video::where('title', 'like', '%' . $request->title . '%')
            ->take(10)
            ->get();
            return view('admin.videos.related_videos',compact('videos'));  
        }
        return [];
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * param  \App\Product  $product
     * return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        User::canTakeAction(3);
        $categories = Category::parents()->get();
        $genres     = Genre::latest()->get();
        $sections   = Section::latest()->get();
        $filmers    = (new User())->filmers()->latest()->get();
        $casts      = (new User())->castings()->latest()->get();
        $video      = Video::find($id);
        return view('admin.videos.edit',compact('genres','filmers','sections','casts','categories','video'));
    }

    /**
     * Update the specified resource in storage.
     *
     * param  \Illuminate\Http\Request  $request
     * param  \App\Product  $product
     * return \Illuminate\Http\Response
     */
    public function update(Request $request,$id) { 
           //
        $this->validate($request,[
            'buy_price'  =>  'required',
            'rent_price' =>  'required',
            "cast_id"    =>  "required|array",
            "genre_id"    =>  "required|array",
            "filmer_id"  =>  "required|array",
            'poster'     =>  'required',
            'tn_poster'  =>  'required',
            'title' =>  'required|unique:videos,title,' . $id,
        ]);

        $video = Video::find($id);

        $video->title           = $request->title;
        $video->slug            = str_slug($request->title);
        $video->preview_link    = $request->preview_link;
        $video->duration        = $request->duration;
        $video->poster          = $request->poster;
        $video->tn_poster       = $request->tn_poster;
        $video->duration        = $request->duration;
        $video->buy_price       = $request->buy_price;
        $video->rent_price      = $request->rent_price;
        $video->film_rating     = $request->film_rating;
        $video->description     = $request->description;
        $video->resolution      = $request->resolution;
        $video->release_date        =  Helper::getFormatedDate($request->release_date);//Format data
        $video->link                =  $request->link;
        $video->iframe              =  $request->iframe;
        $video->featured            =  $request->featured_video ? 1 : 0;
        $video->allow_restriction   =  $request->allow_restriction ? 1 : 0;
        $video->allow_rent          =  $request->allow_rent ? 1 : 0;
        $video->allow_buy           =  $request->allow_buy ? 1 : 0;
        $video->save();

        if(!empty($request->category_id)){
            $video->categories()->sync($request->category_id);
        }

        if(!empty($request->cast_id)){
            $video->users()->syncWithoutDetaching($request->cast_id);
        }

        if(!empty($request->section_id)){
            $video->sections()->sync($request->section_id);
        }

        if(!empty($request->genre_id)){
            $video->genres()->sync($request->genre_id);
        }

        if(!empty($request->filmer_id)){
            $video->users()->syncWithoutDetaching($request->filmer_id);
        }

    
        if(!empty($request->related_videos)){
            foreach ($request->related_videos as $key => $video_id) {
                $video->related_videos()->updateOrCreate(
                    [
                        'id' =>  $key
                    ],
                    [
                    'related_id' =>  $video_id,
                    'sort_order' =>  $request->sort_order[$key],
                    ]
                );
            }
        }


        (new Activity)->Log("Edited a new video {$request->title}");
        return redirect()->route('videos.index')->with('success','Video  Updated');
    }



    /**
     * Remove the specified resource from storage.
     *
     * param  \App\Product  $product
     * return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        User::canTakeAction(5);
        $rules = array (
            '_token' => 'required' 
        );
        $validator = \Validator::make ( $request->all (), $rules );
        if (empty ( $request->selected )) {
            $validator->getMessageBag ()->add ( 'Selected', 'Nothing to Delete' );
            return \Redirect::back ()->withErrors ( $validator )->withInput ();
        }
        $count = count($request->selected);
        $delete = Video::destroy($request->selected);
        (new Activity)->Log("Deleted  {$count} Videos");
        return redirect()->back()->with('success',"Deleted  {$count} Videos");
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helper;
use App\Traits\FormatPrice;
use Carbon\Carbon;
use App\Traits\ColumnFillable;
use Stevebauman\Location\Location;




class Video extends Model
{
    
    use FormatPrice, ColumnFillable;//,SoftDeletes,CascadeSoftDeletes;

    public $appends = [
		'url',
		'currency',
        'converted_buy_price',
        'converted_rent_price',
        'iso_code',
        'year_release'
    ];
    
    protected $dates = [
        'release_date',
    ];

    /**
     * The casts that belong to the user.
    */
    public function sections()
    {
        return $this->belongsToMany('App\Section','section_video');
    }

    /**
     * The categories that belong to the user.
    */
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    /**
     * The video in Cart.
    */
    public function cart()
    {   
        return $this->hasOne(Cart::class);
    }

    /**
     * The video in Cart.
    */
    public function isVideoRentExpired()
    {   
        return optional($this->cart)->purchase_type == 'rent' && 
        optional($this->cart)->created_at <= Carbon::now()->addDays(2) ? true : false;
    }


    /**
     * The sold that belong to the user.
    */
    public function solds()
    {   
        return $this->hasMAny('App\Cart')->where([
            'purchase_type' => 'buy',
            'status' => 'Complete',
            'content_owner_id' => optional(auth()->user())->id
        ]);
    }


     /**
     * The sold that belong to the user.
    */
    public function rents()
    {
        return $this->hasMAny('App\Cart')->where([
            'purchase_type' => 'rent',
            'status' => 'Complete',
            'content_owner_id' => optional(auth()->user())->id
        ]);
    }


    /**
     * The filmers that belong to the user.
    */
    public function users()
    {
        return $this->belongsToMany('App\User','user_video');
    }


     /**
     * The filmers that belong to the user.
    */
    public function filmers()
    {
        return $this->belongsToMany('App\User','filmer_video','video_id','user_id');
    }


    /**
     * The casts that belong to the user.
    */
    public function casts()
    {
        return $this->belongsToMany('App\User','cast_video','video_id','user_id');
    }

    /**
     * The filmers that belong to the user.
    */
    public function default_banner()
    {
        return $this->hasOne(DefaultBanner::class);
    }

    /**
     * The filmers that belong to the user.
    */
    public function genres()
    {
        return $this->belongsToMany('App\Genre');
    }


    public function getUrlAttribute()
	{
		$link  = '/watch/';
		$link .= $this->slug;
		return $link;
	}

    /**
     * The videos bought that belong to the video.
    */
    public function movies()
    {
        return $this->hasMany('App\OrderedMovie');
    }


     /**
     * The videos bought that belong to the video.
    */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }


    /**
     * The video's views that belong to the video.
    */
    public function views()
    {
        return $this->hasMany('App\View');
    }


    /**
     * The video's views that belong to the video.
    */
    public function view()
    {
        return $this->hasOne('App\View');
    }


    public function related_videos()
    {
        return $this->hasMany(RelatedVideo::class);
    }
    
    public function getReversedDate(){
        return Helper::getFormatBack($this->release_date); 
    }


    public function allGenres(){
        $genres = ''; $x= 1; 
        foreach( $this->genres as $index =>  $genre ) {
            $genres .="<a href='/browse/genre/$genre->slug'>$genre->name</a>" ;
            if($x < count($this->genres)){
            $genres .= ' | ';
            $x++;
            }
        }
        return $genres;
    }


    public function watchType(){

        if ($this->is_free){
           return '<a href=""></a>';
        }

        if ($this->is_for_rent_and_buy ){
            return '<a href=""></a>';
        }

        if ( $this->is_only_for_rent ){
            return '<a href=""></a>';
        }

        if ( $this->is_only_for_buy ){
            return '<a href=""></a>';
        }

        return $genres;
    }


    public function allActors(){
        $casts = ''; $x= 1; 
        foreach( $this->casts as $index =>  $cast ) {
            $casts .="<a href='/$cast->username'>$cast->name</a>" ;
            if($x < count($this->casts)){
               $casts .= ' | ';
               $x++;
            }
        }
        return $this->casts;
    }

   

    public function getRouteKeyName(){
        return 'slug';
    }


    public function getYearReleaseAttribute() {
        return null !== $this->release_date ?  $this->release_date->format('Y') : null;
    }


     public static function excludes() {
        return $map = [
            'AF' => 'Africa',
            'EU' => 'Europe',
            'AS' => 'Asia',
            'NA' => 'North America',
            'SA' => 'South America',
            'OC' => 'Oceania',
            'AN' => 'Antarctica',
        ];
    }

    public static function countryToContinent()
    {
        return json_decode(file_get_contents(storage_path('app/continents.json')), true);
    }
    

    public static function detectContinentCode()
    {
        $position = (new Location())->get(request()->ip());

        if ($position && $position->countryCode) {

            $map = self::countryToContinent();

            return $map[$position->countryCode];
        }

        return null;
    }
}

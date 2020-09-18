<?php

namespace App;

use App\Follower;
use Illuminate\Support\Str;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Check;
use App\Http\Helper;
use App\Cart;


class User extends Authenticatable
{
    use Notifiable,Check;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'name','last_name', 'email','phone_number','password','permission','type'
	];

	public $appends = [
		'currency',
		'cart_total',
		'iso_code',
		'profile_picture'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	protected $casts = [
        'email_verified_at' => 'datetime',
    ];


	public function activities(){
		return $this->hasMany('App\Activity');	
	}

	public function hasSocialLinked($service)
	{
		return (bool) $this->social->where('service', $service)->count();
	}
		
	public function fullname() 
	{ 
		return ucfirst($this->name) . ' '. ucfirst($this->last_name);
	}

	public function users_permission()
	{
		return $this->hasOne("App\UserPermission");
	}
	
	public function scopeCustomers(Builder $builder)
	{
		return $builder->where('type','subscriber');
	}

	public function profile_videos()
    {   
		if ($this->type == 'casts'){
			return $this->belongsToMany('App\Video','cast_video');
		} 
        return $this->belongsToMany('App\Video','filmer_video');
	}
	

	public function cast_videos()
    {
        return $this->belongsToMany('App\Video','cast_video');
    }

	public function filmer_videos()
    {
        return $this->belongsToMany('App\Video','filmer_video');
	}
	
	

	public function scopeCastings(Builder $builder)
	{
		return $builder->where('type','casts');
	}

	/**
     * Get the banner's image.
     */
    public function cast_banner()
    {
        return $this->morphOne('App\Banner', 'banner');
	}


	/**
     * Get all of the carts for the user.
    */
    public function carts()
    {    
		$cookie=\Cookie::get('cart'); 
        return $this->hasMany('App\Cart')->where('remember_token',$cookie);
	}
	

	/**
     * Get all of the movies.
     */
    public function movies()
    {
        return $this->hasMany('App\Cart')->where('status','Complete')->orderBy('id','DESC');
    }
	
	/**
     * Get the banner's image.
     */
    public function filmer_banner()
    {
        return $this->morphOne('App\Banner', 'banner');
    }

	public function scopeFilmers(Builder $builder)
	{
		return $builder->where('type','filmers');
	}

	public function scopeAdmin(Builder $builder)
	{
		return $builder->where('type','admin');
	}


	 /**
     * The user's views that belong to the video.
    */
    public function views()
    {
        return $this->hasMany('App\View');
	}
	


    /**
     * The user's views that belong to the video.
    */
    public function view()
    {
        return $this->hasOne('App\View');
    }



	public function getCartTotalAttribute()
	{   
		return Cart::sum_items_in_cart();
	}

	public function getCurrencyAttribute()
	{
	    return Helper::getCurrency();   
	}

	public function getIsoCodeAttribute()
	{
	    return Helper::getIsoCode();   
	}

	public static function userHasPermission($num)
	{
		$model = \Auth::user();
		return Str::contains(optional(optional($model->users_permission)->permission)->code, $num) ? true : false;
	}

	public static function canTakeAction($num)
	{
		if(!User::userHasPermission($num)){
			dd('You dont have access,Permission Denied.'); 	
		}
	}

	public function isAdmin()
	{
		return $this->users_permission  !== null ? true : false;
	}


	 /**
     * The user's views that belong to the video.
    */
    public function hasWatchedVideo()
    {
        //return null !== $this->view ? 
	}
	

	 /**
     * The user's views that belong to the video.
    */
    public function getProfilePictureAttribute()
    {
        return $this->m_path();
    }


	public function isSubscriber()
	{
		return $this->type == 'subscriber' ? true : false;
	}

	public static function IsSuperUser()
	{
		$model = \Auth::user();
		return optional(optional($model->users_permission)->permission)->name == 'Super User' ? true : false;
	}

	public function activity(){
		return $this->hasMany('App\Activity');
	}
		
	public function hasVerified() {
		$this->verified=true;
		$this->token = null;
		$this->save(); 
	}

	public function getRouteKeyName(){
        return 'slug';
	}
	

	public function m_path()
	{
		$image = basename($this->image);
		return !empty(trim($image)) ?  asset('images/users/m/'.$image) : asset('images/users/default/default_image.png');
	}	
}

<?php

use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'admin','prefix' => 'admin'], function(){
    Route::get('/','Admin\HomeCtrl@index')->name('admin_home');
    Route::get('/maintainance/mode', 'Live\LiveController@index')->name('maintainance');
    Route::get('live', 'Live\LiveController@activate');
    Route::resource('permissions','Admin\Permission\PermissionsController',['names'=>'permissions']);
    Route::resource('orders','Admin\Orders\OrdersController',['names' => 'admin.orders']);
    Route::get('orders/invoice/{id}','Admin\Orders\OrdersController@invoice')->name('order.invoice');

    Route::resource('banners', 'Admin\Design\BannersController',['names' =>'banners']);
    Route::get('customers',  'Admin\Users\UsersController@customers')->name('customers');
    Route::resource('reviews',  'Admin\Reviews\ReviewsController',['names' => 'reviews']);
    Route::resource('posts',  'Admin\Blog\BlogController',['names' => 'posts']);
    Route::get('post/{post_id}/comments',  'Admin\Comments\CommentsController@comments');
    Route::delete('comments/{comment}',  'Admin\Comments\CommentsController@destroy');

    Route::resource('settings','Admin\Settings\SettingsController',['names' => 'settings']);
    Route::resource('media','Admin\Media\MediaController',['names'=>'media']);
    Route::resource('category','Admin\Category\CategoryController',['names'=>'category']);
    Route::resource('sections','Admin\Section\SectionsController',['names'=>'sections']);

    Route::post('category/delete/image','Admin\Category\CategoryController@undo');

    Route::resource('rates','Admin\CurrencyRates\CurrencyRatesController',['name'=>'rates']);
    Route::get('videos/search','Admin\Videos\VideosController@search')->name('search.videos');

    Route::resource('videos','Admin\Videos\VideosController',['names' => 'videos']);
    Route::resource('activity','Admin\Activity\ActivityController',['names' => 'activity']);
    Route::resource('category/featured','Admin\Activity\ActivityController',['names' => 'category.featured']);

    Route::get('related/videos',     'Admin\Videos\VideosController@getRelatedVideos');
    Route::delete('delete/{id}/related_video',     'Admin\Videos\\VideosController@destroyRelatedVideo');
    Route::post('upload/image','Admin\Image\ImagesController@store');
    Route::post('delete/image','Admin\Image\ImagesController@undo');
    Route::post('upload','Admin\Uploads\UploadsController@store');
    Route::get('delete/upload','Admin\Uploads\UploadsController@destroy');

    /* INFORMATION */
    Route::resource('pages','Information\InformationController',['name' => 'pages']);
    /* INFORMATION */

    Route::get('featured/{video_id}','Admin\Featured\FeaturedController@add')->name('featured');
    Route::post('page/banner','Admin\PageBanner\PageBannerController@store');

    Route::post('logout',  'Auth\LoginController@logout')->name('admin_users_logout');
 
    Route::get('register','Admin\Users\UsersController@create')->name('create_admin_users');
    Route::post('register','Auth\RegisterController@register');

    Route::resource('users',                    'Admin\Users\UsersController',['names'=>'admin.users']);
    Route::resource('customers',                'Admin\Customers\CustomersController',['name'=>'customers']);
    Route::resource('casts',                    'Admin\Casts\CastsController',['name'=>'casts']);
    Route::resource('filmers',                  'Admin\Filmers\FilmersController',['name'=>'filmers']);
    Route::resource('genres',                   'Admin\Genres\GenresController',['name'=>'genres']);

    Route::resource('newsletter',               'Admin\NewsLetter\NewsLetterController',['name'=>'newsletter']);
    Route::resource('lists',                    'Admin\EmailLists\EmailListsController',['name'=>'lists']);
    Route::get('lists/emails/create/{id}',      'Admin\NewsLetter\NewsLetterController@create');
    Route::post('lists/emails/create/{id}',     'Admin\NewsLetter\NewsLetterController@store');
    Route::resource('campaigns',                'Admin\Campaign\CampaignController',['name'=>'campaigns']);
    Route::resource('templates',                'Admin\Templates\TemplatesController',['name'=>'templates']);

});


Route::get('/', 'HomeController@index')->name('home');

Route::group(['middleware' => 'currencyByIp'], function(){
    Route::resource('browse',          'Browse\BrowseController',['name'=>'browse']);
    Route::get('profile/videos',       'ProfileVideo\ProfileVideoController@index')->name('profiles.videos');
    Route::get('profile/watchlists',   'ProfileWatchList\ProfileWatchListController@index')->name('profiles.watchlists');
    Route::get('watch/{id}',           'Watch\WatchController@index')->name('watch');
    Route::resource('orders',          'Orders\OrdersController',['name'=>'orders']);
    Route::post('carts',               'Cart\CartController@store');
    Route::get('carts',                'Cart\CartController@index');
    Route::get('cart/delete/{id}',     'Cart\CartController@destroy');
    Route::get('thankyou',             'Thankyou\ThankYouCtrl@index');



    Route::get('checkout',              'Checkout\CheckoutController@index');
    Route::post('checkout',             'Checkout\CheckoutController@store');
    Route::resource('profile',         'Profile\ProfileController',['name'=>'profile']);
    Route::post('change/password',     'Profile\ProfileController@changePassword');
    Route::get('{user}',                'Profile\ProfileController@ActorsAndFilMakers')->name('user.profiles');
});


// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();


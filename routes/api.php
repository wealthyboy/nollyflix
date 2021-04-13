<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('browse',          'Api\Browse\BrowseController@index');
Route::get('featured_videos', 'Api\Browse\BrowseController@featuredVideos');
Route::get('browse/casts',    'Api\Casts\CastsController@index');
Route::get('browse/filmers',  'Api\FilmMakers\FilmMakersController@index');
Route::get('profile/videos',  'Api\ProfileWatchList\WatchListController@index');






Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'Api\Auth\RegisterController@action');
    Route::post('login', 'Api\Auth\LoginController@action');
    Route::get('me', 'Api\Auth\MeController@action');
});


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('browse',          'Api\Browse\BrowseController@index');
Route::get('featured_videos', 'Api\Browse\BrowseController@featuredVideos');
Route::get('browse/casts',    'Api\Casts\CastsController@index');
Route::get('browse/filmers',  'Api\FilmMakers\FilmMakersController@index');
Route::get('profile/videos',  'Api\WatchList\WatchListController@index');
Route::get('search',          'Api\Search\SearchController@search');

Route::get('forgot/password',  'Api\Auth\ResetPasswordController@forgot');
Route::get('reset/password',  'Api\Auth\ResetPasswordController@reset');



Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'Api\Auth\RegisterController@action');
    Route::post('login', 'Api\Auth\LoginController@action');
    Route::get('me', 'Api\Auth\MeController@action');
    Route::post('notifications', 'Api\Notifications\NotificationsController@store');
});


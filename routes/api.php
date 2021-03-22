<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('browse', 'Api\Browse\BrowseController@index');
Route::get('featured_videos', 'Api\Browse\BrowseController@featuredVideos');



Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'Api\Auth\RegisterController@action');
    Route::post('login', 'Api\Auth\LoginController@action');
    Route::get('me', 'Api\Auth\MeController@action');
});


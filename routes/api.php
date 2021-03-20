<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('browse', 'Api\Browse\BrowseController@index');
Route::get('featured_videos', 'Api\Browse\BrowseController@featuredVideos');


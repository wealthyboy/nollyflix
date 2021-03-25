<?php

namespace App\Http\Controllers\Api\FilmMakers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\FilmMakersResource;

class FilmMakersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  User::where('type','filmakers')->orderBy('name','ASC')->get();
        return FilmMakersResource::collection(
            $users->load('filmer_videos')
        );
    }


}

<?php

namespace App\Http\Controllers\Api\Casts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\CastsResource;


class CastsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $users =  User::where('type','casts')->orderBy('name','ASC')->get();
        return CastsResource::collection(
            $users->load('cast_videos')
        );
    }


}

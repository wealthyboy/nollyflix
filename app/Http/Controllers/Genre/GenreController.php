<?php

namespace App\Http\Controllers\Genre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Genre;


class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function  index(Request $request,Genre $genre)  
    {   
        $genre->setRelation('videos', $genre->videos()->visibleInCurrentRegion()->get());

        $page_title = "Buy ,Rent Movies, {$genre->name}";
        $page_meta_description = "Buy nollywood movies, $genre->name";
        return view('genre.index',compact('genre','page_title','page_meta_description'));   
    }

    

    

   
}

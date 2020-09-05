<?php

namespace App\Http\Controllers\Affliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AffliatesController extends Controller
{   

    public $types = [
       'casts',
       'filmakers'
    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {   
        if ( !in_array($type, $this->types) ){
            abort(404);
            return;
        }

        $description = "";

        if (  $type == 'casts' ){
            $cast_title = 'Actors and Actresses';
            $description = "We have a selection of your favorite movie actors/actresses.";
        } else {
            $cast_title = 'Producers and Directors';
            $description = "We have a selection of your favorite movie producers.";
        }

        $users =  User::where('type',$type)->orderBy('name','ASC')->get();
        return view('affiliates.index',compact('users','cast_title','description'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


}

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

        $users =  User::where('type',$type)->orderBy('name','ASC')->get();
        return view('affiliates.index',compact('users'));
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

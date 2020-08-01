<?php

namespace App\Http\Controllers\Admin\Genres;

use Illuminate\Http\Request;
use App\Genre;
use App\Http\Controllers\Controller;
use App\Activity;
use App\Http\Helper;
use App\User;
use Illuminate\Validation\Rule;




class GenresController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genres = Genre::latest()->get();
        return view('admin.genres.index',compact('genres'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        User::canTakeAction(2);
        return view('admin.genres.create');
    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
   
    public function store(Request $request)
    {   
               
        $this->validate($request,[
            'name'=> 'required|max:255|unique:genres',
        ]);

        $genres = new Genre;
        $genres->name = $request->name;
        $genres->slug=str_slug($request->name);
        $genres->sort_order=$request->sort_order;
        $genres->save();
        (new Activity)->Log("Created a new genres called {$request->name}");
        return redirect()->back()->with('success','Genre created');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Genre  $genres
     * @return \Illuminate\Http\Response
     */
    public function show(Genre $genres)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Genre  $genres
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        User::canTakeAction(4);
        $gen = Genre::find($id);
        return view('admin.genres.edit',compact('gen'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $rest
     * @param  \App\Genre  $genres
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $genres = Genre::find($id);
        $this->validate($request,[
            'name'=>[
                'required',
                    Rule::unique('genres')->ignore($id)
                ],
        ]);
       
        $genres->name=$request->name;
        $genres->sort_order=$request->sort_order;
        $genres->slug=str_slug($request->name);
        $genres->save();
        //Log Activity
        (new Activity)->Log("Updated  Genre {$request->name} ");
        return redirect()->route('genres.index')->with('success','Genre Updated');
    }


  

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Genre  $genres
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        //
        User::canTakeAction(5);

        $rules = array (
                '_token' => 'required' 
        );
        $validator = \Validator::make ( $request->all (), $rules );
        if (empty ( $request->selected )) {
            $validator->getMessageBag ()->add ( 'Selected', 'Nothing to Delete' );
            return \Redirect::back ()->withErrors ( $validator )->withInput ();
        }
        $count = count($request->selected);
        (new Activity)->Log("Deleted  {$count} Products");
        try {
            Genre::destroy( $request->selected );
        } catch (\Throwable $th) {
            //throw $th;
        }
        return redirect()->back();
        if ($request->isMethod ( 'get' )) {
            $genres =  Genre::find( $request->id );
            (new Activity)->Log("Deleted  {$genres->name} Categories");
            $genres->delete();
            return redirect()->back()->with('success','Deleted');
        }
        
    }
}

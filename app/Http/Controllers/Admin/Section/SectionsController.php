<?php

namespace App\Http\Controllers\Admin\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Section;
use App\Activity;
use App\User;
use Illuminate\Validation\Rule;



class SectionsController extends Controller
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
        $sections = Section::latest()->get();
        return view('admin.sections.index',compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        User::canTakeAction(2);
        return view('admin.sections.create');
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
            'name'=> 'required|max:255|unique:sections',
        ]);

        $section = new Section;
        $section->name = $request->name;
        $section->slug=str_slug($request->name);
        $section->sort_order=$request->sort_order;
        $section->save();
        (new Activity)->Log("Created a new sections called {$request->name}");
        return redirect()->back()->with('success','Section created');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Type  $sections
     * @return \Illuminate\Http\Response
     */
    public function show(Type $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Type  $sections
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        User::canTakeAction(4);
        $section = Section::find($id);
        return view('admin.sections.edit',compact('section'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $rest
     * @param  \App\Type  $sections
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $section = Section::find($id);
        $this->validate($request,[
            'name'=>[
                'required',
                    Rule::unique('sections')->ignore($id)
                ],
        ]);
       
        $section->name=$request->name;
        $section->sort_order=$request->sort_order;
        $section->slug=str_slug($request->name);
        $section->save();
        //Log Activity
        (new Activity)->Log("Updated  Type {$request->name} ");
        return redirect()->route('sections.index')->with('success','Section Updated');
    }


  

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $sections
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
            Section::destroy( $request->selected );
        } catch (\Throwable $th) {
            //throw $th;
        }
        return redirect()->back()->with('success','Section deleted');
       
        
    }
}

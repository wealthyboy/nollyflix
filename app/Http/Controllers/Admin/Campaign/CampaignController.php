<?php

namespace App\Http\Controllers\Admin\Campaign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Campaign;
use App\EmailList;
use App\Template;
use App\Mail\NewsLetterEmail;



class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns =  Campaign::latest()->get();
        return view('admin.campaign.index',compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $email_lists =  EmailList::latest()->get();
        $templates =  Template::latest()->get();
        return view('admin.campaign.create',compact('email_lists','templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campaign = new Campaign;
        $campaign->name              =  $request->name;
		$campaign->email_list_id     =  $request->email_list_id;
        $campaign->subject           =  $request->subject;
        $campaign->template_id       =  $request->template_id;
		$campaign->status            =  'Processing';
		$campaign->save();
        $this->sendMail($request);
        return redirect()->route('campaigns.index');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $email_lists =  EmailList::latest()->get();
        $templates   =  Template::latest()->get();
        $campaign    =  Campaign::find($id);
        return view('admin.campaign.edit',compact('campaign','email_lists','templates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        $campaign->name              =  $request->name;
		$campaign->email_list_id     =  $request->email_list_id;
        $campaign->subject           =  $request->subject;
        $campaign->template_id       =  $request->template_id;
		$campaign->status            =  'Processing';
		$campaign->save();
        return redirect()->route('campaigns.index');
    }


    public function resendMail(Type $var = null)
    {
        # code...
    }


    public function sendMail(Request $request)
    {
        try {
            //$when = now()->addMinutes(5);
            $email_list =  EmailList::find($request->email_list_id);
            $template   =  Template::find($request->template_id);
            foreach ($email_list->news_letters as $news_letter) {
                \Mail::to($news_letter->email)
			   ->queue(new NewsLetterEmail($template,$request->subject));
            }
		} catch (\Throwable $th) {
			//throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {

        $rules = array (
                '_token' => 'required' 
        );
        $validator = \Validator::make ( $request->all(), $rules );
        if (empty ( $request->selected )) {
            $validator->getMessageBag ()->add ( 'Selected', 'Nothing to Delete' );
            return \Redirect::back ()->withErrors ( $validator )->withInput ();
        }
        $count = count($request->selected);
        //(new Activity)->Log("Deleted  {$count} Products");
        try {
            Campaign::destroy( $request->selected );
        } catch (\Throwable $th) {
            //throw $th;
        }
        return redirect()->back();
    }
}



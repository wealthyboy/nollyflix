<?php

namespace App\Http\Controllers\Api\Comments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;
use App\Video;
use  App\Http\Resources\CommentsResource;
use Illuminate\Notifications\Notification;
use App\Notifications\CommentNotification;


class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Video $video)
    {   
        $comments =  $video->comments()->orderBy('created_at','DESC')->paginate(3);
        return CommentsResource::collection( $comments );
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Comment $comment)
    {
        //
        $user = $request->user();

        
        $new_review =  $comment->create([
            'user_id' => $user->id,
            'video_id' => $request->video_id,
            'description' => $request->description,
        ]);

        //new Review Notification
        $video = Video::find($request->video_id);

        $comments =  $video->comments()->orderBy('created_at','DESC')->paginate(5);
        $comment = CommentsResource::collection( $comments );

        $new_review = [];
        $new_review['video_title'] = $video->title;
        $new_review['full_name'] = $user->name . ' '. $user->last_name;
        $new_review['description'] = $request->description;
        $new_review['email'] = $user->email;
        try {
            \Notification::route('mail', 'jacob.atam@gmail.com')
            ->notify(new CommentsNotification($new_review));
        } catch (\Throwable $th) {
            //throw $th;
        }
       
        return $comment;
    }
    
}

<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $order = new Order;
        $order->user_id  =  auth()->user()->id;
        $order->total    =  $request->price;
        $order->total    =  $request->price;
        $order->currency    =  $request->price;
        $order->total    =  $request->price;
        $order->payment_type    =  $request->price;
        $order->save();

        $order->ordered_movie()->create([
           'video_id'=> $request->id,
        ]);

        
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

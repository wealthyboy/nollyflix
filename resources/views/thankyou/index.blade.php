@extends('layouts.checkout')
 
@section('content')



<div class="background-image">
    <div class="container-fluid">
        <div class="signup--middle">                    
            <h1 class="">Thank you for buying from  us </h1>
            <p class="large">Your order has been received .</p>
            <a href="{{ route('profiles.watchlists') }}" class="btn btn--gray space-t--2">Go to your watch lists</a>
        </div>
    </div>
</div>
@endsection
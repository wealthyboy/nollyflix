@extends('layouts.checkout')
 
@section('content')



<div class="background-image">
    <div class="container-fluid">
        <div class="signup--middle">                    
            <h1 class="">hank you for buying from  us </h1>
            <p class="large">Your order has been received .</p>

            <a href="{{ route('profiles.watchlists') }}" class="btn btn--gray space-t--2">Go to watch lists</a>
        </div>
    </div>
</div>
@endsection
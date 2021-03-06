@extends('layouts.access')

@section('content')

<div class="h-100 d-none searching">
    <div class="">                    
        <p class="large">Searching.....</p>
    </div>
</div>
<section class="section-content">


<div id="content-pro">
    <div class="container-fluid custom-gutters-pro">
        <div style="height:15px;"></div>
        @if($users->count())
        
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="text-center profile-section-images-title">
                        <h2 class="">{{ $cast_title }}</h2>
                        <p>{{ $description }}</p>
                    </div>
                </div>
                @foreach($users as $user)
                    <div class=" col-6 col-lg-2 mb-5">
                        <div class="card card-profile text-center card-plain">
                            <div class="card-avatar">
                                <a href="/{{ $user->username }}">
                                    <img class="img" src="{{ $user->m_path() }}">
                                </a>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">{{ $user->fullname() }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col-12 text-center">
                    <h2>No one found.</h2>
                </div>
            </div>
        @endif            
    
        <div class="clearfix"></div>
        
        </div>
    </div><!-- close .container --> 
</div><!-- close #content-pro -->
</section>


@include('includes.search')
@endsection

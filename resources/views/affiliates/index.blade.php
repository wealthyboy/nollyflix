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
         

            <div class="row profile-section-images">
                <div class="col-6 col-lg-12">
                    <div class="text-center">
                        <h2 class="">CASTS</h2>
                        <p>Select from our </p>
                    </div>
                </div>
                @foreach($users as $user)
                    <div class=" col-6 col-lg-2">
                        <div class="card card-profile text-center card-plain">
                            <div class="card-avatar">
                                <a href="/{{ $user->username }}">
                                    <img class="img" src="{{ $user->m_path() }}">
                                </a>
                            </div>

                            <div class="card-body">
                                <h4 class="card-title">{{ $user->fullname() }}</h4>
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

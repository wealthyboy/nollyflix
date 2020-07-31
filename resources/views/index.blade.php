@extends('layouts.app')

@section('content')
<section class="">
    <div class="background-image">
        <div class="container-fluid">
            <div class="signup--middle">                    
                <h1 class="">BUY ,RENT AND WATCH ON MULTIPLE DEVICES  NOLLYWOOD MOVIES </h1>
                <a class="btn btn-green-pro btn-slider-pro" href="{{  route('register') }}"><i class="fas fa-plus"></i> GET STARTED</a>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <div class="row">
        <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
            <img src="/images/demo/home-1.jpg" class="img-fluid" alt="Watch in Any Devices">
        </div>
        <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
            <h2 class="short-border-bottom">Watch On Any Device</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipiscing nula pellentesquemagna a convallis nula facilisi. Praesent consequat eget elementumconsectetur. Nullam interdum, quam ac sagittis facilisis sapien dolor ipsum consequat ellit tristique senectus</p>
            <div style="height:15px;"></div>
            <p><a class="btn btn-green-pro" href="signup-step1.html" role="button">Learn More</a></p>
        </div>
    </div><!-- close .row -->
    
    
    <div class="row">
        <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
            <h2 class="short-border-bottom">Make Your Own Playlist</h2>
            <p>Curabitur at lobortis leo varius tellus. Phasellus id purus placeratfinibus diam a, feugiat massa. Donec porta orci lectus, ut lacinia risus fringilla nulla facilisi suspendisse eget id justo ac magna finibus dignissim. Integer purus feugiat gravida convalis,</p>
            <div style="height:15px;"></div>
            <p><a class="btn btn-green-pro" href="signup-step1.html" role="button">Start Watching</a></p>
        </div>
        <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
            <img src="/images/demo/home-2.jpg" class="img-fluid" alt="Make Your Own Playlist">
        </div>
    </div><!-- close .row -->
    
    
    <div class="row">
        <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
            <img src="/images/demo/home-3.jpg" class="img-fluid" alt="Watch in Ultra HD">
        </div>
        <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
            <h2 class="short-border-bottom">Watch in Ultra HD</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipiscing nula pellentesquemagna a convallis nula facilisi. Praesent consequat eget elementumconsectetur. Nullam interdum, quam ac sagittis facilisis sapien dolor ipsum consequat ellit tristique senectus</p>
            <div style="height:15px;"></div>
            <p><a class="btn btn-green-pro" href="signup-step1.html" role="button">Start Your Free Trial</a></p>
        </div>
    </div><!-- close .row -->
    
    <div style="height:35px;"></div>
    
    <div class="clearfix"></div>
</div><!-- close .container -->
			
@endsection

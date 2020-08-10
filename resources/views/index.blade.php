@extends('layouts.app')

@section('content')
<div class="background-image">
    <div class="container-fluid">
        <div class="signup--middle">                    
            <h1 class="">BUY ,RENT AND WATCH ON MULTIPLE DEVICES  NOLLYWOOD MOVIES </h1>
            <a class="btn btn-green-pro btn-slider-pro" href="{{  route('register') }}"><i class="fas fa-plus"></i> GET STARTED</a>
        </div>
    </div>
</div>
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

    <div class="row">

    <div id="accordion">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Collapsible Group Item #1
                    </button>
                </h5>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Collapsible Group Item #2
                </button>
            </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingThree">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Collapsible Group Item #3
                </button>
            </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                </div>
            </div>
        </div>
        </div>
    </div>
</div><!-- close .container -->
			
@endsection

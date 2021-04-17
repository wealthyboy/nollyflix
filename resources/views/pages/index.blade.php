@extends('layouts.access')
        
@section('content')

@include('includes.searching')
<section class="section-content">  
    <div class="container mt-3">
        <div class="row justifiy-content-center">        
          <div id="content" class="col-md-12  mb-5">
            <p><?php echo  html_entity_decode($information->description);  ?></p> 
          </div>
        <div class="margin-top-35"></div>
      </div> <!-- /row -->
    </div> <!-- /container -->
</section>
@include('includes.search')

@endsection

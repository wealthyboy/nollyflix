@extends('layouts.access')

@section('page-css')
<link rel="stylesheet" href="/css/trailer.css">
@stop

@section('content')
@include('includes.searching')

<section class="section-content">
 @include('partials.video_show')
</section>

@include('includes.search')
		
@endsection

@section('page-scripts')
<script src="/js/trailer.js?version={{ str_random(6) }}"></script><!---->
@stop

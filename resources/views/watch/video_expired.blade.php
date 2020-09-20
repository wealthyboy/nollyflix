@extends('layouts.profile')
@section('page-css')
<link rel="stylesheet" href="/css/trailer.css">
@stop

@section('content')
@include('partials.video_show')
@endsection

@section('page-scripts')
<script src="/js/trailer.js?version={{ str_random(6) }}"></script><!---->
@stop

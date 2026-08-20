@extends('layouts.access')
@section('page-css')
<link rel="stylesheet" href="/css/trailer.css?version={{ filemtime(public_path('css/trailer.css')) }}">
@stop

@section('content')
@include('partials.video_show')
@endsection

@section('page-scripts')
<script src="/js/trailer.js?version={{ filemtime(public_path('js/trailer.js')) }}"></script>
@stop

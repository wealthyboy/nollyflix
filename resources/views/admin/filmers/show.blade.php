
@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="text-right">
            <a href="{{ route('casts.index') }}" rel="tooltip" title="back" class="btn btn-primary btn-simple btn-xs">
                <i class="material-icons">refresh</i>
                Back
            </a>
           
        </div>
    </div>
   
   <div class="col-md-12">
    <div class="card">
      
        <div class="card-header">
            <h4 class="card-title">Details For {{ $filmer->fullname() }}</h4>
        </div>
        <div class="card-content">
            <ul class="nav nav-pills nav-pills-warning">
                <li class="active"><a href="panels.html#pill1" data-toggle="tab">General</a></li>
                <li class=""><a href="panels.html#videos" data-toggle="tab">Videos</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="pill1">
                    <div class="col-md-4 col-sm-12">
                        <legend>Profile picture</legend>
                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                                <img src="{{ $filmer->image }}" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <div>
                                
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <div class="table-responsive">
                            <table class="table">
                            <tbody>
                                    <tr>
                                        <td colspan="4"><b>Full Name</b></td>
                                        <td class="text-right">{{  $filmer->fullname() }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><b>Bio</b></td>
                                        <td class="text-right">{{ $filmer->description }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><b>Email</b></td>
                                        <td class="text-right">{{ $filmer->email }}</td>
                                    </tr>
                                        <td colspan="4"><b>Added</b></td>
                                        <td class="text-right">{{ $filmer->created_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    
                    </div>
                </div>
                <div class="tab-pane" id="videos">
                   <div class="row">
 
                    @if($filmer->filmer_videos->count())
                        @foreach($filmer->filmer_videos as $video)
                        <div class="col-md-4">
                                <div class="card card-product" data-count="4">
                                    <div class="card-image" data-header-animation="false">
                                        <a href="#">
                                            <img class="img" src="{{ $video->tn_poster }}">
                                        </a>
                                    </div>

                                    <div class="card-content">
                                       

                                        <h4 class="card-title">
                                            <a href="#">{{  $video->title }}</a>
                                        </h4>
                                        
                                    </div>
                                    <div class="card-footer">
                                        <div class="price">
                                            <h4>6k views</h4>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        No videos yet for {{ $filmer->fullname() }}
                    @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@section('inline-scripts')
$(document).ready(function() {

});
@stop







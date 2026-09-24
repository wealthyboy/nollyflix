@extends('admin.layouts.app')

@section('content')

<div class="row">
        <div class="col-md-12">
            <div class="text-right">
                <a href="{{ route('videos.index') }}" rel="tooltip" title="Refresh" class="btn btn-primary btn-simple btn-xs">
                    <i class="material-icons">refresh</i>
                    Refresh
                </a>
                <a href="{{ route('videos.create') }}" rel="tooltip" title="Add New" class="btn btn-primary btn-simple btn-xs">
                    <i class="material-icons">add</i>
                    Add Video
                </a>
                <a href="{{ route('sections.index') }}" rel="tooltip" title="Arrange homepage section movies" class="btn btn-primary btn-simple btn-xs">
                    <i class="material-icons">drag_indicator</i>
                    Arrange Homepage Movies
                </a>
                <a href="javascript:void(0)" onclick="confirm('Are you sure?') ? $('#form-videos').submit() : false;" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                    <i class="material-icons">close</i>
                    Remove
                </a>

            </div>                                                 
                            
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="rose">
                    <i class="material-icons">Filter</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Filter - <small class="category"></small></h4>
                    
                    <form action="{{ route('search.videos') }}" method="get">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group label-floating ">
                                    <label class="control-label">Search </label>
                                    <input required  type="text" value="{{  old('product_name') }}" name="q" class="form-control" >
                                </div>
                            </div>
                            
                        </div>
                        <input name="search" type="submit" value="search" class="btn btn-rose  btn-round pull-right">
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
      
        <div class="col-md-12">
        @include('includes.success')

            <div class="card">
        
                <div class="card-content">
                    <h4 class="card-title">Videos</h4>
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <div class="material-datatables">
                        @if($videos->count())
                        <form  action="{{ route('videos.destroy',['video' => 1]) }}" method="post" enctype="multipart/form-data" id="form-videos">
                            @method('DELETE')
                            @csrf
                            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">

                                <thead>

                                    <tr>
                                    <th>
                                        <div class="checkbox">
                                            <label>
                                                <input onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" type="checkbox" name="optionsCheckboxes" >
                                            </label>
                                        </div>
                                        </th>
                                        <th>Poster</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Buy Price</th>
                                        <th>Rent Price</th>
                                        <th>Status</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($videos as $video) 
                                    <tr>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="{{ $video->id }}" name="selected[]" >
                                                </label>
                                            </div>
                                        </td>
                                        <!-- cart-active -->
                                        <!-- cart-sidebar-btn active -->
                                        <td>
                                            <div class="img-container">
                                                <img class="" src="{{ $video->tn_poster }}" alt="...">
                                            </div>
                                        </td>
                                        <td><a target="_blank" href="/video/{{ isset($video->categories[0]) ?  $video->categories[0]->slug : '' }}/{{ $video->slug }}">{{ $video->title }}</a></td>
                                        <td>
                                            @if(($video->content_type ?? 'movie') == 'series')
                                                Series ({{ $video->episodes_count }} episodes)
                                            @else
                                                Movie
                                            @endif
                                        </td>
                                        <td>
                                            <span class="amount">
                                            {{ $system_settings->default_currency->symbol }}{{ $video->buy_price }}
                                            </span> 
                                        </td>
                                        <td>
                                            <span class="amount">
                                            {{ $system_settings->default_currency->symbol }}{{ $video->rent_price }}
                                            </span> 
                                        </td>
                                        <td>
                                            <div class="togglebutton" style="margin:0;">
                                                <label style="display:flex; align-items:center; gap:8px; margin:0;">
                                                    <input
                                                        type="checkbox"
                                                        class="js-video-active-toggle"
                                                        data-url="{{ route('videos.toggle-active', ['video' => $video]) }}"
                                                        {{ $video->is_active ? 'checked' : '' }}
                                                    >
                                                    <span class="js-video-active-label" style="font-weight:600; color:{{ $video->is_active ? '#2e7d32' : '#c62828' }};">
                                                        {{ $video->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="td-actions text-right">                     
                                            @if(!empty($video->link) || $video->episodes_count > 0)
                                            <a href="{{ route('videos.watch', ['video' => $video]) }}" target="_blank" rel="tooltip" title="Watch movie" class="btn btn-success btn-simple btn-xs">
                                                <i class="material-icons">play_arrow</i>
                                                Watch
                                            </a>
                                            @else
                                            <span rel="tooltip" title="No playable video has been uploaded" class="btn btn-default btn-simple btn-xs disabled" style="pointer-events:none; opacity:.5;">
                                                <i class="material-icons">play_arrow</i>
                                                Watch
                                            </span>
                                            @endif
                                            <a href="{{ route('videos.edit',['video'=>$video->id] ) }}" rel="tooltip" title="Edit" class="btn btn-primary btn-simple btn-xs">
                                                <i class="material-icons">edit</i>
                                                Edit
                                            </a>
                                            <a href="{{ route('featured',['video_id'=>$video->id] ) }}" rel="tooltip" title="Featured" class="btn btn-primary btn-simple btn-xs">
                                                <i class="material-icons">add</i>
                                                {{  optional($video->default_banner)->count() ? "Featured" : "Make default" }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach  
                                </tbody>
                            </table>
                        </form>

                        @else
                           <div ><h1> No videos found</h1></div>
                        @endif
                    </div>
                </div><!-- end content-->
            </div><!--  end card  -->
        </div> <!-- end col-md-12 -->
    </div> <!-- end row -->

@endsection
@section('page-scripts')
  <script src="/assets/js/jquery.datatables.js"></script>
@stop
@section('inline-scripts')
$(document).ready(function() {
    $('#datatables').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        language: {
        search: "_INPUT_",
        searchPlaceholder: "Search records",
        }

    });
    $('.card .material-datatables label').addClass('form-group');

    $(document).on('change', '.js-video-active-toggle', function() {
        var $toggle = $(this);
        var wasChecked = !$toggle.prop('checked');
        var $label = $toggle.closest('label').find('.js-video-active-label');

        $toggle.prop('disabled', true);

        $.ajax({
            url: $toggle.data('url'),
            type: 'POST',
            success: function(response) {
                $toggle.prop('checked', !!response.is_active);
                $label
                    .text(response.label)
                    .css('color', response.is_active ? '#2e7d32' : '#c62828');
            },
            error: function() {
                $toggle.prop('checked', wasChecked);
                alert('Unable to update the video status. Please try again.');
            },
            complete: function() {
                $toggle.prop('disabled', false);
            }
        });
    });
});
@stop








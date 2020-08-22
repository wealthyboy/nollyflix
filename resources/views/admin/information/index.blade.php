@extends('admin.layouts.app')
@section('pagespecificstyles')
<!-- include summernote css/js -->
@stop
@section('content')

<style>
  .categories .categories {
     margin-left: 20px;
  }
  .checkbox, .radio {
    margin-top: 0px;
    margin-bottom: 0px;
  }
</style>

<div class="row">
    <div class="col-md-8">
        @include('errors.errors')
        <div class="card">
            <div class="card-content">
                <h4 class="card-title">Add Page</h4>
                <div class="material">
                    <form id="" action="{{ route('pages.store') }}" method="post">
                        @csrf
                        
                        <div class="row">
                          
                            <div class="col-md-12">
                                    <div class="form-group label-floating is-empty">
                                    <label class="control-label">Name</label>
                                    <input  required="true" name="name" data-msg="" class="form-control" type="text">
                                    <span class="material-input"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group label-floating is-empty">
                                    <label class="control-label">Sort Order</label>
                                    <input name="sort_order" class="form-control" type="number">
                                    <span class="material-input"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group label-floating is-empty">
                                    <label class="control-label">Custom Link</label>
                                    <input   name="custom_link"  class="form-control" type="text">
                                    <span class="material-input"> Please use full url https://ohram.org/</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label class="control-label"></label>
                                        <select name="same_page" class="form-control">
                                            <option  value="">--Same Page--</option>
                                            <option class="" value="yes" >Yes</option>
                                            <option class="" value="no" >No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label class="control-label"></label>
                                        <select name="parent_id" class="form-control">
                                        <option  value="">--Choose One--</option>
                                            @foreach($pages as $page)
                                                @if($page->isParent())
                                                    <option class="" value="{{ $page->id }}" >{{ $page->title }} </option>
                                                    @foreach($page->children as $page)
                                                        <option class="" value="{{ $page->id }}" >&nbsp;&nbsp;&nbsp;{{ $page->title }} </option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <div class="form-group ">
                                            <label class="control-label"> </label>
                                            <textarea name="description" 
                                            id="description" class="form-control"  rows="20"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-footer text-right">
                                <button type="submit" class="btn btn-rose btn-round  btn-fill">Submit</button>
                            </div>
                    </form>
                </div>
            </div><!-- end content-->
        </div><!--  end card  -->
    </div> <!-- end col-md-6 -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-content">
                <h4 class="card-title">Pages</h4>
                     <div class="text-right">
                        <a href="javascript:void(0)" onclick="confirm('Are you sure?') ? $('#form--pages').submit() : false;" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                            <i class="material-icons">close</i> Delete
                        </a>
                    </div>
                    <div class="material-datatables">
                        <form action="{{ route('pages.destroy',['page'=>1]) }}" method="post" enctype="multipart/form-data" id="form--pages">
                            @csrf
                            @method('DELETE')
                            @foreach($pages as $page)
                                <div class="categories">
                                    @if($page->isParent())
                                        <div class="checkbox">
                                            <label>
                                                <input 
                                                type="checkbox" 
                                                value="{{ $page->id }}" 
                                                name="selected[]" >
                                                {{ $page->name }}  
                                                <a  href="{{ route('pages.edit',['page'=>$page->id]) }}" 
                                                    rel="tooltip" title="Edit" 
                                                    class="btn btn-primary btn-simple btn-xs">	
                                                    <i class="material-icons">edit</i> Edit
                                                </a>
                                            </label>
                                        </div> 
                                        @foreach($page->children as $page)
                                        <div class="checkbox categories">
                                            <label>
                                                <input type="checkbox" 
                                                value="{{ $page->id }}" 
                                                name="selected[]" >
                                                {{ $page->title }}  
                                                <a  href="{{ route('pages.edit',['page'=>$page->id]) }}" 
                                                    rel="tooltip" title="Edit" 
                                                    class="btn btn-primary btn-simple btn-xs">	
                                                    <i class="material-icons">edit</i> Edit
                                                </a>
                                            </label>
                                        </div> 
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </form> 
                    </div>
                </div><!-- end content-->
            </div><!--  end card  -->
        </div> <!-- end col-md-6 -->
    </div> <!-- end row -->




@endsection

@section('page-scripts')
<script src="/ckeditor/ckeditor.js"></script>
@stop

@section('inline-scripts')
$(document).ready(function() {
    CKEDITOR.replace('description',{
        height: '400px'
    })    

    let activateFileExplorer = 'a.activate-file';
    let delete_image = 'a.delete_image';
    var main_file = $("input#file_upload_input");
    Img.initUploadImage({
        url:'/admin/upload/image?folder=banners',
        activator: activateFileExplorer,
        inputFile: main_file,
    });
    Img.deleteImage({
        url:'/admin/delete/image?folder=banners&model=Information',
        activator: delete_image,
        inputFile: main_file,

    });
});
@stop






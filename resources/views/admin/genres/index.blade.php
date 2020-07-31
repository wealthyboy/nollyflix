@extends('admin.layouts.app')

@section('content')


<div class="row">
    <div class="col-md-6">
    @include('includes.success')

        @include('errors.errors')
        <div class="card">
            <div class="card-content">
                <h4 class="card-title">Add Genres</h4>
                <div class="material-datatables">
                <form action="{{ route('genres.store') }}" method="post" enctype="multipart/form-data" id="form-genre">
                    @csrf
                        <div class="form-group label-floating">
                            <label class="control-label">
                                Name
                                <small>*</small>
                            </label>
                            <input class="form-control"
                                name="name"
                                type="text"
                                required="true"
                            />
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">
                                Sort Order
                            </label>
                            <input class="form-control"
                                name="sort_order"
                                type="number"  
                            />
                        </div>
                        <div class="form-group hide label-floating">
                            <label class="control-label">
                                Image link
                            </label>
                            <input class="form-control"
                                name="image_custom_link"
                                type="text"  
                            />
                        </div>
                     
                        <h4 class="info-text hide">Upload Image Here</h4>
                        <div class="hide">
                            <div id="m_image"  class="uploadloaded_image text-center mb-3">
                                <div class="upload-text"> 
                                        <a class="activate-file" href="#">
                                        <img src="{{ asset('store/img/upload_icon.png') }}">
                                        <b>Add Image </b> 
                                        </a>
                                </div>
                                <div id="remove_image" class="remove_image hide">
                                    <a class="delete_image"  href="#">Remove</a>
                                </div>

                                <input accept="image/*"  class="upload_input" data-msg="Upload  your image" type="file" id="file_upload_input" name="category_image"  />
                                <input type="hidden"  class="file_upload_input  stored_image" id="stored_image" name="image">
                            </div>
                        </div>
                       
                        <div class="form-footer text-right">
                            <button type="submit" class="btn btn-rose btn-round btn-group  btn-fill">Submit</button>
                        </div>
                    </form>
                </div>
            </div><!-- end content-->
        </div><!--  end card  -->
    </div> <!-- end col-md-6 -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-content">
                <h4 class="card-title">Genres</h4>
                    <div  class="checkbox col-md-6 text-left">
                        <label>
                            <input onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" type="checkbox" name="optionsCheckboxes" > Select All
                        </label>
                    </div>
                    <div  class="col-md-6 text-right">
                        <a href="javascript:void(0)" onclick="confirm('Are you sure?') ? $('#form-genres').submit() : false;" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                            <i class="material-icons">close</i> Delete
                        </a>
                    </div> 
                    <div class="clearfix"></div> 

                    <form action="{{ route('genres.destroy',['genre'=>1]) }}" method="post" enctype="multipart/form-data" id="form-genres">
                    @csrf
                    @method('DELETE')
                    <div class="material-datatables">
                        @foreach($genres as $genre)
                            <div class="parent" value="{{ $genre->id }}">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="{{ $genre->id }}" name="selected[]" >
                                    {{ $genre->name }}  
                                    <a href="{{ route('genres.edit',['genre'=>$genre->id]) }}">
                                    <i class="fa fa-pencil"></i> Edit</a> 
                                </label>
                            </div>   
                        </div>
                        @endforeach  
                    </div>
                </form>
            </div><!-- end content-->
        </div><!--  end card  -->
    </div> <!-- end col-md-6 -->
</div> <!-- end row -->
@endsection

@section('inline-scripts')

$(document).ready(function() {
    let activateFileExplorer = 'a.activate-file';
    let delete_image = 'a.delete_image';
    var main_file = $("input#file_upload_input");
    Img.initUploadImage({
        url:'/admin/upload/image?folder=genre',
        activator: activateFileExplorer,
        inputFile: main_file,
    });
    Img.deleteImage({
        url:'/admin/genre/delete/image',
        activator: delete_image,
        inputFile: main_file,
    });
});

@stop






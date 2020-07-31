
@extends('admin.layouts.app')

@section('content')

   <div class="row">
   <form  action="{{ route('genres.update',['genre' => $gen->id ]) }}" method="post">
       
        <div class="col-md-8">
            @include('errors.errors')
            <div class="card">
                   @csrf
                   @method('PATCH')
                    <div class="card-content">
                        <h4 class="card-title">Edit Category</h4>
                        <div class="form-group label-floating">
                            <label class="control-label">
                              Namehide
                                <small>*</small>
                            </label>
                            <input class="form-control"
                                   name="name"
                                   type="text"
                                   value="{{ $gen->name }}"
                                   required="true"
                             />
                        </div>

                        <div class="form-group label-floating">
                            <label class="control-label">
                              Sort Order
                            </label>
                            <input class="form-control"
                                   name="sort_order"
                                   type="text"
                                   value="{{ $gen->sort_order }}"
                             />
                        </div>

                        <div class="form-group hide label-floating">
                            <label class="control-label">
                                Image link
                            </label>
                            <input class="form-control"
                                   name="image_custom_link"
                                   type="text"
                                   value="{{ $gen->image_custom_link }}"      
                            />
                        </div>


                      


                        <h4 class="info-text hide">Upload Image Here</h4>
                            <div class="hide">
                                <div id="m_image"  class="uploadloaded_image text-center mb-3">
                                    <div class="upload-text {{ $gen->image !== null  ?  'hide' : '' }}"> 
                                         
                                            <a class="activate-file" href="#">
                                            <img src="{{ asset('store/img/upload_icon.png') }}">
                                            <b>Add Image </b> 
                                            </a>
                                    </div>
                                    <div id="remove_image" class="remove_image {{ $gen->image !== null  ?  '' : 'hide' }}">
                                        <a class="delete_image" data-id="{{ $gen->id }}" href="#">Remove</a> 
                                    </div>

                                    <input accept="image/*"  class="upload_input" data-msg="Upload  your image" type="file" id="file_upload_input" name="category_image"  />
                                    <input type="hidden"  class="file_upload_input  stored_image" value="{{ $gen->image }}" name="image">
                                    @if ( $gen->image )
                                       <img id="stored_image" class="img-thumnail" src="{{ $gen->image }}" alt="">
                                    @endif
                                    
                                </div>
                            </div>

                            <div class="form-footer text-right">
                                <button type="submit" class="btn btn-rose btn-round  btn-fill">Submit</button>
                            </div>
                    </div>
            </div>
        </div>
        
   
    </form>

</div>
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







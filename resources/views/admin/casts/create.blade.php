
@extends('admin.layouts.app')

@section('content')

   <div class="row">
   <form  enctype="multipart/form-data" action="{{ route('casts.store') }}" method="post">
       
        <div class="col-md-8">
            @include('errors.errors')
            <div class="card">
                   @csrf
                    <div class="card-content">
                        <h4 class="card-title">Add Actor/Actress</h4>
                        <div class="form-group label-floating">
                            <label class="control-label">
                              Name
                                <small>*</small>
                            </label>
                            <input class="form-control"
                                   name="first_name"
                                   type="text"
                                   value="{{ old('name') }}"
                                   required="true"
                             />
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">
                              Last Name
                                <small>*</small>
                            </label>
                            <input class="form-control"
                                   name="last_name"
                                   type="text"
                                   value="{{ old('last_name') }}"
                                   required="true"
                             />
                        </div>

                        <div class="form-group label-floating">
                            <label class="control-label">
                              Username
                                <small>*</small>
                            </label>
                            <input class="form-control"
                                   name="username"
                                   type="text"
                                   value="{{ old('username') }}"
                                   required="true"
                             />
                        </div>

                        <div class="form-group label-floating">
                            <label class="control-label">
                              Email
                                <small>*</small>
                            </label>
                            <input class="form-control"
                                   name="email"
                                   type="email"
                                   value="{{ old('email') }}"
                                   required="true"
                             />
                        </div>

                        <div class="form-group label-floating">
                            <label class="control-label">
                              Bio
                                <small>*</small>
                            </label>
                            <textarea 
                                    required="true"
                                    name="description" 
                                    id="description"
                                    class="form-control" 
                                    rows="15"></textarea>

                        </div>

                        <div class="image-header text-center">
                           <h4 class="info-text bold">Upload Image Here</h4>
                        </div>
                            <div class="">
                                <div id="m_image"  class="uploadloaded_image text-center mb-3">
                                    <div class="upload-text "> 
                                        <a class="activate-file" href="#">
                                        <img src="{{ asset('backend/img/upload_icon.png') }}">
                                        <b>Add Image </b> 
                                        </a>
                                    </div>
                                    <div id="remove_image" class="remove_image  hide">
                                        <a class="delete_image" data-id="" href="#">Remove</a> 
                                    </div>
                                    <input accept="image/*"  class="upload_input" data-msg="Upload  your image" type="file" id="file_upload_input" name="uimage"  />
                                    <input type="hidden"  class="file_upload_input  stored_image" value="" name="image">
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
        url:'/admin/upload/image?folder=users',
        activator: activateFileExplorer,
        inputFile: main_file,
    });

    Img.deleteImage({
        url:'/admin/category/delete/image?folder=users',
        activator: delete_image,
        inputFile: main_file,

    });
});
@stop







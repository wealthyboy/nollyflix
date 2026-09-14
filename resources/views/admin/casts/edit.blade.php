@extends('admin.layouts.app')

@section('content')
<div class="row">
    <form enctype="multipart/form-data" action="{{ route('casts.update', ['cast' => $cast->id]) }}" method="post">
        <div class="col-md-8">
            @include('errors.errors')
            <div class="card">
                @csrf
                @method('PATCH')
                <div class="card-content">
                    <div class="clearfix">
                        <h4 class="card-title pull-left">Edit Actor/Actress</h4>
                        <a href="{{ route('casts.show', ['cast' => $cast->id]) }}" class="btn btn-primary btn-simple btn-xs pull-right">
                            <i class="material-icons">arrow_back</i> Back
                        </a>
                    </div>

                    <div class="form-group label-floating is-focused">
                        <label class="control-label">Name <small>*</small></label>
                        <input class="form-control" name="first_name" type="text" value="{{ old('first_name', $cast->name) }}" required>
                    </div>

                    <div class="form-group label-floating is-focused">
                        <label class="control-label">Last Name</label>
                        <input class="form-control" name="last_name" type="text" value="{{ old('last_name', $cast->last_name) }}">
                    </div>

                    <div class="form-group label-floating is-focused">
                        <label class="control-label">Username <small>*</small></label>
                        <input class="form-control" name="username" type="text" value="{{ old('username', $cast->username) }}" required>
                    </div>

                    <div class="form-group label-floating is-focused">
                        <label class="control-label">Email <small>*</small></label>
                        <input class="form-control" name="email" type="email" value="{{ old('email', $cast->email) }}" required>
                    </div>

                    <div class="form-group label-floating is-focused">
                        <label class="control-label">Bio <small>*</small></label>
                        <textarea required name="description" id="description" class="form-control" rows="10">{{ old('description', $cast->description) }}</textarea>
                    </div>

                    <div class="image-header text-center">
                        <h4 class="info-text bold">Profile Image</h4>
                    </div>
                    <div>
                        <div id="m_image" class="uploadloaded_image text-center mb-3">
                            <div class="upload-text {{ $cast->image ? 'hide' : '' }}">
                                <a class="activate-file" href="#">
                                    <img src="{{ asset('backend/img/upload_icon.png') }}">
                                    <b>Add Image</b>
                                </a>
                            </div>
                            <div id="remove_image" class="remove_image {{ $cast->image ? '' : 'hide' }}">
                                <a class="delete_image" data-id="{{ $cast->id }}" href="#">Remove</a>
                            </div>
                            <input accept="image/*" class="upload_input" data-msg="Upload your image" type="file" id="file_upload_input" name="uimage">
                            <input type="hidden" class="file_upload_input stored_image" value="{{ old('image', $cast->image) }}" name="image">
                            @if($cast->image)
                                <img id="stored_image" class="img-thumnail" src="{{ $cast->image }}" alt="{{ $cast->fullname() }}">
                            @endif
                        </div>
                    </div>

                    <div class="form-footer text-right">
                        <button type="submit" class="btn btn-rose btn-round btn-fill">Update Cast</button>
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
    var main_file = $('input#file_upload_input');

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

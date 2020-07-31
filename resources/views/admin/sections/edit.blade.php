
@extends('admin.layouts.app')

@section('content')

   <div class="row">
   <form  action="{{ route('sections.update',['section' => $section->id ]) }}" method="post">
       
        <div class="col-md-8">
            @include('errors.errors')
            <div class="card">
                   @csrf
                   @method('PATCH')
                    <div class="card-content">
                        <h4 class="card-title">Edit Section</h4>
                        <div class="form-group label-floating">
                            <label class="control-label">
                              Name
                                <small>*</small>
                            </label>
                            <input class="form-control"
                                   name="name"
                                   type="text"
                                   value="{{ $section->name }}"
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
                                   value="{{ $section->sort_order }}"
                             />
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
	
});
@stop







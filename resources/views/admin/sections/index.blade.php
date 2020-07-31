@extends('admin.layouts.app')

@section('content')


<div class="row">
    <div class="col-md-6">
    @include('includes.success')

        @include('errors.errors')
        <div class="card">
            <div class="card-content">
                <h4 class="card-title">Add Sections</h4>
                <div class="material-datatables">
                <form action="{{ route('sections.store') }}" method="post" enctype="multipart/form-data" id="form-section">
                    @csrf
                        <div class="form-group label-floating">
                            <label class="control-label">
                                Name
                                <small>*</small>
                            </label>
                            <input class="form-control"
                                name="name"
                                section="text"
                                required="true"
                            />
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">
                                Sort Order
                            </label>
                            <input class="form-control"
                                name="sort_order"
                                section="number"  
                            />
                        </div>
                       
                
                       
                        <div class="form-footer text-right">
                            <button section="submit" class="btn btn-rose btn-round btn-group  btn-fill">Submit</button>
                        </div>
                    </form>
                </div>
            </div><!-- end content-->
        </div><!--  end card  -->
    </div> <!-- end col-md-6 -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-content">
                <h4 class="card-title">Sections</h4>
                    <div  class="checkbox col-md-6 text-left">
                        <label>
                            <input onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" type="checkbox" name="optionsCheckboxes" > Select All
                        </label>
                    </div>
                    <div  class="col-md-6 text-right">
                        <a href="javascript:void(0)" onclick="confirm('Are you sure?') ? $('#form-sections').submit() : false;" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                            <i class="material-icons">close</i> Delete
                        </a>
                    </div> 
                    <div class="clearfix"></div> 

                    <form action="{{ route('sections.destroy',['section'=>1]) }}" method="post" enctype="multipart/form-data" id="form-sections">
                    @csrf
                    @method('DELETE')
                    <div class="material-datatables">
                        @foreach($sections as $section)
                            <div class="parent" value="{{ $section->id }}">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="{{ $section->id }}" name="selected[]" >
                                    {{ $section->name }}  
                                    <a href="{{ route('sections.edit',['section'=>$section->id]) }}">
                                       <i class="fa fa-pencil"></i> Edit
                                    </a> 
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
    
});

@stop






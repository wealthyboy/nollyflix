@extends('admin.layouts.app')

@section('content')

<div class="row">
   <div class="col-md-12">
   @include('includes.success')

        <div class="card">
            <div class="text-right">
                <a href="{{ route('filmers.index') }}" rel="tooltip" title="Refresh" class="btn btn-primary btn-simple btn-xs">
                    <i class="material-icons">refresh</i>
                    Refresh
                </a>
                <a href="{{ route('filmers.create') }}" rel="tooltip" title="Add New" class="btn btn-primary btn-simple btn-xs">
                    <i class="material-icons">add</i>
                    Add Filmers
                </a>
                <a href="javascript:void(0)" onclick="confirm('Are you sure?') ? $('#form-customers').submit() : false;" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                    <i class="material-icons">close</i>
                    delete
                </a>
            </div>
            <div class="card-content">
                <h4 class="card-title">Casts</h4>
                <div class="toolbar">
                    <!-- Here you can write extra buttons/actions for the toolbar -->
                </div>
                <div class="material-datatables">
                    <form action="{{ route('filmers.destroy',['filmer' => 1]) }}" method="post" enctype="multipart/form-data" id="form-customers">
                       @csrf
 
                        @method('DELETE')
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
                                    <th>Full name</th>
                                    <th>Email</th>
                                    <th>Movies</th>
                                    <th class="disabled-sorting text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filmers as $filmer)

                                    <tr>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="{{ $filmer->id }}" name="selected[]" >
                                                </label>
                                            </div>
                                        </td>
                                        <td><a href="{{ route('filmers.show',['filmer'=>$filmer->id]) }}">{{ $filmer->fullname() }}</a></td>
                                        <td class="text-left">{{ $filmer->email }}</td>
                                        <td class="text-left">{{ $filmer->filmer_videos->count() }}</td>
                                        <td class="text-right">
                                           {{ $filmer->created_at }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                         </table>
                        </form>
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
});
@stop








@if (session('errors'))
    <div class="alert alert-danger alert-dismissible ">
    <button type="button" aria-hidden="true"  data-dismiss="alert" style="margin-right: 20px;" class="close">
                <i class="material-icons">close</i>
            </button> 
        <strong>{{ session('errors') }}</strong>
    </div>
@endif

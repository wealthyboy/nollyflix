@extends('admin.layouts.app')

@section('content')

<div class="row">


        <div class="col-md-12">
        <div class="text-right">
            <a href="{{ route('customers') }}" rel="tooltip" title="Back" class="btn btn-primary btn-simple btn-xs">
                    <i class="material-icons">reply</i>
                </a>
                
            </div>
        </div>

            
            <div class="col-md-12">
               <div class="card">
         
               <div class="card-content">
                     <div class="">
                          <div class="pull-right">Date: {{ $user->created_at }}</div>
                        <br/>
                        Full Name: {{ $user->fullname() }}<br/>
 
                        Email : {{ $user->email }}<br/>
                        Phone: {{ $user->phone_number }}<br/>
                        Email: {{ $user->email }}<br/><br/>

                        Address : {{ $user->address->address  }}<br/>
                        City : {{ $user->address->city  }}<br/>
                        State : {{ $user->address->state->name  }}<br/>


                           
                            <br/>

                        <table class="table shopping-cart-table-total">
                       
                       <tr>
                       </tr>
                     </table>
                        
                        </div>
                        </div>

                     </div>

    </div> <!-- end row -->




@endsection
@section('inline-scripts')
@stop








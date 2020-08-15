@extends('admin.layouts.app')
@section('content')
<div class="row">
   <div class="col-md-12">
      <div class="text-right">
        
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-content">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Order Details</h3>
               </div>
               <table class="table">
                  <tbody>
                     <tr>
                        <td style="width: 1%;"><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="Store"><i class="fa fa-shopping-cart fa-fw"></i></button></td>
                        <td><a href="" target="_blank">{{  Config('app.name') }}</a></td>
                     </tr>
                     <tr>
                        <td><button data-toggle="tooltip" title="Date Added" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
                        <td>{{ $order->created_at }}</td>
                     </tr>
                     <tr>
                        <td><button data-toggle="tooltip" title="Payment Method" class="btn btn-info btn-xs"><i class="fa fa-credit-card fa-fw"></i></button></td>
                        <td>{{ $order->payment_type }}</td>
                     </tr>
                    
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-content">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-user"></i> Customer Details</h3>
               </div>
               <table class="table">
                  <tbody>
                     <tr>
                        <td style="width: 1%;"><button data-toggle="tooltip" title="Customer" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
                        <td> <a href="" target="_blank">{{ $order->user->fullname() }}</a></td>
                     </tr>
                     <tr>
                        <td><button data-toggle="tooltip" title="E-Mail" class="btn btn-info btn-xs"><i class="fa fa-envelope-o fa-fw"></i></button></td>
                        <td><a href="">{{ $order->user->email }}</a></td>
                     </tr>
                     <tr>
                        <td><button data-toggle="tooltip" title="Telephone" class="btn btn-info btn-xs"><i class="fa fa-phone fa-fw"></i></button></td>
                        <td>{{ $order->user->phone_number }}</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-content">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-cog"></i> Options</h3>
               </div>
               <table class="table">
                  <tbody>
                     <tr>
                        <td>Invoice</td>
                        <td id="invoice" class="text-right">{{ $order->invoice  }}</td>
                        <td style="width: 1%;" class="text-center"><button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-refresh"></i></button>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-12">
      <div class="card">
         <div class="card-header card-header-icon" data-background-color="rose">
            <i class="material-icons">assignment</i>
         </div>
         <div class="card-content">
            
               <h2>Items</h2>
               <table class="table table-shopping">
                  <thead>
                     <tr>
                        <th class="text-center"></th>
                        <th>Video</th>
                        <th class="th-description">Title</th>
                        <th class="text-right">Purchase Type</th>

                        <th class="text-right">Price</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Amount</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach ( $order->ordered_movies as $order_movie )
                     <tr>
                        <td>
                           <div class="img-container">
                              <img src="{{ optional($order_movie->video)->tn_poster  }} " alt="...">
                           </div>
                          
                        </td>
                        <td class="td-name">
                           <a href="">{{  optional(optional($order_movie)->video)->title }}</a>
                           <br><small></small>
                        </td>
                        <td>
                           <a href="">{{  $order_movie->purchase_type }}</a>


                        </td>
                      
                        <td class="td-number text-right">
                           {{  $order->currency }}{{  $order_movie->order_price   }}
                        </td>
                        <td class="td-number">
                           {{ $order_movie->quantity }}
                        </td>
                        <td class="td-number">
                           <small>{{  $order->currency }}</small>{{ $order_movie->total   }}
                        </td>
                        
                     </tr>
                     @endforeach                               
                  </tbody>
               </table>
               <table class="table ">
                  <tfoot>
                     <tr>
                        <td colspan="6" class="text-right">Sub-Total</td>
                        <td class="text-right"><small>{{ $order->currency }}</small>{{ number_format($sub_total)  }}</td>
                     </tr>
                   
                     
                     <tr>
                        <td colspan="6" class="text-right">Total</td>
                        <td class="text-right">{{ $order->currency }}{{  $order->get_total() }}</td>
                     </tr>
                  </tfoot>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- end row -->
@endsection
@section('inline-scripts')


@stop


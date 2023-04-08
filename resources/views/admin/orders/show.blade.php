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
                        <td><a href="" target="_blank">{{ Config('app.name') }}</a></td>
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
                     <th>Poster</th>
                     <th class="th-description">Title</th>
                     <th class="text-right">Purchase Type</th>
                     <th class="text-right">Price</th>
                     <th class="text-right">Qty</th>
                     <th class="text-right">Amount</th>
                  </tr>
               </thead>
               <tbody>

                  <?php $cart = $order->cart  ?>
                  <tr>
                     <td>
                        <div class="img-container">
                           <img src="{{ optional($cart->video)->tn_poster  }} " alt="...">
                        </div>
                        <div class="form-group label-floating">
                           <input type="hidden" class="cart_id" value="{{ $cart->id }}" />
                           <select class="form-control mt-3 update_status" name="order_status[{{ $cart->id }}]" id="">
                              <option value="">Choose Status</option>
                              @foreach($statuses as $status)
                              @if ($status == $cart->status)
                              <option value="{{ $status }}" selected>{{ $status }}</option>
                              @else
                              <option value="{{ $status }}">{{ $status }}</option>
                              @endif
                              @endforeach
                           </select>
                        </div>
                     </td>
                     <td class="td-name">
                        <a href="">{{ optional(optional($cart)->video)->title }}</a>
                        <br><small></small>
                     </td>
                     <td>
                        {{ $cart->purchase_type }}
                     </td>
                     <td class="td-number text-right">
                        {{ $order->currency }}{{ $cart->customer_price }}
                     </td>
                     <td class="td-number">
                        {{ $cart->quantity }}
                     </td>
                     <td class="td-number">
                        <small>{{ $order->currency }}</small>{{ $cart->customer_total }}
                     </td>

                  </tr>
               </tbody>
            </table>
            <table class="table ">
               <tfoot>
                  <tr>
                     <td colspan="6" class="text-right">Sub-Total</td>
                     <td class="text-right"><small>{{ $order->currency }}</small>{{ $order->get_total() }}</td>
                  </tr>

                  <tr>
                     <td colspan="6" class="text-right">Total</td>
                     <td class="text-right">{{ $order->currency }}{{ $order->get_total() }}</td>
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
$(".update_status").on('change',function(e){
let self = $(this)
if(self.val() == '') return;
let value = self.parent().find(".cart_id").val()
console.log(value)
var payLoad = { cart_id: value,status: self.val() }
$.ajax({
type: "PATCH",
url: "/admin/orders/"+value,
data: payLoad,
}).done(function(response){
console.log(response)
})
})
@stop
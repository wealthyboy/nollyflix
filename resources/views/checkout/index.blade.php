@extends('layouts.checkout')

@section('content')

<div id="content-pro">
<section class="pb-4 mt-1">
    <div class="container">
      <div class="row cart-header mt-4 mb-1 pb-1">
       
        <div class="col-lg-12 col-md-12 col-12">
            <h3> Your Cart</h3>
         </div>
      </div>
      <div>
        @if ($carts->count())
            <!----> 
            <div class="row">
                <div class="col-md-8">
                    <article>
                        <form action="/update/cart" method="" class="cart-form">
                            <input type="hidden" value="" name="_token"> 
                            @foreach($carts as $cart)
                            <div class="cart-product-table-wrap ">
                                <div class="row cart-rows raised mb-3 pt-4 pb-4 border border-gray">
                                <div class="col-md-2 col-6">
                                    <div class="cart-image"><img src="{{ $cart->video->tn_poster }}" alt=""></div>
                                </div>
                                <div class="col-md-7 col-6">
                                    <h5><a href="#">{{ $cart->video->title }}</a></h5>
                                    <div class="product--share  mt-3"><span class="bold">Type #:</span> {{ $cart->purchase_type }}
                                    </div>
                                    <div class="product-item-price">
                                        <div class="product-price-amount"><span class="retail-title text-gold">PRICE: </span> <span class="product--price text-gold">{{ $currency }}{{ $cart->converted_price }}</span></div>
                                    </div>
                                    <!---->
                                </div>
                                <div class="col-md-2">
                                    <div class="pt-2 pb-4">
                                        <label class="bold">Qty</label> 
                                        <div id="quantity_1234" class="video-quantity">
                                            1
                                        </div>
                                    </div>
                                    <div><a href="/cart/delete/{{ $cart->id }}" class="text-danger btn-transparent bold"><span class="button-icon"><i class="fas fa-trash-alt"></i></span>
                                        Remove 
                                        </a>
                                    </div>
                                </div>
                                </div>
                            </div>
                            @endforeach
                        </form>
                    </article>
                </div>
                <div class="col-md-4">
                <div class="cart-collateralse  border pb-3 pt-3 pl-3 pt-3 pr-3 raised">
                    <div class="cart_totalse">
                        <h3> Summary </h3>
                        <p><span class="bold">Subtotal</span> <span class="price-amount amount bold pull-right"><span class="currencySymbol">{{ $currency }}{{ $cart::sum_items_in_cart() }}</span></span></p>
                        <hr>
                        <p><span class="bold">Total</span> <span class="price-amount amount bold pull-right"><span class="currencySymbol">{{ $currency }}{{ $cart::sum_items_in_cart() }}</span></span></p>
                        <div class="proceed-to-checkout"><a  data-user="{{ auth()->user() }}" data-currency="" data-total="" href="#" class="checkout-button btn btn--lg btn--primary bold full-width display-2">Make Payment</a></div>
                    </div>
                </div>
                </div>
            </div>
            <!---->
        @else
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <div class="error-page text-center">
                        <h1>YOUR CART IS EMPTY</h1>
                        <a href="/" class="btn btn--gray space-t--2">Back to home</a>
                    </div>
                </div>

            </div>
        @endif


      </div>
    </div>
</section>

</div><!-- close #content-pro -->
@endsection
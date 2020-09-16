<template>
    <div v-if="loggedIn" class="container mt-5 rounded ">
        <div class="row no-gutters d-flex justify-content-center">
            <div class="col-md-12">
                <div class="payment-info">
                     <div class="aligncenter">
                        <h1>CHECKOUT</h1>
                    </div>
                    <form action="/checkout" method="POST" id="checkout" class="cart-form">
                        <div class="cart-product-table-wrap ">
                            <div class="row cart-rows raised mb-3 pt-4 pb-4 border border-gray">
                            <div class="col-md-3 col-3">
                                <div class="cart-image"><img :src="$root.video.tn_poster" alt=""></div>
                            </div>
                            <div class="col-md-9 col-9">
                                <h5><a href="#">{{ $root.video.title }}</a></h5>
                                <div class="product--share  mt-3"><span class="bold">Type #:</span> {{ purchaseType }} 
                                        <span v-if="purchaseType == 'rent' " class="ml-2 border">Expires after 48hour</span>
                                </div>
                                <div class="product-item-price">
                                    <div class="product-price-amount"><span class="retail-title text-gold">PRICE: </span> <span class="product--price text-gold">{{ $root.video.currency }}{{ price  }}</span></div>
                                </div>
                            </div>
                            
                            </div>
                        </div>
                    </form>
                    <hr class="line">
                    <div class="d-flex justify-content-between information"><span>Total </span><span>{{ $root.video.currency }}{{ price  }}</span></div>
                    <button class="btn btn-primary btn-block d-flex justify-content-center mt-3" @click="submit" type="button"> <span>MAKE PAYMENT<i class="fa fa-long-arrow-right ml-1"></i></span></button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    
    data(){
        return {
            loading:false,
            scriptLoaded: null, 
        }
    },
    computed:{
        ...mapGetters({
            loggedIn: 'loggedIn',
            purchaseType: 'purchaseType',
            showPayemtForm: 'showPayemtForm'
        }),
        price: function () {
           return this.purchaseType == 'Rent' ? this.$root.video.converted_rent_price : this.$root.video.converted_buy_price
        }
        
    },
    created() {
        this.scriptLoaded = new Promise((resolve) => {
            this.loadScript(() => {
                resolve()
            })
        })
    },
 
    methods: {
       
        loadScript(callback) {
            const script = document.createElement('script')
            script.src = 'https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js'
            document.getElementsByTagName('head')[0].appendChild(script)
            if (script.readyState) {  // IE
                script.onreadystatechange = () => {
                    if (script.readyState === 'loaded' || script.readyState === 'complete') {
                        script.onreadystatechange = null
                        callback()
                    }
                }
            } else {  // Others
                script.onload = () => {
                    callback()
                }
            }
        },
        submit: function(){
            this.scriptLoaded && this.scriptLoaded.then(() => {
                var x = getpaidSetup({
                    PBFPubKey: "FLWPUBK_TEST-d8c9813bd0912d597cc6fddacc11e45f-X",
                    customer_email: 'jacob.atam@gmail.com',
                    amount: 4000,
                    currency:'NGN',
                    country: "NG",
                    payment_method: "both",
                    txref: "rave-"+ Math.floor((Math.random() * 1000000000) + 1), 
                    meta: [{
                        metaname: 'sss',
                    }],
                    onclose: function() {
                        //context.loading =false
                       // context.error = "Payment was not completed"
                    },
                   
                });
                
            })
        }
    }

}
</script>
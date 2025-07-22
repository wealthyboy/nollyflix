<template>
    <div v-if="loggedIn" class="container mt-5 rounded pb-3">
        <div class="row no-gutters d-flex justify-content-center">
            <div class="col-md-12">
                <div class="payment-info">
                    <div class="aligncenter">
                        <h1>CHECKOUT</h1>
                    </div>
                    <form
                        action="/checkout"
                        method="POST"
                        id="checkout"
                        class="cart-form"
                    >
                        <input
                            :value="$root.token"
                            type="hidden"
                            name="_token"
                        />

                        <div class="cart-product-table-wrap ">
                            <div
                                v-if="!loading"
                                class="row cart-rows raised mb-3 pt-4 pb-4 border border-gray"
                            >
                                <div class="col-md-3 col-4">
                                    <div class="cart-image">
                                        <img
                                            :src="$root.video.tn_poster"
                                            alt=""
                                        />
                                    </div>
                                </div>
                                <div class="col-md-9 col-8">
                                    <h5>
                                        <a href="#">{{ $root.video.title }}</a>
                                    </h5>
                                    <div class="product--share  mt-3">
                                        <span class="bold">Type #:</span>
                                        {{ purchaseType }}
                                        <span
                                            v-if="purchaseType == 'rent'"
                                            class="ml-2 border"
                                            >Expires after 48hour</span
                                        >
                                    </div>
                                    <div class="product-item-price">
                                        <div class="product-price-amount">
                                            <span class="retail-title text-gold"
                                                >PRICE:
                                            </span>
                                            <span
                                                class="product--price text-gold"
                                                >{{ $root.video.currency
                                                }}{{ price }}</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="loading"
                                class="row justify-content-center text-center"
                            >
                                <div class="text-center col-md-9 col-12">
                                    <span
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>
                                    {{ statusText }}
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="line" />
                    <div v-if="!loading">
                        <div class="d-flex justify-content-between information">
                            <span>Total </span
                            ><span>{{ $root.video.currency }}{{ price }}</span>
                        </div>
                        <button
                            class="btn btn-primary btn-block d-flex justify-content-center mt-3 mt-sm-3 mt-md-2  mb-sm-3 mb-md-3 "
                            @click="submit"
                            type="button"
                        >
                            <span
                                >MAKE PAYMENT<i
                                    class="fa fa-long-arrow-right ml-1"
                                ></i
                            ></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";

export default {
    data() {
        return {
            scriptLoaded: null,
            statusText: ""
        };
    },
    computed: {
        ...mapGetters({
            loggedIn: "loggedIn",
            purchaseType: "purchaseType",
            showPayemtForm: "showPayemtForm",
            user: "user",
            loading: "loading",
            cart_id: "cart_id"
        }),
        price: function() {
            return this.purchaseType == "Rent"
                ? this.$root.video.converted_rent_price
                : this.$root.video.converted_buy_price;
        }
    },
    created() {
        this.scriptLoaded = new Promise(resolve => {
            this.loadScript(() => {
                resolve();
            });
        });
    },

    methods: {
        loadScript(callback) {
            const script = document.createElement("script");
            script.src = "https://checkout.flutterwave.com/v3.js";
            document.getElementsByTagName("head")[0].appendChild(script);
            if (script.readyState) {
                // IE
                script.onreadystatechange = () => {
                    if (
                        script.readyState === "loaded" ||
                        script.readyState === "complete"
                    ) {
                        script.onreadystatechange = null;
                        callback();
                    }
                };
            } else {
                // Others
                script.onload = () => {
                    callback();
                };
            }
        },
        submit: function() {
            var context = this;
            this.$store.commit("setLoading", true);
            this.statusText = "Payment is processing.....";

             axios
                    .post("/checkout", {
                        cart_id: context.cart_id
                    })
                    .then(res => {
                        // location.href =
                        //     "/watch/" +
                        //     context.$root.video.slug;
                    })
                    .catch(error => {
                        alert("Something went wrong");
                        // context.$store.commit(
                        //     "setLoading",
                        //     false
                        // );
                    });


             return;
         
        }
    }
};
</script>

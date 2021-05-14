<template>
    <div class="container mt-5 rounded pb-3">
        <div class="row no-gutters d-flex justify-content-center">
            <div class="col-md-12">
                <div class="payment-info">
                    <div class="aligncenter">
                        <h1>Payment</h1>
                    </div>

                    <hr class="line" />

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

                    <div>
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
    props: ["params"],

    data() {
        return {
            scriptLoaded: null,
            statusText: ""
        };
    },
    computed: {
        ...mapGetters({
            loading: "loading"
        })
    },

    created() {
        this.scriptLoaded = new Promise(resolve => {
            this.loadScript(() => {
                resolve();
            });
        });
    },
    mounted() {
        this.submit();
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
            this.scriptLoaded &&
                this.scriptLoaded.then(() => {
                    var x = FlutterwaveCheckout({
                        public_key:
                            "FLWPUBK_TEST-d8c9813bd0912d597cc6fddacc11e45f-X", //test pbkey FLWPUBK_TEST-d8c9813bd0912d597cc6fddacc11e45f-X,//live  FLWPUBK-3c3bd76ddea8a8bc289651bfd883b970-X
                        customer_email: context.params.email,
                        amount: context.params.price,
                        currency: "NGN",
                        country: "NG",
                        tx_ref:
                            "rave-" +
                            Math.floor(Math.random() * 1000000000 + 1),
                        customer: {
                            phone_number: context.cart_id,
                            email: context.params.email
                        },
                        onclose: function() {
                            // context.$store.commit("setLoading", false);
                        },
                        callback: function(response) {
                            context.$emit("paymentCompleted", "Completed");
                            alert("34");
                            $("#apModal").on("hide.bs.modal", function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                return false;
                            });

                            if (response.status == "successful") {
                                x.close();
                                context.statusText =
                                    "Redirecting you to your vidoe .Enjoy.....";
                                axios
                                    .post("/checkout", {
                                        cart_id: context.params.cart_id
                                    })
                                    .then(res => {
                                        alert("Cool");

                                        location.href =
                                            "/watch/" +
                                            context.$root.video.slug;
                                    })
                                    .catch(error => {
                                        alert("Something went wrong");
                                        context.$store.commit(
                                            "setLoading",
                                            false
                                        );
                                    });
                            } else {
                                x.close();
                                context.$store.commit("setLoading", false);
                            }
                        }
                    });
                });
        }
    }
};
</script>

require("./bootstrap");

require("@fancyapps/fancybox");
require("../../public/js/navigation.js");
require("../../public/js/jquery.flexslider-min.js");
require("../../public/js/owl.carousel.min.js");
require("../../public/js/scripts.js");

window.Vue = require("vue");

import store from "./store";

const Modal = require("./components/checkout/Index.vue").default;
const MobilePayment = require("./components/checkout/MobilePayment.vue")
    .default;

const Buttons = require("./components/buttons/BuyRentButton.vue").default;
const Reviews = require("./components/comments/Index.vue").default;
const ProfilePicture = require("./components/profile/ProfilePicture.vue")
    .default;

let app = new Vue({
    el: "#app",
    store,
    data: Window.user || {},
    components: {
        Modal,
        Buttons,
        Reviews,
        ProfilePicture,
        MobilePayment
    }
});
export default app;

//This code helps  browser like safari that caches
//javascript Back/Forward cache (the cache pulled from when a visitor presses the Back or Forward browser buttons)

window.onpageshow = function(event) {
    if (event.persisted) {
        store.commit("setLoading", false);
        $("#close-modal")
            .removeClass("pointer-events")
            .trigger("click");
    }
};

require('./bootstrap');


window.Vue = require('vue');
import store from './store'

const CheckoutIndex = require('./components/checkout/Index.vue').default
const MobilePayemnt = require('./components/checkout/mobilePayment.vue').default

const Buttons = require('./components/buttons/BuyRentButton.vue').default
const Reviews = require('./components/comments/Index.vue').default
const ProfilePicture = require('./components/profile/ProfilePicture.vue').default


let app = new Vue({
    el: '#app',
    store, 
    data: Window.user,
    components:{
        CheckoutIndex,
        Buttons,
        Reviews,
        ProfilePicture,
        MobilePayemnt
    }
});
export default app

//This code helps  browser like safari that caches
// javascript Back/Forward cache (the cache pulled from when a visitor presses the Back or Forward browser buttons) 

window.onpageshow = function(event) {
    if (event.persisted) {
        store.commit('setLoading',false)
        $('#close-modal').removeClass('pointer-events').trigger('click')
    }
};

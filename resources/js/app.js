require('./bootstrap');


window.Vue = require('vue');
import store from './store'

const CheckoutIndex = require('./components/checkout/Index.vue').default
const Buttons = require('./components/buttons/BuyRentButton.vue').default


let app = new Vue({
    el: '#app',
    store, 
    data: Window.user,
    components:{
        CheckoutIndex,
        Buttons
    }
});
export default app
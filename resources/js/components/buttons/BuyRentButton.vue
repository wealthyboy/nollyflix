<template>
    <div>
        <a href="#!" data-toggle="modal" data-target="#apModal" @click="buyOrRent('Buy')"  class="d-flex flex-row mb-2 rounded-0 btn-primary">
            <span class="purchase-btn-icon  p-4"> 
                <svg viewBox="0 0 16 16"><path d="M13.781 7.25A3.96 3.96 0 0014 6a4 4 0 00-4-4C8.247 2 6.774 3.135 6.233 4.704A2.487 2.487 0 004.5 4 2.5 2.5 0 002 6.5c0 .273.055.531.135.776A3.5 3.5 0 003.5 14h9a3.5 3.5 0 003.5-3.5c0-1.48-.921-2.738-2.219-3.25zM6 11.25v-4.5L10.5 9 6 11.25z"></path></svg>
            </span>
            <span class="purchase-btn-price-text-wrap p-3">
                Buy  {{ $root.video.currency }}{{ $root.video.converted_buy_price }}
                <div class="purchase-btn-price-subtext">Streaming anytime</div> 
            </span>
        </a>
        <a href="#!" data-toggle="modal" data-target="#apModal" @click="buyOrRent('Rent')"  class="d-flex flex-row rounded-0 btn-primary mb-4">
            <div class="purchase-btn-icon p-4">
                <svg viewBox="0 0 16 16"><path d="M4 6h8v4H4z"></path><path d="M16 11V5a3 3 0 01-3-3H3a3 3 0 01-3 3v6a3 3 0 013 3h10a3 3 0 013-3zm-3-1a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1h8a1 1 0 011 1v4z"></path></svg>
            </div>
            <div class="purchase-btn-price-text-wrap p-3">
                Rent  {{ $root.video.currency }}{{ $root.video.converted_rent_price }}
                <div class="purchase-btn-price-subtext">48-hour streaming period</div>
            </div>
        </a>
    
        <div class="clearfix"></div>
        <div class="learn-more"><span>Watch on iOS, Android and Desktop.</span>
            <p><a href="#">Learn more</a></p></div>
    </div>
</template>
<script>

import { mapGetters, mapActions } from 'vuex'

export default {
   
    data(){
        return {
            loading:false,
        }
    },

    methods: {
        buyOrRent(type){
            this.$store.commit('setBuyOrRent',type)
            this.$store.commit('setTitle','To purchase')
            this.$store.commit('showPaymentForm',true)
        },
       
    } 
}
</script>
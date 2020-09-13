<template>
    <!-- Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModal" aria-hidden="true">
        <button type="button" class="close float-close-pro noselect" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header-pro">
                    <h1><a href="/"><img :src="logo" alt="Nolly Flix Logo"></a></h1>
                </div>
                <div v-if="loading" class="modal-body-pro text-center social-login-modal-body-pro">
                     <span  v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                     {{ 'Loading...' }}
                </div><!-- close .modal-body -->
                <div v-else class="modal-body-pro social-login-modal-body-pro">
                    <register />
                    <login />
                    <payment />
                </div><!-- close .modal-body -->
            </div><!-- close .modal-content -->
        </div><!-- close .modal-dialog -->
    </div><!-- close .modal -->
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import Login from './login'
import Payment from './payment'
import Register from './Register'




export default {

    components:{
        Login,
        Payment,
        Register
    },
    data(){
        return {
            loading:false,
        }
    },
    computed:{
        ...mapGetters({
            loggedIn: 'loggedIn'
        }),
        logo : function () {
            return '/images/logo/'+this.$root.settings.store_logo
        }

    },
    created(){
        this.loading = true
        this.me({context:this})
    },
     methods: {
        showLogin(){
            this.$store.commit('showLoginForm',true)
        },
        ...mapActions({
            me:'me',
        }),  
    }

}
</script>
<template>
    <!-- Modal -->
    <div class="modal fade"   id="apModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModal" aria-hidden="true">
        <button type="button"   id="close-modal" class="close float-close-pro noselect" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header-pro">
                    <h1><a href="/"><img width="150" height="100" :src="logo" alt="Nolly Flix Logo"></a></h1>
                </div>
                <div v-if="loading" class="modal-body-pro text-center social-login-modal-body-pro">
                     <span  v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                     
                </div><!-- close .modal-body -->
                <div v-else class="modal-body-pro social-login-modal-body-pro">
                                   </div><!-- close .modal-body -->
            </div><!-- close .modal-content -->
        </div><!-- close .modal-dialog -->
    </div><!-- close .modal -->
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import Login from '../auth/login'
import Payment from './payment'
import Register from '../auth/register'


export default {

    components:{
        Login,
        Payment,
        Register
    },
    data(){
        return {
            loading:false,
            isPaymentCompleted: false,
        }
    },
    computed:{
        ...mapGetters({
            loggedIn: 'loggedIn',
            showPayemtForm: 'showPayemtForm'

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
        showLogin(value){
            this.$store.commit('showLoginForm',true)
        },
        ...mapActions({
            me:'me',
        }),
        disableClick(value){
            console.log(value)
            if (value == 'Completed'){
                this.isPaymentCompleted = true
            } else {
                this.isPaymentCompleted = false
                document.getElementById('close-modal').click()

            }
            
        }

    }

}
</script>
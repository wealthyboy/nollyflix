<template>
    <!-- Modal -->      
    <form method="POST"  @submit.prevent="submit" v-if="showLoginForm && !loggedIn"  action="">
        <div class="aligncenter">
            <h1>LOGIN</h1>
            <p>{{ title }}, login or <span><a @click="showLogin" href="#"> register </a></span></h2></p>

        </div>
            <div class="form-group">
                <input 
                    id="email" 
                    type="email" 
                    class="form-control lrequired"  
                    placeholder="Email" 
                    name="email" 
                    value="" 
                    :class="{'is-invalid': errors.email}" 
                    autocomplete="email" 
                    v-model="form.email" 
                >
                <span class="invalid-feedback" v-if="errors.email" role="alert">
                    <strong>{{ formatError(errors.email) }}</strong>
                </span>
            </div>
            <div class="form-group">
                <input 
                    id="password" 
                    type="password" 
                    class="form-control lrequired" 
                    placeholder="Password" 
                    name="password"  
                    autocomplete="current-password"
                    v-model="form.password"
                    :class="{'is-invalid': errors.password}" 
                    @input="removeError"
                >
                <span class="invalid-feedback" v-if="errors.password" role="alert">
                    <strong>{{ formatError(errors.password) }}</strong>
                </span>
               
            </div>
            <div class="container-fluid">
                <div class="row no-gutters">
                <div class="col checkbox-remember-pro"><input type="checkbox" id="checkbox-remember"><label for="checkbox-remember" class="col-form-label">Remember me</label></div>
                <div class="col forgot-your-password"><a  href="/password/reset">Forgot your password?</a></div>
                </div>
            </div>
            <!-- close .container-fluid -->
            <div class="form-group aligncenter">
                <button type="submit" class="btn">
                    <span  v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Login
                </button>
            </div>
            <div class="aligncenter"><a @click="showLogin" class="not-a-member-pro" href="#">Don't have an  account yet? <span>Signup</span></a></div>
        </form>
               
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data(){
        return {
            showForgotPassword: false,
            loading:false,
            errorsBag:[],
            form: {
                email: '',
                password: '', 
            }
        }
    },
    computed:{
        ...mapGetters({
            errors: 'errors',
            showLoginForm: 'showLoginForm',
            loggedIn: 'loggedIn',
            title: 'title'
        }),
    },
    methods: {
        showLogin(){
            this.$store.commit('showLoginForm',false)
        },
        ...mapActions({
            login:'login',
            validateForm: 'validateForm',
            clearErrors: 'clearErrors',
            checkInput: 'checkInput'
        }),
        formatError(error){
            return Array.isArray(error) ? error[0] : error
        },
        removeError(e){
            let input = document.querySelectorAll('.lrequired');
            if (typeof input !== 'undefined'){
                this.clearErrors({  context:this, input:input })
            }
        },
        vInput(e){
            let input = document.querySelectorAll('.lrequired');
            if (typeof input !== 'undefined') {
                this.checkInput({ context: this, email: this.form.email, input:e })
            }
        },
        submit: function(){
            let input = document.querySelectorAll('.lrequired');
            this.validateForm({ context:this, input:input })
            if ( Object.keys(this.errors).length !== 0 ){
                this.error = "Please check for errors"
                return false;
            }
            this.loading = true
            this.login({
                context:this,
            })
        }
    }

}
</script>
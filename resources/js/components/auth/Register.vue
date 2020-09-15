<template>
    <form   @submit.prevent="submit" v-if="!showLoginForm && !loggedIn"  method="POST" action="">
        <div class="aligncenter">
            <h1>Register</h1>
            <p>{{ title }}, join NollyFlix or <span><a @click="showLogin" href="#"> log in</a></span></h2></p>
        </div>
        <div class="form-group">
            <input 
                id="name" 
                type="text" 
                class="form-control required" 
                :class="{'is-invalid': errors.first_name}" 
                name="first_name" 
                v-model="form.first_name" 
                placeholder="Name" 
                @input="removeError" 
                value=""  
                autocomplete="name"
                autofocus>
                <span class="invalid-feedback" v-if="errors.first_name" role="alert">
                    <strong>{{ formatError(errors.first_name) }}</strong>
                </span>
        </div>
        <div class="form-group">
            <input 
                id="last_name" 
                type="text" 
                :class="{'is-invalid': errors.last_name}" 
                @input="removeError" 
                class="form-control required" 
                name="last_name" 
                v-model="form.last_name" 
                placeholder="Last Name" 
                value="" 
                autocomplete="name"
                autofocus
            >
            <span class="invalid-feedback" v-if="errors.last_name" role="alert">
                <strong>{{ formatError(errors.last_name) }}</strong>
            </span>
        </div>
        <div class="form-group">
            <input 
                id="email" 
                type="email" 
                v-model="form.email" 
                :class="{'is-invalid': errors.email}" 
                class="form-control required" 
                name="email"  
                placeholder="Email" 
                @input="removeError" 
                @blur="checkInput($event)"
                value="" 
                autocomplete="email"
            >
            <span class="invalid-feedback" v-if="errors.email" role="alert">
                <strong>{{ formatError(errors.email) }}</strong>
            </span>
        </div>
        <div class="form-group">
            <input 
                id="password" 
                type="password" 
                class="form-control required" 
                placeholder="Password"  
                v-model="form.password"
                :class="{'is-invalid': errors.password}" 
                @input="removeError" 
                name="password" 
                autocomplete="new-password"
            >
            <span class="invalid-feedback" v-if="errors.password" role="alert">
                <strong>{{ formatError(errors.password) }}</strong>
            </span>
        </div>
        <div class="form-group">
            <input 
                id="password-confirm" 
                type="password" 
                class="form-control required" 
                name="password_confirmation" 
                placeholder="Confirm Password"
                @input="removeError" 
                autocomplete="new-password"
                v-model="form.password_confirmation"  
            >
        </div>
        <div class="form-group aligncenter">
            <button type="submit" class="btn">
                <span  v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Submit
            </button>
        </div>
        <div class="aligncenter"><a class="not-a-member-pro" href="#"  @click="showLogin">Already   have an  account? <span>Login</span></a></div>
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
                first_name: null,
                last_name: null,
                password_confirmation: null,
            }
        }
    },
    computed:{
        ...mapGetters({
            errors: 'errors',
            showLoginForm:'showLoginForm',
            loggedIn: 'loggedIn',
            user: 'user',
            title: 'title'
        })
    },
  
    methods: {
        showLogin(){
            this.$store.commit('showLoginForm',true)
        },
        ...mapActions({
            register: 'register',
            validateForm: 'validateForm',
            clearErrors: 'clearErrors',
            checkInput: 'checkInput'
        }),
        formatError(error){
            return Array.isArray(error) ? error[0] : error
        },
        removeError(e){
            let input = document.querySelectorAll('.required');
            if (typeof input !== 'undefined'){
                this.clearErrors({  context:this, input:input })
            }
        },
        vInput(e){
            let input = document.querySelectorAll('.required');
            if (typeof input !== 'undefined') {
                this.checkInput({ context: this, email: this.form.email, input:e })
            }
        },
        submit: function(){
            let input = document.querySelectorAll('.required');
            this.validateForm({ context:this, input:input })
            if ( Object.keys(this.errors).length !== 0 ){
                this.error = "Please check for errors"
                return false;
            }
            this.loading = true
            this.register({
                context:this,
            })
        }
    }

}
</script>
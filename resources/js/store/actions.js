import axios from 'axios'
import store from './index'



export const flashMessage = ({ commit },message) => {
    commit('setMessage', message) 
    setTimeout(() => {
        commit('clearMessage')  
    }, 3000);
}


export const login = ({ commit },{ context }) => {

    return axios.post('/login',context.form).then((response) => {
        commit('loggedIn',true)
        commit('setUser',response.data.user)
        context.loading = false
        if (!store.getters.showPayemtForm){
            document.getElementById("close-modal").click()
        }
        return Promise.resolve()
    }).catch((error)=>{
        context.loading = false
        if ( error.response.status == 500 ){
            commit('setFormErrors', {
                general: "We could register you.Please try again later"
            })
            return;
        }
        commit('setFormErrors', error.response.data.errors)
    })
}


export const me = ({ commit },{ context }) => {
    return axios.post('/me').then((response) => {
        commit('loggedIn',true)
        commit('setUser',response)
        context.loading = false
        return Promise.resolve()
    }).catch((error)=>{
        context.loading = false
    })
}


export const register = ({ commit },{ context }) => {
    return axios.post('/register',context.form).then((response) => {
        commit('loggedIn',true)
        commit('setUser',response.data.user)
        if (!store.getters.showPayemtForm){
            document.getElementById("close-modal").click()
        }
        context.loading = false
    }).catch((error) =>{
        context.loading = false
        if ( error.response.status == 500 ){
            commit('setFormErrors', {
                general: "We could register you.Please try again later"
            })
            return;
        }
        commit('setFormErrors', error.response.data.errors)
    })
}


export const createComments = ({commit} , { payload, context,form }) => {
    return axios.post('/comments/store',form).then((response) => {
        context.submiting = false;
        commit('setComments', response.data.data)
    }).catch((error) => {
        context.submiting = false;
        if ( error.response.status == 500 ){
            let errors = { general: "We could create your comment"}
            commit('setFormErrors', errors)
            return;
        }
        if (error.response.data.errors){
            commit('setFormErrors', error.response.data.errors)
        }
    }) 
}


export const updatePassword = ({commit,dispatch} , { payload, context }) => {
    return axios.put('/change/password',payload).then((response) => {
        context.loading = false
        commit('setMessage', response.data.message)
    }).catch((error) => {
        context.loading = false
        if ( error.response.status == 500 ){
            context.error = "We could not change your password at the moment .Please try again"
            return;
        }
        if (error.response.data.errors){
            context.error =  error.response.data.errors
            commit('setFormErrors', error.response.data.errors)
        }
    }) 
}


export const resetPassword = ({commit} , { payload, context }) => {
    return axios.post('/reset/password',payload).then((response) => {
        context.loading = false;
        commit('setMessage', response.data.message)
    }).catch((error) => {
        context.loading = false
        if ( error.response.status == 500 ){
            context.error = "We could not send your password reset link"
            commit('setFormErrors', "We could not send your password reset link")
            return;
        }
        if ( error.response.data.errors ){
            commit('setFormErrors', error.response.data.errors)
            return
        }
    }) 
}



export const validateForm = ({dispatch,commit},{context,input}) => {

    let p = {},k,errors = []
    if (input.length) {
        input.forEach(function( element,v){
            if (element.value == '' ){
                k = element.name.split('_').join(' ');
                errors = Object.assign({}, errors, { [ element.name ]: k + '  is required' }) 
                console.log(errors)  
            }
            if (element.name == 'email'){
                let value = element.value;
                if ( !validateEmail(value) ) {
                    p.email = 'Please enter a valid email'
                }
            }
        })
    }

    if (  
        context.form.password !== ''  && 
        typeof context.form.password_confirmation !== 'undefined' &&
        context.form.password_confirmation !== '' ) {
        if ( context.form.password !== context.form.password_confirmation ){
              p.password_confirmation = 'Password do not match'
        }
    } 
    errors = Object.assign({}, errors, p);
    commit('setFormErrors',errors)
}



export const forgotPassword = ({commit} , { payload, context }) => {
    return axios.post('/password/reset/link',payload).then((response) => {
        context.loading = false;
        commit('setMessage', response.data.message)
    }).catch((error) => {
        context.loading = false
        if ( error.response.status == 500 ){
            let errors = { general: "We could not send your password reset link"}
            commit('setFormErrors', errors)
            return;
        }
        if (error.response.data.errors){
            commit('setFormErrors', error.response.data.errors)
        }
    }) 
}



export const getReviews = ({commit} , { context }) => {
    return axios.get('/reviews/'+ context.product_slug).then((response) => {
        context.loading = false;
        commit('setReviews', response.data.data)
    }).catch((error) => {
        context.loading = false;
        if ( error.response.status == 500 ){
            let errors = { general: "We could not send your password reset link"}
            commit('setFormErrors', errors)
            return;
        }
        if (error.response.data.errors){
            commit('setFormErrors', error.response.data.errors)
        }
    }) 
}


export const clearError =  ({commit}) => {
    let errors = {}
    commit('setFormErrors',errors)
}

export const clearErrors =  ({commit},{context,input}) => {
    let k;
    let p = {},errors = []
    if (input.length) {
        input.forEach(function( element,v){
            console.log()
            if (element.value !== '' ){
                const prop = element.name
                delete context.errorsBag[prop]
            } 
            if (  
                context.form.password !== ''  && 
                typeof context.form.password_confirmation !== 'undefined' &&
                context.form.password_confirmation !== ''
             ) {
                if ( context.form.password !== context.form.password_confirmation ){
                    p.password_confirmation = 'Password do not match'
                }
            } 
        })
    }
    errors = Object.assign({}, context.errorsBag, p);
    commit('setFormErrors',errors)
}

export const checkInput =  ({commit},{context,input}) => {
    let p = {},errors = [],errMsg = [],k
    if (typeof input !== 'undefined'){
        if (input.target.value == '') {
           k = input.target.name.split('_').join(' ');
           errMsg = Object.assign({}, errMsg, { [ input.target.name ]: k + '  is required' })
        }
        if ( input.target.name == 'email') {
            if (!validateEmail(input.target.value)  ){
                p.email = 'Please enter a valid email'
            }
        }
    }

    if (typeof context !== 'undefined'){
        errMsg = Object.assign({}, context.errorsBag, errMsg);
    }
    errors = Object.assign({}, errMsg, p);
    commit('setFormErrors',errors)
}

export const validateEmail =  (email) => {
   return  ruleE().test(String(email).toLowerCase());   
}

export const ruleE = () => {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re;
}




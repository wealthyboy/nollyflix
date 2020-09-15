<template>

    <div class="row pt-5 ">
        <div class="col-12 col-lg-6 mb-5">
            <div v-if="loggedIn" class="review-form-wrapper">
                <h3 class="review-title text-uppercase ml-3">Add a Comment</h3>
                <p class="ml-3"> Required fields are marked *</p>
                <form id="comment-form" @submit.prevent="submit" class="comment-form">
                    

                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label>Your Comment<span class="req">*</span></label>
                        <textarea   
                                id="comment" 
                                v-model="form.description" 
  
                                name="description" 
                                class="form-control rating_required" 
                                cols="45" 
                                rows="10"
                                aria-required="true" 
                                 @input="removeError($event)"
                                @blur="vInput($event)" 
                        >
                        </textarea>
                            
                            <span class="help-block error  text-danger text-sm-left" v-if="errors.description">
                                <strong class="text-danger">{{ formatError(errors.description) }}</strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <button  type="submit" class="btn btn-primary rounded-0 btn-lg btn-block" name="checkout_place_order" id="place_order" value="Place order" data-value="Place Order">
                                <span  v-if="submiting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Save
                            </button>
                        </div>
                </form>
            </div>
            <div v-if="!loading && !loggedIn"   class="review-form-wrapper">
                <button data-toggle="modal" @click="setTitle" data-target="#apModal"  type="button"  class="btn btn-primary rounded-0 btn-lg btn-block"><i class="fas fa-comment"></i> Add Comment </button>
            </div>
            
        </div>
        <div class="col-12 col-lg-6">
            <div v-if="loading" class="comments"> Loading...</div>

            <div v-if="!loading && comments.length" class="comments">
                <h3 class="review-title text-uppercase">{{ meta.total }}  Comment(s)</h3>
                <div v-for="comment in comments" :key="comment.id"  class="media mb-3">
                    <img src="/images/icons/avtar.jpg" class="mr-3 rounded-circle" alt="...">
                    <div class="media-body">
                        <h5 class="mt-0">{{ comment.full_name }}  <small class="mt-0">{{ comment.date }}</small></h5>
                        {{ comment.description }}
                    </div>
                </div>
                
            </div>
            <div  v-if="!loading && !comments.length"  class="comments">
                No Comments yet.
            </div>
        <!--Paginattion-->
          

            <div v-if="!loading && meta && meta.total > meta.per_page"  class="">
                <pagination :meta="meta" />
            </div>
        </div> 


       
    </div>


                
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

import  Login from '../auth/login'
import  Register from '../auth/register'
import  Pagination from '../pagination/Index'




export default {
    components:{
       Login,
       Register,
       Pagination
    },
    data(){
        return {
            loading: false,
            errorsBag:[],
            fadeIn: false,
            form:{
                description: null,
            },
            submiting:false
        }
    },
    computed: {
        ...mapGetters({
            loggedIn:'loggedIn',
            errors:'errors',
            comments: 'comments',
            meta: 'commentsMeta',

        }),
    },
    created(){
       this.videoComments() 
    },
    methods: {
        setTitle(){
            this.$store.commit('setTitle','To comment')
            this.$store.commit('showPaymentForm',false)
            this.$store.commit('setFormErrors',{})
        },
        videoComments() {
            this.loading =true
            return axios.get('/comments/'+ this.$root.video.slug).then((response) => {
                this.loading = false;
                this.$store.commit('setComments', response.data.data)
                this.$store.commit('setCommentsMeta', response.data.meta)
            }).catch((error) => {
                this.loading = false;
            }) 
        },
       
        formatError(error){
            return Array.isArray(error) ? error[0] : error
        },
        removeError(e){
            let input = document.querySelectorAll('.rating_required');
            if (typeof input !== 'undefined'){
                this.clearErrors({  context:this, input:input })
            }
        },
        vInput(e){
            let input = document.querySelectorAll('.rating_required');
            if (typeof input !== 'undefined') {
                this.checkInput({ context: this, input:e })
            }
        }, 
        ...mapActions({
            createComments: 'createComments',
            validateForm:  'validateForm',
            clearErrors:   'clearErrors',
            checkInput:    'checkInput',
        }),
      
        submit(){
            let input = document.querySelectorAll('.rating_required');
            this.validateForm({ context:this, input:input })
            if ( Object.keys(this.errors).length !== 0 ){ 
                return false; 
            }
            this.submiting = true
            let form = new FormData();
            form.append('description',this.form.description)
            //this.$root came from the Vue isntance app.js
            form.append('video_id',this.$root.video.id)
            this.createComments({ context: this ,form})
        }  
    }
}
</script>
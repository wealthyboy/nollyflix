<template>

    <div class="row pt-5">
        <div class="col-12 col-lg-6">
            <div v-if="user" class="review-form-wrapper">
                <h3 class="review-title text-uppercase">Add a Comment</h3>
                <p> Required fields are marked *</p>
                <form id="comment-form" class="comment-form">
                    

                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label>Your Comment<span class="req">*</span></label>
                        <textarea   
                                id="comment" 
                                    
                                name="description" 
                                class="form-control rating_required" 
                                cols="45" 
                                rows="10"
                                aria-required="true" 
                        >
                        </textarea>
                            <span class="help-block error  text-danger text-sm-left" >
                                <strong class="text-danger"></strong>
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
            <div v-if="!loggedIn"   class="review-form-wrapper">
                <button data-toggle="modal" data-target="#checkoutModal"  type="button"  class="btn btn-primary rounded-0 btn-lg btn-block"><i class="fas fa-comment"></i> Add Comment </button>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="comments">
                <h5 class="review-title text-uppercase"> Comments </span></h5>
                <div class="media">
                    <img src="/images/icons/avtar.jpg" class="mr-3" alt="...">
                    <div class="media-body">
                        <h5 class="mt-0">Media heading  <small class="mt-0">01/02/30</small></h5>
                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                    </div>
                </div>
                
            </div>
            <div  class="comments">
                
            </div>
        <!--Paginattion-->
          

            <div class="pagination-wraper">
                <div class="pagination">
                </div>
            </div>
        </div> 
    </div>


                
</template>
<script>

import  Login from '../auth/login'
import  Register from '../auth/register'



export default {
    name: "Show",
    props:{
        product:Object,
        attributes:Object,
    },
    components:{
       Images,
       LoginModal,
       Pagination,
       RegisterModal
    },
    data(){
        return {
            loading: false,
            errorsBag:[],
            fadeIn: false,
            form:{
                description: null,
                product_id:this.product.id ,
                image: null
            },
            submiting:false
        }
    },
    computed: {
        ...mapGetters({
            cart: 'cart' ,
            loggedIn:'loggedIn',
            reviews: 'reviews',
            meta: 'reviewsMeta',
            errors:'errors'
        }),
    },
    created(){
        this.commentsReviews() 
    },
    methods: {
    
        videoComments() {
            return axios.get('/reviews/'+ 9).then((response) => {
                this.loading = false;
                this.$store.commit('setReviews', response.data.data)
                this.$store.commit('setReviewsMeta', response.data.meta)
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
            createReviews: 'createReviews',
            validateForm:  'validateForm',
            clearErrors:   'clearErrors',
            checkInput:    'checkInput',
            getReviews:    'getReviews'
        }),
      
        submit(){
            let input = document.querySelectorAll('.rating_required');
            this.validateForm({ context:this, input:input })
            if ( Object.keys(this.errors).length !== 0 ){ 
                if (!this.form.rating){
                   this.noRating = true
                }
                return false; 
            }
            this.submiting = true
            let form = new FormData();
             
            form.append('description',this.form.description)
            form.append('rating',this.form.rating)
            form.append('video_id',this.form.product_id)
            this.createReviews({ context: this ,form})
        }  
    }
}
</script>
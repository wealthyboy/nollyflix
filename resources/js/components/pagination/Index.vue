<template>
    <!--Paginattion-->
    <ul class="page-numbers">
        <li>
            <a href="#" @click.prevent="switched(meta.current_page - 1)"   :class="{'disabled': meta.current_page === 1 }"  class="previous"><i class="fas fa-chevron-left"></i></a>
        </li>
        <li :key="x" v-for="x in meta.last_page">
            <a @click.prevent="switched(x)" href="#"  :class="{'current': meta.current_page  === x }" class="page-number">{{ x }}</a>
        </li>
        
        <li>
            <a  @click.prevent="switched(meta.current_page + 1)" :class="{'disabled': meta.current_page === meta.last_page }"   href="#" class="next page-numbers-select"><i class="fas fa-chevron-right"></i></a>
        </li>
    </ul>
            
    <!--End Paginattion-->
</template>
<script>
export default {
    props:{
        meta:Object,
        useUrl:Boolean
    },
   
    methods:{
        switched(page){
            if (this.pageIsFinished(page)) {
               return;
            }
            this.$emit('pagination:switched',page)

            if (this.useUrl){
                  this.$router.replace({
                    query:{
                       page
                    }
                })
            } else {
                return  axios.get(this.meta.path + '?page=' +page).then((response) => {
                            this.loading = false;
                            this.$store.commit('setComments', response.data.data)
                            this.$store.commit('setCommentsMeta', response.data.meta)
                        }).catch((error) => {}) 
            }
          
        },
        pageIsFinished(page){
           return page <= 0 || page > this.meta.last_page;
        }
    }

}
</script>
<style>
 
</style>
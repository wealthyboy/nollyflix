<template>
    <div id="content-sidebar-info ">
        <div id="avatar-sidebar-large-profile" @click.prevent="activateFile" :style="{ 'background-image': 'url(' + profile_photo + ')' }"></div>
        <a href="#" @click.prevent="activateFile" class="edit-profile-pic">
            <span v-html="icon"></span> 
            {{ text }}
        </a>
        <input accept="image/*"  @change="readURL($event)"  class="profile-pic" data-msg="Upload  your image" type="file" id="file_upload_input" name="img"  />
    </div>
</template>
<script>
export default {
    data(){
        return {
            file:null,
            profile_photo: this.$root.user.profile_picture,
            allowedFileTypes: ['image/jpeg','image/png','image/gif'],
            submiting: false,
            text: 'Edit',
            icon: '<i class="fas fa-edit"></i>'
        }
    },
    methods:{
        activateFile(evt){
            let fileInput = document.getElementById("file_upload_input")
            fileInput.click()
        },
        readURL(input) {
            this.file  = document.getElementById("file_upload_input").files[0];
            if ( !this.allowedFileTypes.includes(this.file.type) ){
                this.error = "Your selected  is not alllowed"
                return
            }
            //there I CHECK if the FILE SIZE is bigger than 8 MB (numbers below are in bytes)
            if ( this.file.size > 8388608 ){
                this.error = "Allowed file size exceeded. (Max. 8 MB)"
                return;
            } 
            
            this.text = 'Uploading...'
            this.icon = '<i class="fas fa-spinner"></i>'

            var reader = new FileReader();
            let context = this
            reader.onload = function (e) {
                context.profile_photo = e.target.result
            }
            reader.readAsDataURL(this.file);

            this.submiting = true

            let form = new FormData();
             
            form.append('file',this.file)

            axios.post('/profile/picture',form).then((response) => {
                this.submiting = false;
                this.text = 'Uploaded'
                this.icon = '<i class="fas fa-check"></i>'
                 setTimeout(() => {
                    this.text = 'Edit'
                    this.icon = '<i class="fas fa-edit"></i>'
                }, 3000);
            }).catch((error) => {
                this.submiting = false;
                if ( error.response.status == 500 ){
                    let errors = { general: "We could not upload your picture"}
                    return;
                }
               
            }) 
       }
    } 
}
</script>
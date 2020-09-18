<template>
    <div :class="{ 'pointer-events': submiting }" id="">
        <div id="avatar-sidebar-large-profile" @click.prevent="activateFile" :style="{ 'background-image': 'url(' + profile_photo + ')' }"></div>
        <a href="#" @click.prevent="activateFile"  :style="{'background-color': statusColor}" class="edit-profile-pic "><span v-html="icon" class="mr-1"></span>{{ text }}</a>
        <input accept="image/*"   class="profile-pic-file" data-msg="Upload  your image" @change="readURL($event)" type="file" id="file_upload_input" name="img"  />
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
            icon: '<i class="fas fa-edit"></i>',
            statusColor: 'rgba(0,0,0,.7)'
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
                this.statusColor = 'green'
                setTimeout(() => {
                    this.text = 'Edit'
                    this.icon = '<i class="fas fa-edit"></i>'
                    this.statusColor = 'rgba(0,0,0,.7)'

                }, 1000);
            }).catch((error) => {
                this.submiting = false;
                this.text = 'Uploading Failed'
                this.icon = '<i aria-hidden="true" class="fas fa-times"></i>'

                this.statusColor = 'red'
                setTimeout(() => {
                    this.text = 'Edit'
                    this.icon = '<i class="fas fa-edit"></i>'
                    this.statusColor = 'rgba(0,0,0,.7)'
                }, 1000);
            }) 
       }
    } 
}
</script>
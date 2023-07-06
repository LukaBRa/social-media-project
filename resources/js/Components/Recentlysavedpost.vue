<script>

export default {
    data(){
        return {
            userEmail: '',
            post: Object,
        }
    },
    props: [
        'savedPost'
    ],
    mounted(){
        console.log(this.savedPost)
        axios.get("http://localhost:8000/api/post/" + this.savedPost.post_id + "/getPost")
        .then(response => {
            this.post = response.data;
            axios.get("http://localhost:8000/api/get-user", {
                params: {
                    userId: this.post.user_id,
                }
            })
            .then(response => {
                this.userEmail = response.data.email
            })
            .catch(error => console.log(error));
        })
        .catch(error => console.log(error));
        
    }
}

</script>

<template>

<a :href="'/post/' + post.id" class="saved">
    <img :src="'/images/' + userEmail + '/posts/' + post.image_name" alt="Saved image">
</a>

</template>

<style>

.saved{
    width: 130px;
    height: 130px;
}

.saved img{
    width:100%; 
    height:100%;
    object-fit: cover;
}

</style>
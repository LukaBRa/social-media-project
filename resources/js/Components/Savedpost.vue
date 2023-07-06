<script>
import axios from 'axios';


export default {
    data() {
        return {
            savedPost: {},
            userEmail: ''
        }
    },  
    props: [
        'post'
    ],
    mounted() {
        axios.get("http://localhost:8000/api/post/" + this.post.post_id + "/getPost")
        .then(response => {
            this.savedPost = response.data;
            axios.get("http://localhost:8000/api/get-user", {
                params: {
                    userId: this.savedPost.user_id,
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

<div class="profile-post">
    <a href="">
        <img :src="'/images/' + userEmail + '/posts/' + savedPost.image_name" alt="Post">
    </a>
</div>

</template>

<style scoped>

.profile-post{
    width: 250px;
    height: 250px;
}

.profile-post a img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

</style>
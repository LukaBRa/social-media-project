<script>
import Profilepost from "./Profilepost.vue";

export default{
    components: {
        Profilepost,
    },
    props: [
        'posts',
        'userEmail'
    ],
}

</script>

<template>

<div class="profile-posts">

    <Profilepost v-for="post in posts" :key="post.id" :postId="post.id" :imageName="post.image_name" :userEmail="userEmail"/>

</div>

</template>

<style scoped>

.profile-posts{
    margin-top: 1rem;
    width: 70%;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    height: 100%;
    overflow: auto;
    align-items: center;
    gap: 1rem;
}

</style>
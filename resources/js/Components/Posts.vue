<script>
import Post from './Post.vue'

export default{
    components: {
        Post
    },
    props: [
        'posts',
        'signedUser'
    ],
}

</script>

<template>

<div class="posts">
    <Post v-for="post in posts" :key="post.id" :post="post" :signedUser="signedUser"/>

    <h3 class="pregledano">Pregledali ste sve objave...</h3>

</div>

</template>

<style scoped>

.posts{
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10rem;
    overflow-y: auto;
    height: 85vh;
}

.pregledano{
    color: lightgrey;
    padding: 1rem 0rem;
}

</style>
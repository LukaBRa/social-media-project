<script>
import Savedpost from "./Savedpost.vue";

export default{
    components: {
        Savedpost,
    },
    props: [
        'posts',
        'userEmail'
    ],
}

</script>

<template>

<div class="profile-posts">

    <Savedpost v-for="post in posts" :key="post.id" :post="post"/>

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
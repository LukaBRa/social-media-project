<script>

export default {
    props: [
        'postId',
        'imageName',
        'userEmail'
    ]
}

</script>

<template>

<div class="profile-post">
    <a :href="'/post/' + postId">
        <img :src="'/images/' + userEmail + '/posts/' + imageName" alt="Post">
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
<script>

export default {
    data() {
        return {
            user: {},
        }
    },
    props: [
        'signedUser',
        'comment',
    ],
    mounted() {
        axios.get("http://localhost:8000/api/get-user", {
            params: {
                userId: this.comment.user_id
            }
        })
        .then(response => {
            this.user = response.data
        })
        .catch(error => console.log(error));
    },
    computed: {
        isOwner() {
            return this.signedUser.id == this.comment.user_id || this.signedUser.user_type == "administrator"
        }
    }
}

</script>

<template>

<div class="comment">
    <div class="comment-header">
        <a :href="'/profile/' + user.id" class="comment-user">
            <div class="image-div">
                <img :src="'/images/' + user.email + '/' + user.image_name" alt="User">
            </div>
            <p>{{ user.name }}</p>
        </a>
        <button @click="$emit('deleteComment', comment.id)" v-if="isOwner" class="delete-btn"><i class="fa-solid fa-trash-can"></i></button>
        <button @click="$emit('reportComment', comment.id)" v-else class="delete-btn"><i class="fa-solid fa-circle-exclamation"></i></button>
    </div>
    <p class="comment-content">{{ comment.comment_text }}</p>
</div>

</template>

<style>

:root{
  --primary: #5664f9;
  --secondary: #1e1e1e;
  --lightgray: #f5f5f5;
  --lightred: #fa3678;
  --bookmarked: #fec604;
}

.comment{
    padding: 0rem 1rem;
    width: 100%;
}

.comment-header{
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.image-div{
    width: 40px;
    height: 40px;
}

.image-div img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.comment-user{
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    color: var(--secondary);
    font-weight: bold;
}

.delete-btn{
    background: none;
    border: none;
    color: var(--lightred);
    cursor: pointer;
}

.comment-content{
    overflow-wrap: break-word;
    padding: 0rem 3rem;
    font-size: 0.9rem;
    margin-top: -6px;
    width: 100%;
}

</style>
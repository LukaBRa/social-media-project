<script>

export default{
    data() {
        return {
            user: {},
        }
    },
    props: [
        'admin',
        'reportedcomment'
    ],
    methods: {
        deleteComment() {
            axios.delete("http://localhost:8000/api/comment-delete", {
                params: {
                    commentID: this.reportedcomment.id
                }
            })
            .then(response => {
                this.$emit('deletedComment', this.reportedcomment.id);
                console.log(response);
            })
            .catch(error => console.log(error));
        },
        removeFromReported() {
            axios.post("http://localhost:8000/api/comment/remove-reported", {
                commentId: this.reportedcomment.id
            })
            .then(response => {
                this.$emit('deletedComment', this.reportedcomment.id);
                console.log(response);
            })
            .catch(error => console.log(error));
        }
    },
    mounted(){ 
        axios.get("api/get-user",{
            params: {
                userId: this.reportedcomment.user_id
            }
        })
        .then(response => {
            this.user = response.data
        })
        .catch(error => console.log(error));
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
        <div class="buttons">
            <button @click="deleteComment" class="delete-btn"><i class="fa-regular fa-trash-can"></i></button>
            <button @click="removeFromReported" class="delete-btn"><i class="fa-solid fa-x"></i></button>
            <a :href="'/post/' + reportedcomment.post_id"><i class="fa-solid fa-link"></i></a>
        </div>
    </div>
    <p class="comment-content">{{ reportedcomment.comment_text }}</p>
</div>

</template>

<style scoped>

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
    border-bottom: 1px solid rgb(230, 230, 230);
    padding-bottom: 1rem;
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

.buttons{
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
}

.buttons button{
    font-size: 1.1rem;
}

</style>
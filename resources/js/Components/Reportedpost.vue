<script>
import axios from 'axios';



export default{
    data() {
        return {
            user: {},
        }
    },
    props: [
        'admin',
        'reportedpost'
    ],
    methods: {
        deletePost() {
            axios.delete("http://localhost:8000/api/post/" + this.reportedpost.id + "/delete")
            .then(response => {
                this.$emit('deletedPost', this.reportedpost.id);
                console.log(response);
            })
            .catch(error => console.log(error));
        },
        removeFromReported() {
            axios.post("http://localhost:8000/api/post/remove-reported", {
                postId: this.reportedpost.id
            })
            .then(response => {
                this.$emit('deletedPost', this.reportedpost.id);
                console.log(response);
            })
            .catch(error => console.log(error));
        }
    },
    mounted(){ 
        axios.get("api/get-user",{
            params: {
                userId: this.reportedpost.user_id
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

<div class="post">
    <img class="post-image" :src="'/images/' + user.email + '/posts/' + reportedpost.image_name" alt="Post image" loading="lazy">
    <div class="post-info">
        <a :href="'/profile/' + user.id" class="user">
            <div class="post-friend-image">
                <img :src="'/images/' + user.email + '/' + user.image_name" alt="Friend profile image">
            </div>
            <p>{{ user.name }}</p>
        </a>
        <div class="post-stats">
            <div class="stat">
                <button @click="deletePost"  class="alert-btn"><i class="fa-regular fa-trash-can"></i></button>
            </div>
            <div class="stat">
                <button @click="removeFromReported" class="alert-btn"><i class="fa-solid fa-x"></i></button>
            </div>
            <div class="stat">
                <a :href="'/post/' + reportedpost.id"><i class="fa-solid fa-link"></i></a>
            </div>
        </div>
    </div>
    <div class="description">
            <p>{{ reportedpost.description }}</p>
    </div>
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
.post{
    width: 300px;
    height: 400px;
}

.post .post-image{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.post-info{
    display: flex;
    justify-content: space-between;
    margin-top: 0.5rem;
}

.post-friend-image{
    width: 40px;
    height: 40px;
}

.post-friend-image img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.post-stats{
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.post-stats .stat{
    display: flex;
    gap: 0.2rem;
}

.post-stats .stat i{
    font-size: 1.2rem;
}

.post-stats .stat button{
    background: none;
    border: none;
    cursor: pointer;
}

.post-stats .stat .like{
    color: var(--secondary);
}

.post-stats .stat .liked{
    color: var(--lightred);
}

.post-stats .stat .bookmark{
    color: var(--primary);
}

.post-stats .stat .bookmarked{
    color: #fec604;
}

.post-stats .stat a{
    text-decoration: none;
    color: var(--primary);
}

.user{
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.user p{
    font-weight: bold;
    color: var(--secondary);
}

.user img{
    border-radius: 50%;
}

.description{
    margin-top: 0.7rem;
}
.description p{
    color: rgb(84, 84, 84);
    font-size: 0.97rem;
    display: -webkit-box;
    max-width: 300px;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.alert-btn{
    color: var(--lightred);
}

</style>
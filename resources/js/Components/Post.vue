<script>
import axios from 'axios';



export default{
    data() {
        return {
            numberOfLikes: 0,
            numberOfComments: 0,
            liked: false,
            bookmarked: false,
            user: {},
        }
    },
    methods: {
        likePost(){
            this.liked = !this.liked
            if(this.liked == true){
                axios.get("api/like-post/" + this.signedUser.id + "/post/" + this.post.id)
                .then(response => console.log(response))
                .catch(error => console.log(error));
                ++this.numberOfLikes;
            }else{
                axios.get("api/dislike-post/" + this.signedUser.id + "/post/" + this.post.id)
                .then(response => console.log(response))
                .catch(error => console.log(error));
                --this.numberOfLikes;
            }
        },

        bookmarkPost(){
            if(this.bookmarked){
                axios.get("api/post/" + this.post.id + "/unsave/" + this.signedUser.id)
                .then(response => {
                    console.log(response);
                    this.bookmarked = false;
                })
                .catch(error => console.log(error));
            }else{
                axios.get("api/post/" + this.post.id + "/save/" + this.signedUser.id)
                .then(response => {
                    console.log(response);
                    this.bookmarked = true;
                })
                .catch(error => console.log(error));
            }
        }
    },
    props: [
        'post',
        'signedUser'
    ],
    mounted(){ 
        axios.get("api/get-user",{
            params: {
                userId: this.post.user_id
            }
        })
        .then(response => {
            this.user = response.data
        })
        .catch(error => console.log(error));
        axios.get("api/post/" + this.post.id + "/stats/" + this.signedUser.id)
        .then(response => {
            this.numberOfLikes = response.data.numberOfLikes;
            this.numberOfComments = response.data.numberOfComments;
            this.liked = response.data.liked;
            this.bookmarked = response.data.isSaved;
        })
        .catch(error => console.log(error));
    }
}

</script>

<template>

<div class="post">
    <a :href="'/post/' + post.id">
        <img class="post-image" :src="'/images/' + user.email + '/posts/' + post.image_name" alt="Post image">
    </a>
    <div class="post-info">
        <a :href="'/profile/' + post.user_id" class="user">
            <div class="post-friend-image">
                <img :src="'/images/' + user.email + '/' + user.image_name" alt="Friend profile image">
            </div>
            <p>{{ user.name }}</p>
        </a>
        <div class="post-stats">
            <div class="stat">
                <button @click="likePost" v-if="!liked" class="like"><i class="fa-regular fa-heart"></i></button>
                <button @click="likePost" v-else class="liked"><i class="fa-solid fa-heart"></i></button>
                <p>{{ numberOfLikes }}</p>
            </div>
            <div class="stat">
                <a :href="'/post/' + post.id"><i class="fa-solid fa-comments"></i></a>
                <p>{{ numberOfComments }}</p>
            </div>
            <div class="stat">
                <button @click="bookmarkPost" v-if="!bookmarked" class="bookmark"><i class="fa-solid fa-bookmark"></i></button>
                <button @click="bookmarkPost" v-else class="bookmarked"><i class="fa-solid fa-bookmark"></i></button>
            </div>
        </div>
    </div>
    <div class="description">
            <p>
                {{ post.description }}
            </p>
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

</style>
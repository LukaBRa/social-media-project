<script>
import Sidebarextended from '../Components/Sidebarextended.vue'
import Comment from '../Components/Comment.vue'
import ReportMessage from '../Components/ReportMessage.vue';

export default {
    data() {
        return {
            numberOfLikes: 0,
            isLiked: false,
            isSaved: false,
            commentContent: '',
            postComments: [],
            canDelete: false,
            canReport: true,
            numberOfNewNotifications: 0,
            newReport: 0,
            msg: ''
        }
    },
    computed: {
        btnDisabled() {
            return this.commentContent == ''
        },
        loadReport() {
            return this.newReport;
        }
    },  
    components: {
        Sidebarextended, Comment, ReportMessage
    },
    props: [
        'post',
        'signedUser',
        'user',
        'comments',
        'newNotifs'
    ],
    methods: {
        likePost() {
            if(!this.isLiked){
                axios.get("http://localhost:8000/api/like-post/" + this.signedUser.id + "/post/" + this.post.id)
                .then(response => console.log(response))
                .catch(error => console.log(error));
                this.isLiked = true
                ++this.numberOfLikes
            }else{
                this.isLiked = false
                axios.get("http://localhost:8000/api/dislike-post/" + this.signedUser.id + "/post/" + this.post.id)
                .then(response => console.log(response))
                .catch(error => console.log(error));
                --this.numberOfLikes;
            }
        },
        savePost() {
            if(this.isSaved){
                axios.get("http://localhost:8000/api/post/" + this.post.id + "/unsave/" + this.signedUser.id)
                .then(response => {
                    console.log(response);
                    this.isSaved = false;
                })
                .catch(error => console.log(error));
            }else{
                axios.get("http://localhost:8000/api/post/" + this.post.id + "/save/" + this.signedUser.id)
                .then(response => {
                    console.log(response);
                    this.isSaved = true;
                })
                .catch(error => console.log(error));
            }
        },
        postComment(){
            axios.get("http://localhost:8000/api/comment-store", {
                params: {
                    postId: this.post.id,
                    userId: this.signedUser.id,
                    commentText: this.commentContent
                }
            })
            .then(response => {
                this.comments.push(response.data.newComment);
                this.commentContent = "";
            })
            .catch(error => console.log(error));
        },
        deleteComment(commentId){
            axios.delete("http://localhost:8000/api/comment-delete", {
                params: {
                    commentID: commentId
                }
            })
            .then(response => {
                this.postComments = this.postComments.filter(comment => comment.id != commentId);
            })
            .catch(error => console.log(error));
        },
        reportComment(commentId){
            axios.post("http://localhost:8000/api/comment-report", {
                    commentID: commentId,
                }
            )
            .then(response => {
                this.newReport++;
                this.msg = "Uspešno ste prijavili komentar."
            })
            .catch(error => console.log(error));
        },
        deletePost() {
            axios.delete("http://localhost:8000/api/post/" + this.post.id + "/delete")
            .then(response => {
                console.log(response)
                if(response.data.message == "success"){
                    if(this.signedUser.user_type == "administrator"){
                        window.location.href = "http://localhost:8000/reported-posts";
                    }else{
                        window.location.href = "http://localhost:8000/profile/" + this.signedUser.id;
                    }
                }
            })
            .catch(error => console.log(error));
        },
        reportPost() {
            axios.post("http://localhost:8000/api/post-report", {
                    postID: this.post.id,
                }
            )
            .then(response => {
                this.newReport++;
                this.msg = "Uspešno ste prijavili objavu."
            })
            .catch(error => console.log(error));
        }
    },
    mounted() {
        if(this.signedUser.id == this.post.user_id){
            this.canDelete = true;
            this.canReport = false;
        }
        if(this.signedUser.user_type == "administrator"){
            this.canDelete = true;
            this.canReport = false;
        }
        this.postComments = this.comments;
        axios.get("http://localhost:8000/api/post/" + this.post.id + "/stats/" + this.signedUser.id, {
            params: {
                postId: this.post.id
            }
        })
        .then(response => {
            this.numberOfLikes = response.data.numberOfLikes;
            this.isLiked = response.data.liked;
            this.isSaved = response.data.isSaved;
        })
        .catch(error => console.log(error));
        // Listening for nitifications
        this.numberOfNewNotifications = this.newNotifs;
        Echo.channel("notifications")
        .listen("NewNotification", (e) => {
            if(e.notification.user_id == this.signedUser.id){
              this.numberOfNewNotifications++;
            }
        })
    }
}

</script>

<template>

<div class="app-container bg-lightgray">

    <ReportMessage :msg="msg" :key="loadReport"/>

    <Sidebarextended :signedUserId="signedUser.id" :newNotif="numberOfNewNotifications"/>

    <div class="post-container">
        
        <div class="post-image">
            <img :src="'/images/' + user.email + '/posts/' + post.image_name" alt="Post image">
        </div>

        <div class="post-info">
            <div class="post-header">
                <div class="post-header-top">
                    <a :href="'/profile/' + user.id" class="user-posted">
                    <div class="image-div">
                        <img :src="'/images/' + user.email + '/' + user.image_name" alt="User">
                    </div>
                    <p>{{ user.name }}</p>
                    </a>
                    <button v-if="canReport" class="report-btn" @click="reportPost"><i class="fa-solid fa-circle-exclamation"></i></button>
                    <button @click="deletePost" v-if="canDelete" class="report-btn"><i class="fa-solid fa-trash-can"></i></button>
                </div>
                <p class="post-description">{{ post.description }}</p>
            </div>
            <div class="comments">
                <Comment @deleteComment="deleteComment" @reportComment="reportComment" v-for="comment in postComments" :key="comment.id" :signedUser="signedUser" :comment="comment"/>
            </div>

            <div class="comment-form">
                <div class="buttons">
                    <button @click="likePost"><i v-if="!isLiked" class="fa-regular fa-heart"></i> <i v-if="isLiked" class="fa-solid fa-heart red"></i> {{ numberOfLikes }}</button>
                    <button @click="savePost"><i v-if="!isSaved" class="fa-solid fa-bookmark"></i><i v-if="isSaved" class="fa-solid fa-bookmark bookmarked"></i></button>
                </div>
                <div class="comment-input">
                    <input v-model="commentContent" type="text" name="comment-text" placeholder="Vas komentar...">
                    <button @click="postComment" :disabled="btnDisabled">Objavi</button>
                </div>
            </div>

        </div>
        
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

.bg-lightgray{
    background-color: var(--lightgray);
}

.post-container{
    background-color: white;
    height: 80vh;
    width: 110vh;
    margin-top: auto;
    margin-bottom: auto;
    display: flex;
    flex-wrap: wrap;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
}

.post-container .post-image{
    width: 50%;
    height: 100%;
}

.post-container .post-image img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.post-info{
    width: 50%;
}
.post-header{
    width: 100%;
    display: flex;
    flex-direction: column;
    padding: 1rem;
    border-bottom: 1px solid rgb(231, 231, 231);
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

.user-posted {
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    color: var(--secondary);
    font-weight: bold;
}

.report-btn{
    color: var(--lightred);
    background: none;
    border: none;
    font-size: 1.1rem;
    cursor: pointer;
}

.comment-form{
    padding: 1rem;
    border-top: 1px solid rgb(231, 231, 231);
}

.comment-form .buttons{
    display: flex;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.comment-form .buttons button{
    font-size: 1.5rem;
    background:none;
    border: none;
    cursor: pointer;
}

.comment-form input{
    padding: 0.3rem;
    border: 1px solid rgb(169, 169, 169);
    border-radius: 5px;
    width: 80%;
}

.comment-input button{
    background-color: var(--primary);
    padding: 0.3rem 0.5rem;
    color: white;
    border: 1px solid var(--primary);
    border-radius: 5px;
    cursor: pointer;
    margin-left: 0.5rem;
}

.comment-input button:disabled{
    background-color: #bcc2ff;
    border: 1px solid #bcc2ff;
    cursor: default;
}

.comments{
    padding: 1rem 0rem;
    overflow-y: auto;
    width: 100%;
    height: 55vh;
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
}

.red{
    color: var(--lightred);
}

.bookmarked{
    color: var(--bookmarked);
}
.post-header-top{
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.post-description{
    margin-left: 3.2rem;
    text-overflow: ellipsis;
    overflow: hidden; 
    width: 100%; 
    white-space: nowrap;
}

</style>

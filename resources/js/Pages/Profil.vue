<script>
import Sidebarextended from '../Components/Sidebarextended.vue';
import Profileposts from '../Components/Profileposts.vue';
import Savedposts from "../Components/Savedposts.vue";
import Modal from "../Components/Modal.vue";
import ReportMessage from '../Components/ReportMessage.vue';

export default{
    data() {
        return {
            activePosts: true,
            activeSaved: false,
            followingUser: false,
            showFollowingUser: true,
            followText: 'Zaprati',
            showSavedBtn: false,
            personalPostsToggled: true,
            savedPostsToggled: false,
            numberOfNewNotifications: 0,
            toggledFollowers: false,
            toggledFollowed:false,
            modalToggled: false,
            numberOfNewMessages: 0,
        }
    },
    components: {
        Profileposts, Sidebarextended, Savedposts, Modal, ReportMessage
    },
    methods: {
        toggleFollowers(){
            this.modalToggled = true;
            this.toggledFollowers = true;
            this.toggledFollowed = false;
        },
        toggleFollowed(){
            this.modalToggled = true;
            this.toggledFollowers = false;
            this.toggledFollowed = true;
        },
        closeModal(){
            this.modalToggled = false;
            this.toggledFollowers = false;
            this.toggledFollowed = false;
        },
        togglePosts(){
            this.activePosts = true
            this.activeSaved = false
        },
        toggleSaved(){
            this.activePosts = false
            this.activeSaved = true
        },
        followUser(){
            this.followingUser = !this.followingUser
            if(this.followText == "Zaprati"){
                axios.get("http://localhost:8000/api/follow",{
                    params: {
                        friendIdReq: this.user.id,
                        signedUserId: this.signedUserId
                    }
                })
                .then(response => console.log(response))
                .catch(error => console.log(error));;
                this.followText = "Otprati"
            }else{
                axios.delete("http://localhost:8000/api/unfollow",{
                    params: {
                        friendIdReq: this.user.id,
                        signedUserId: this.signedUserId
                    }
                })
                .then(response => console.log(response.data))
                .catch(error => console.log(error));;
                this.followText = "Zaprati"
            }
        },
    },
    computed: {
        isOwnerModal(){
            return this.user.id == this.signedUserId; 
        }
    },
    props: [
        'signedUserId',
        'user',
        'numberOfPosts',
        'numberOfFollowers',
        'numberOfFollowed',
        'followers',
        'followed',
        'posts',
        'savedPosts',
        'newNotifs',
        'newMsgCount',
        'msg',
    ],
    mounted(){
        if(this.user.id == this.signedUserId){
            this.showFollowingUser = false
            this.showSavedBtn = true;
        }
        axios("http://localhost:8000/api/is-following", {
            params: {
                singedUserId: this.signedUserId,
                friendId: this.user.id
            }
        })
        .then(response => {
            if(response.data.message == "true"){
                this.followText = "Otprati";
                this.followingUser = true;
            }
            if(response.data.message == "false"){
                this.followText = "Zaprati";
                this.followingUser = false;
            }
        })
        .catch(error => console.log(error));
        // Listening for nitifications
        this.numberOfNewNotifications = this.newNotifs;
        this.numberOfNewMessages = this.newMsgCount;
        const notificationsListener = Echo.channel("notifications");
        notificationsListener.listen("NewNotification", (e) => {
            if(e.notification.user_id == this.user.id){
              this.numberOfNewNotifications++;
            }
        });
        const messagesListener = Echo.channel("chat");
        messagesListener.listen("NewMessage", (e) => {
        if(e.message.receiver_id == this.user.id){
          this.numberOfNewMessages++;
        }
        });
    }
}

</script>

<template>

<div class="app-container">

<ReportMessage :msg="msg" />

<Modal v-if="modalToggled && isOwnerModal" :title="toggledFollowed ? 'Vi pratite' : 'Vaši pratioci'" :signedUser="user" :users="toggledFollowed ? followed : followers" @closeModal="closeModal"/>
<Modal v-else-if="modalToggled" :title="toggledFollowed ? 'Praćenja' : 'Pratioci'" :signedUser="user" :users="toggledFollowed ? followed : followers" @closeModal="closeModal"/>


<Sidebarextended :signedUserId="signedUserId" :newNotif="numberOfNewNotifications" :newMsg="numberOfNewMessages"/>

    <div class="middle-container">
    <div class="profile-container">

        <div class="profile-info">
                <div class="profile-image">
                    <img :src="'/images/' + user.email + '/' + user.image_name">
                </div>
                <div class="profile-stats">
                    <div class="name">
                        <p class="user-name">{{ user.name }}</p>
                        <button v-if="showFollowingUser" class="follow-btn" :class="{active: followingUser}" @click="followUser">{{ followText }}</button>
                    </div>
                    <div class="profile-details">
                        <div class="posts-toggle-btn">
                            <p class="number">{{ numberOfPosts }}</p>
                            <p class="stat-name">Objava</p>
                        </div>
                        <button @click="toggleFollowed" class="stat">
                            <p class="number">{{ numberOfFollowed }}</p>
                            <p class="stat-name">Praćenja</p>
                        </button>
                        <button @click="toggleFollowers" class="stat">
                            <p class="number">{{ numberOfFollowers }}</p>
                            <p class="stat-name">Pratilaca</p>
                        </button>
                    </div>
                </div>    
        </div>

        <div class="togglers">
            <button @click="togglePosts" :class="[activePosts ? 'toggler-active' : '', 'toggler']"><i class="fa-solid fa-layer-group"></i> Objave</button>
            <button v-if="showSavedBtn" @click="toggleSaved" :class="[activeSaved ? 'toggler-active' : '', 'toggler']"><i class="fa-solid fa-bookmark"></i> Sačuvano</button>
        </div>

        <Profileposts v-if="activePosts" :posts="posts" :userEmail="user.email"/>
        <Savedposts v-if="activeSaved" :posts="savedPosts"/>

    </div>
    </div>
</div>

</template>

<style>

:root{
  --primary: #5664f9;
  --secondary: #1e1e1e;
  --lightgray: #f5f5f5;
  --lightred: #fa3678;
}

.profile-container{
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.profile-info{
    display: flex;
    justify-content: center;
    gap: 5rem;
    border-bottom: 2px solid rgb(221, 221, 221);
    height: max-content;
    width: 70%;
    padding-bottom: 2rem;
}

.profile-image{
    width: 10rem;
    height: 10rem;
}

.profile-image img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.name{
    display: flex;
    gap: 1rem;
    align-items: center;
}

.name p{
    font-size: 1.5rem;
}

.profile-details{
    margin-top: 2rem;
    display: flex;
    gap: 3rem;
}

.profile-details .stat{
    background: none;
    border: none;
    cursor: pointer;
}

.posts-toggle-btn{
    display: flex;
    flex-direction: column;
    align-items: center;
}

.posts-toggle-btn .number{
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.profile-details .stat .number{
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.toggler{
    background: none;
    padding: 1rem;
    font-size: 1.2rem;
    border-top: 1px solid rgb(221, 221, 221);
    border-bottom: none;
    border-right: none;
    border-left: none;
    color: rgb(221, 221, 221);
    cursor: pointer;
}

.toggler-active{
    background: none;
    padding: 1rem;
    font-size: 1.2rem;
    border-top: 1px solid var(--secondary);
    border-bottom: none;
    border-right: none;
    border-left: none;
    color: var(--secondary);
    cursor: pointer;
}

.profile-link{
    text-decoration: none;
    background-color: white;
    border: 1px solid var(--secondary);
    color: var(--secondary);
    padding: 5px 15px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

</style>
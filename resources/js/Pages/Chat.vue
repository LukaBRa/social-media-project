<script>
import Sidebarextended from '../Components/Sidebarextended.vue'
import Chatfriend from '../Components/Chatfriend.vue'
import ChatComponent from '../Components/ChatComponent.vue'

export default{
    data() {
        return {
            numberOfNewNotifications: 0,
            chatToggled: false,
            currentChatFriend: Object,
            reloadFriends: 0,
            numberOfNewMessages: 0,
        }
    },
    props: [
        'user',
        'friends',
        'newNotifs',
    ],
    components: {
        Sidebarextended, Chatfriend, ChatComponent
    },
    methods: {
        toggleChat(friend){
            this.currentChatFriend = friend;
            this.chatToggled = true;
            ++this.reloadFriends;
        }
    },
    computed: {
        reload(){
            return this.reloadFriends;
        }
    },
    mounted() {
        this.numberOfNewNotifications = this.newNotifs;
        const notificationsListener = Echo.channel("notifications");
        notificationsListener.listen("NewNotification", (e) => {
            if(e.notification.user_id == this.user.id){
              this.numberOfNewNotifications++;
            }
        });
    }
}

</script>

<template>

<div class="app-container bg-lightgray">

    <Sidebarextended :signedUserId="user.id" :newNotif="numberOfNewNotifications" :newMsg="numberOfNewMessages"/>

    <div class="chat-container">
        
        <div class="friends" :key="reload">
            <h2>Vasi prijatelji</h2>
            <div class="friends-container">
                <Chatfriend v-for="friend in friends" :key="friend.id" :friend="friend" :signedUser="user" :currentToggled="currentChatFriend" @toggleChat="toggleChat"/>
            </div>
        </div>

        <ChatComponent v-if="chatToggled" :key="currentChatFriend.id" :friend="currentChatFriend" :signedUser="user"/>
        <div v-else class="no-chat">
            <i class="fa-regular fa-paper-plane"></i>
            <h3>Posaljite poruku Vasim prijateljima</h3>
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
  --bookmarked: #fec604;
}

.bg-lightgray{
    background-color: var(--lightgray);
}

.chat-container{
    background-color: white;
    display: flex;
    height: 85vh;
    margin-top: 2rem;
    width: 50rem;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
}

.friends{
    width: 50%;
    height: 100%;
    overflow: auto;
}

.chat{
    width: 50%;
    height: 100%;
    border-left: 1px solid rgb(236, 236, 236);
}

.friends h2{
    text-align: center;
    padding: 1.46rem 0rem;
    border-bottom: 1px solid rgb(236, 236, 236);
    color: var(--secondary);
}

.friends-container{
    height: 87%;
    overflow: auto;
}

.chat-header{
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-bottom: 1px solid rgb(236, 236, 236);
}

.chat-header p{
    font-size: 1.1rem;
    margin-left: 0.5rem;
    font-weight: bold;
}

.friend-image{
    width: 60px;
    height: 60px;
}

.friend-image img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.messages{
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
    width: 100%;
    height: 80%;
    overflow: auto;
}

.send-form-container{
    height: 8.2%;
    display: flex;
    align-items: center;
    padding: 0rem 0.5rem;
    gap: 0.5rem;
}

.send-form-container input{
    padding: 0.4rem;
    width: 90%;
    border-radius: 5px;
    border: 1px solid rgb(194, 194, 194);
}

.send-form-container button{
    background-color: var(--primary);
    color: white;
    padding: 0.4rem;
    cursor: pointer;
    border: 1px solid var(--primary);
    border-radius: 50%;
}

.no-chat{
    border-left: 1px solid rgb(236, 236, 236);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 50%;
}

.no-chat i {
    color: var(--primary);
    font-size: 5rem;
    margin-bottom: 2rem;
    border: 3px solid var(--primary);
    padding: 2rem 2rem;
    border-radius: 50%;
}

.no-chat h3{
    color: rgb(194, 194, 194);
}

</style>
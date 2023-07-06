<script>
import axios from 'axios';
import Message from '../Components/Message.vue';


export default {
    data(){
        return{
            messageText: '',
            messages: [],
            showChat: false,
        }
    },
    components: {
        Message
    },
    methods: {
        sendMessage(){
            axios.post("http://localhost:8000/api/send-message", {
                    senderId: this.signedUser.id,
                    receiverId: this.friend.id,
                    message: this.messageText,
            })
            .then(response => {
                this.messageText = '';
                this.messages.push(response.data);
                this.scrollChatToBottom();
            })
            .catch(error => console.log(error))
        },
        scrollChatToBottom(){
            this.$nextTick(() => {
                const chatContainer = this.$refs.chatContainer;
                chatContainer.scrollTop = chatContainer.scrollHeight;
            })
        }
    },
    props: [
        'friend',
        'signedUser',
    ],
    mounted(){
        axios.get("http://localhost:8000/api/is-both-following", {
            params: {
                singedUserId: this.signedUser.id,
                friendId: this.friend.id
            }
        })
        .then(response => {
            if(response.data.message == 'true'){
                this.showChat = true
            }
        })
        axios.get("http://localhost:8000/api/get-messages", {
        params: {
            friendId: this.friend.id,
            signedUserId: this.signedUser.id
        }
        })
        .then(response => {
            this.messages = [...response.data];
            this.scrollChatToBottom();
        })
        .catch(error => console.log(error));
        axios.get("http://localhost:8000/api/mark-all-read", {
            params: {
                friendId: this.friend.id,
                signedUserId: this.signedUser.id
            }
        })
        .then(response => {
            //
        })
        .catch(error => console.log(error));
        Echo.channel('chat')
            .listen('NewMessage', (e) => {
                if(e.message.receiver_id == this.signedUser.id && e.message.sender_id == this.friend.id){
                    this.messages.push(e.message);
                    axios.get("http://localhost:8000/api/mark-read", {
                        params: {
                            messageId: e.message.id
                        }
                    })
                    .then(response => {
                        this.scrollChatToBottom();
                    })
                    .catch(error => console.log(error));
                }
            });
    }
}

</script>

<template>

<div v-if="showChat" class="chat">
        <div class="chat-header">
            <div class="friend-image">
                <img :src="'/images/' + friend.email + '/' + friend.image_name" alt="Friend image">
            </div>
            <p>{{ friend.name }}</p>
        </div>
        <div class="messages" ref="chatContainer">
            <Message v-for="m in messages" :senderId="m.sender_id" :signedUser="signedUser" :message="m.message"/>
        </div>
        <div class="send-form-container">
            <input v-model="messageText" type="text" placeholder="Poruka...">
            <button @click="sendMessage"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
</div>

<div v-else class="error-div">
    <i class="fa-solid fa-users"></i>
    <h3>Korisnik mora uzvratiti pracenje</h3>
</div>

</template>

<style>

:root{
  --primary: #5664f9;
  --secondary: #1e1e1e;
  --lightgray: #f5f5f5;
  --lightred: #fa3678;
}

.chat{
    width: 50%;
    height: 100%;
    border-left: 1px solid rgb(236, 236, 236);
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
    gap: 00.5rem;
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

.error-div{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-left: 1px solid rgb(236, 236, 236);
    width: 50%;
    gap: 2rem;
}

.error-div h3{
    color: rgb(191, 191, 191);
}

.error-div i{
    color: var(--primary);
    font-size: 5rem;
    border: 3px solid var(--primary);
    border-radius: 50%;
    padding: 2.5rem 2rem;
}

</style>
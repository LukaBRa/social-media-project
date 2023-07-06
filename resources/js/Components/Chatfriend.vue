<script>

export default{
    data() {
        return {
            lastMessage: '',
            numberOfUnreadMessages: 0,
            showBorder: false,
        }
    },
    props: [
        'signedUser',
        'friend',
        'currentToggled'
    ],
    mounted(){
        if(this.currentToggled == null || this.currentToggled.id != this.friend.id){
            axios.get('http://localhost:8000/api/get-unread-count-single', {
            params: {
                signedUserId: this.signedUser.id,
                friendId: this.friend.id,
            }
            })
            .then(response => {
                this.numberOfUnreadMessages = response.data
            })
            .catch(error => console.log(error));
            Echo.channel('chat')
                .listen('NewMessage', (e) => {
                    if(e.message.sender_id == this.friend.id && e.message.receiver_id == this.signedUser.id){
                        this.numberOfUnreadMessages++;
                        this.lastMessage = e.message.message;
                    }
                });
        }else{
            this.numberOfUnreadMessages = 0;
            this.showBorder = true;
        }
        axios.get('http://localhost:8000/api/get-last-message', {
            params: {
                signedUserId: this.signedUser.id,
                friendId: this.friend.id,
            }
        })
        .then(response => {
            this.lastMessage = response.data.message
        })
        .catch(error => console.log(error));
    },
    computed: {
        showUnreadMessages() {
            return this.numberOfUnreadMessages > 0
        }
    },
    methods: {
        openChat(){
            this.$emit('toggleChat', this.friend);
        }
    }
}

</script>

<template>

<button @click="openChat" class="chat-friend" :class="{'toggled-friend' : showBorder}">

    <div class="friend">
        <div class="friend-image">
            <img :src="'/images/' + friend.email + '/' + friend.image_name" alt="">
        </div>
        
        <div class="friend-data">
            <p class="friend-name">{{ friend.name }}</p>
            <p class="last-message">{{ lastMessage }}</p>
        </div>
    </div>

    <div v-if="showUnreadMessages" class="new-messages">{{ numberOfUnreadMessages }}</div>

</button>

</template>

<style scoped>

:root{
  --primary: #5664f9;
  --secondary: #1e1e1e;
  --lightgray: #f5f5f5;
  --lightred: #fa3678;
}

.chat-friend{
    border: none;
    padding: 0.7rem 1rem;
    background-color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    transition: all 0.3s ease;
    cursor: pointer;
}

.chat-friend:hover{
    background-color: var(--lightgray);
}

.friend{
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.friend-image{
    width: 50px;
    height: 50px;
}

.friend-image img{
    border-radius: 50%;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.friend-data{
    text-align: left;
    color: var(--secondary);
}

.friend-name{
    font-weight: bold;
    font-size: 1.1rem;
    margin-bottom: 0.3rem;
}

.last-message{
    text-overflow: ellipsis;
    overflow: hidden; 
    width: 250px; 
    white-space: nowrap;
    color: rgb(108, 107, 107);
}
.new-messages{
    background-color: var(--lightred);
    padding: 0.2rem 0.4rem;
    color: white;
    border-radius: 50%;
}

.toggled-friend{
    border-left: 3px solid var(--primary);
}

</style>
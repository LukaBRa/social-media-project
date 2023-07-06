<script>

export default {
    data(){ 
        return {
            post: Object,
            userSender: Object,
            userReceiver: Object,
        }
    },
    props: [
        'notification'
    ],
    mounted() {
        // Getting user sender
        console.log(this.notification)
        axios.get('api/get-user', {
            params: {
                userId: this.notification.sender_id
            }
        })
        .then(response => {
            console.log(response)
            this.userSender = response.data
        })
        .catch(error => console.log(error));
        // Getting user receiver
        axios.get('api/get-user', {
            params: {
                userId: this.notification.user_id
            }
        })
        .then(response => {
            console.log(response)
            this.userReceiver = response.data
        })
        .catch(error => console.log(error));
        //Getting post
        if(this.notification.post_id != "nema"){
            axios.get('api/post/' + this.notification.post_id + '/getPost')
            .then(response => {
                console.log(response)
                this.post = response.data
            })
            .catch(error => console.log(error));
        }
    }
}

</script>

<template>

<a v-if="notification.post_id != 'nema'" :href="'/post/' + notification.post_id" class="notification">
    <div class="message">
        <div class="user-image">
            <img :src="'/images/' + userSender.email + '/' + userSender.image_name" alt="User profile image">
        </div>
        <p class="user-name">{{ userSender.name }}</p>
        <p class="notification-message">{{ notification.notification }}</p>
    </div>
    <div class="post">
        <img v-if="notification.post_id != 'nema'" :src="'/images/' + userReceiver.email + '/posts' + '/' + post.image_name" alt="">
    </div>
</a>

<a v-else :href="'/profile/' + userReceiver.id" class="notification">
    <div class="message">
        <div class="user-image">
            <img :src="'/images/' + userSender.email + '/' + userSender.image_name" alt="User profile image">
        </div>
        <p class="user-name">{{ userSender.name }}</p>
        <p class="notification-message">{{ notification.notification }}</p>
    </div>
    <div class="post">
        <img v-if="notification.post_id != 'nema'" :src="'/images/' + userReceiver.email + '/posts' + '/' + post.image_name" alt="">
    </div>
</a>

</template>


<style>

:root{
  --primary: #5664f9;
  --secondary: #1e1e1e;
  --lightgray: #f5f5f5;
  --lightred: #fa3678;
}

.notification{
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-decoration: none;
    color: var(--secondary);
    transition: all 0.3s ease;
}

.notification:hover{
    background-color: var(--lightgray);
}

.user-image{
    width: 40px;
    height: 40px;
    margin-right: 0.5rem;
}

.user-image img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.message {
    display: flex;
    align-items: center;
}

.user-name{
    font-weight: bold;
    margin-right: 0.5rem;
}

.post{
    width: 60px;
    height: 70px;
}

.post img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

</style>
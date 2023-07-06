<script>
import axios from 'axios'


export default{
    data(){
        return {
            text: "Zaprati",
            following: false,
        }
    },
    methods: {
        followUser(){
            this.following = !this.following
            if(this.text == "Zaprati"){
                axios.get("api/follow",{
                    params: {
                        friendIdReq: this.user.id,
                        signedUserId: this.signedUser.id
                    }
                })
                .then(response => console.log(response))
                .catch(error => console.log(error));;
                this.text = "Otprati"
            }else{
                axios.delete("api/unfollow",{
                    params: {
                        friendIdReq: this.user.id,
                        signedUserId: this.signedUser.id
                    }
                })
                .then(response => console.log(response.data))
                .catch(error => console.log(error));;
                this.text = "Zaprati"
            }
        }
    },
    props: [
        'user',
        'signedUser'
    ],
    mounted() {
        axios("api/is-following", {
            params: {
                singedUserId: this.signedUser.id,
                friendId: this.user.id
            }
        })
        .then(response => {
            if(response.data.message == "true"){
                this.text = "Otprati";
                this.following = true;
            }
            if(response.data.message == "false"){
                this.text = "Zaprati";
                this.following = false;
            }
        })
        .catch(error => console.log(error));
    }
}

</script>

<template>

<div class="suggested-friend">
    <a :href="'/profile/' + user.id" class="friend-info">
        <div class="img"><img :src="'/images/' + user.email + '/' + user.image_name" alt="Suggested friend"></div>
        <p>{{ user.name }}</p>
    </a>
    <button class="follow-btn" :class="{active: following}" @click="followUser">{{ text }}</button>
</div>

</template>

<style>

:root{
  --primary: #5664f9;
  --secondary: #1e1e1e;
  --lightgray: #f5f5f5;
  --lightred: #fa3678;
}

.suggested-friend{
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.suggested-friend a .img{
    width: 40px;
    height: 40px;
}

.suggested-friend a .img img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.friend-info{
    display: flex;
    align-items: center;
    gap: 00.5rem;
    text-decoration: none;
    color: var(--secondary);
}

.friend-info p{
    font-weight: bold;
}

.friend-info img{
    border-radius: 50%;
}

</style>
<script>

export default{
    data(){
        return {
            text: "Zaprati",
            following: false
        }
    },
    methods: {
        followUser(){
            this.following = !this.following
            if(this.text == "Zaprati"){
                axios.get("http://localhost:8000/api/follow",{
                    params: {
                        friendIdReq: this.user.id,
                        signedUserId: this.signedUser.id
                    }
                })
                .then(response => console.log(response))
                .catch(error => console.log(error));;
                this.text = "Otprati"
            }else{
                axios.delete("http://localhost:8000/api/unfollow",{
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
        'signedUser',
    ],
    mounted() {
        axios("http://localhost:8000/api/is-following", {
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
        <div class="friend-image">
            <img :src="'/images/' + user.email + '/' + user.image_name" alt="Follower">
        </div>
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

.friend-info{
    display: flex;
    align-items: center;
    gap: 00.5rem;
    text-decoration: none;
    color: var(--secondary);
}

.friend-image{
    width: 40px;
    height: 40px;
}

.friend-image img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.friend-info p{
    font-weight: bold;
}

.friend-info img{
    border-radius: 50%;
}

</style>
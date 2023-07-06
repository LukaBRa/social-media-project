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
                axios.get("api/follow",{
                    params: {
                        friendIdReq: this.friend.id,
                        signedUserId: this.user
                    }
                })
                .then(response => console.log(response))
                .catch(error => console.log(error));;
                this.text = "Otprati"
            }else{
                axios.delete("api/unfollow",{
                    params: {
                        friendIdReq: this.friend.id,
                        signedUserId: this.user
                    }
                })
                .then(response => console.log(response.data))
                .catch(error => console.log(error));;
                this.text = "Zaprati"
            }
        }
    },
    props: [
        'friend',
        'user',
    ],
    mounted() {
        axios("api/is-following", {
            params: {
                singedUserId: this.user,
                friendId: this.friend.id
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
    <a :href="'/profile/' + friend.id" class="friend-info">
        <div class="friend-image">
            <img :src="'/images/' + friend.email + '/' + friend.image_name" alt="Suggested friend">
        </div>
        <p>{{ friend.name }}</p>
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
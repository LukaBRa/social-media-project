<script>
import Suggestedfriend from "./Suggestedfriend.vue"
import Recentlysavedpost from './Recentlysavedpost.vue'

export default{
    components: {
        Suggestedfriend, Recentlysavedpost
    },
    props: [
        'suggested',
        'signedUserId',
        'newNotifCount',
        'newMsgCount',
        'lastSaved'
    ],
    computed: {
        showNotif(){
            return this.newNotifCount > 0
        },
        showMsg(){
            return this.newMsgCount > 0
        }
    },
}

</script>

<template>

<div class="rightbar">
    <div class="buttons">
        <a href="/search"><i class="fa-solid fa-magnifying-glass"></i></a>
        <a href="/notifications" class="notifications"><i class="fa-solid fa-bell"></i> <span v-if="showNotif">{{ newNotifCount }}</span></a>
        <a href="/chat" class="notifications"><i class="fa-solid fa-envelope"></i> <span v-if="showMsg">{{ newMsgCount }}</span></a>
    </div>

    <div class="suggestions">
        <h3>Preporučeni</h3>
        <div class="suggested-friends">
            <Suggestedfriend v-for="suggestedFriend in suggested" :key="suggestedFriend.id" :friend="suggestedFriend" :user="signedUserId"/>
        </div>
    </div>

    <div class="recently-saved">
        <div class="title">
            <h3>Poslednje sačuvano</h3>
            <a :href="'/profile/' + signedUserId">Više...</a>
        </div>
        <div class="recently-saved-posts">
            <Recentlysavedpost v-for="post in lastSaved" :key="post.post_id" :savedPost="post"/>
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
}

.rightbar{
    background-color: var(--lightgray);
    padding: 2rem;
    width: 25%;
}

.buttons{
    margin-bottom: 2rem;
    display: flex;
    justify-content: center;
    gap: 1.1rem;
}

.buttons a{
    cursor: pointer;
    background-color: white;
    padding: 0.7rem;
    border-radius: 50%;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    color: var(--secondary);
    text-decoration: none;
}

.suggested-friends{
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.norifications{
    position: relative;
}
.notifications span{
    position:absolute;
    top: 30px;
    background-color: var(--lightred);
    color: white;
    border-radius: 50%;
    padding: 3px 5px;
    font-size: 12px;
}

.recently-saved{
    margin-top: 2rem;
}

.recently-saved .title{
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.recently-saved .title a{
    color: var(--primary);
    text-decoration: none;
}

.recently-saved-posts{
    margin-top: 2rem;
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.recently-saved-posts .saved{
    width: 130px;
    height: 130px;
}

.recently-saved-posts .saved img{
    width:100%; 
    height:100%;
    object-fit: cover;
}

</style>
<script>
import Sidebarextended from '../Components/Sidebarextended.vue';
import Suggesteduser from '../Components/Suggesteduser.vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

export default{
    data() {
        return {
            suggestedUsers: null,
            searchText: '',
            numberOfNewNotifications: 0,
            numberOfNewMessages: 0,
        }
    },  
    components: {
        Sidebarextended, Suggesteduser
    },
    props: [
        'user',
        'newNotifs',
        'newMsgCount'
    ],
    methods: {
        searchFriend() {
            if(this.searchText == ""){
                return;
            }
            axios.get("api/search-friend",{
                params: {
                    srcTxt: this.searchText,
                    signedUserId: this.user.id
                }
            })
            .then(response => this.suggestedUsers = response.data)
            .catch(error => console.log(error));
        }
    },
    mounted() {
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
};

</script>

<template>

<div class="app-container bg-lightgray">

<Sidebarextended :signedUserId="user.id" :newNotif="numberOfNewNotifications" :newMsg="numberOfNewMessages"/>

<div class="add-post-container">
    <h1>Pretraga</h1>
    <div class="search-div">
        <input @keyup="searchFriend" v-model="searchText" type="text" name="searchText" placeholder="Ime prijatelja...">
        <i class="fa-solid fa-magnifying-glass"></i>
    </div>

    <div class="suggested-friends">
        <Suggesteduser v-if="searchText != ''" v-for="suggestedUser in suggestedUsers" :key="suggestedUser.id" :user="suggestedUser" :signedUser="user"/>
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

.bg-lightgray{
  background-color: var(--lightgray);
}

.add-post-container{
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 2rem;
  padding: 2rem;
  width: 50%;
  background-color: white;
  border-radius: 10px;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
  height: 90vh;
  overflow: auto;
}

.search-div{
    border: 1px solid rgb(156, 156, 156);
    padding: 0.5rem;
    margin-top: 1rem;
    border-radius: 5px;
    width: 50%;
    display: flex;
    justify-content: space-between;
}
.search-div input{
    border: none;
    margin-right: 1rem;
    width: 85%;
}

.search-div input:focus{
    outline: none;
}

.search-div i{
    color: var(--primary);
}

.suggested-friends{
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    width: 50%;
    height: 100%;
    overflow: auto;
}

</style>
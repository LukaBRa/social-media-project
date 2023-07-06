<script>
import Sidebar from '../Components/Sidebar.vue';
import Rightbar from '../Components/Rightbar.vue';
import Posts from '../Components/Posts.vue';

export default{
  data() {
    return{
      numberOfNewNotifications: 0,
      numberOfNewMessages: 0,
    }
  },
  components: {
    Sidebar, Rightbar, Posts
  },
  props: [
    'user',
    'suggestedFriends',
    'posts',
    'newNotifCount',
    'newMsgCount',
    'lastSaved'
  ],
  mounted() {
    this.numberOfNewNotifications = this.newNotifCount;
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
<div class="app-container-space-between">

  <Sidebar :user="user"/>

  <div class="middle-container">
    <Posts :posts="posts" :signedUser="user"/>
  </div>

  <Rightbar :suggested="suggestedFriends" :signedUserId="user.id" :newNotifCount="numberOfNewNotifications" :newMsgCount="numberOfNewMessages" :lastSaved="lastSaved"/>

</div>
</template>

<style scoped>
</style>

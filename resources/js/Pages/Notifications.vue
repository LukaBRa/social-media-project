<script>
import Sidebarextended from "../Components/Sidebarextended.vue";
import Notification from "../Components/Notification.vue"; 

export default{
    data() {
        return{
            rNotifications: [],
            numberOfNewNotif: 0,
            numberOfNewMessages: 0,
        }
    },  
    components: {
        Sidebarextended, Notification
    },
    props: [
        'user',
        'notifications',
        'newMsgCount'
    ],
    mounted() {
        this.rNotifications = [...this.notifications];
        this.numberOfNewMessages = this.newMsgCount;
        axios.get("http://localhost:8000/api/mark-notifications", {
            params: {
            notificationId: null,
            userId: this.user.id
            }
        })
        .then(response => {
             // do nothing
        })
        .catch(error => console.log(error));
        const notificationListener= Echo.channel('notifications');
        notificationListener.listen('NewNotification', (e) => {
                if(e.notification.user_id == this.user.id){
                    this.rNotifications.push(e.notification);
                    axios.get("http://localhost:8000/api/mark-notifications", {
                        params: {
                            notificationId: e.notification.id,
                            userId: false
                        }
                    })
                    .then(response => {
                        // do nothing
                    })
                    .catch(error => console.log(error));
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

<div class="app-container bg-lightgray">

<Sidebarextended :signedUserId="user.id" :newNotif="numberOfNewNotif" :newMsg="numberOfNewMessages"/>

<div class="add-post-container">
    
    <h1>Obavestenja</h1>

    <div class="notifications">
        <Notification v-for="notification in rNotifications" :key="notification.id" :notification="notification"/>
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

h1{
    border-bottom: 1px solid lightgray;
    padding-bottom: 1rem;
    width: 100%;
    text-align: center;
}

.notifications{
    margin-top: 1rem;
    width: 100%;
    overflow: auto;
    height: 90vh;
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

</style>
<script>
import Sidebarextended from "../Components/Sidebarextended.vue"
import { useForm } from '@inertiajs/vue3'
import ReportMessage from '../Components/ReportMessage.vue';

export default {
    data() {
        return {
            numberOfNewNotifications: 0,
            numberOfNewMessages: 0,
        }
    },
    components: {
        Sidebarextended, ReportMessage
    },
    props: [
        'user',
        'errors',
        'message',
        'newNotifs',
        'newMsgCount',
        'msg',
    ],
    setup() {
        const form = useForm({
            name: '',
            password: '',
            profileImage: null,
        })

        return { form }
    },
    methods: {
        submitForm() {
            this.form.post("/user-update");
        }
    },
    mounted() {
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
        console.log(this.msg)
    }
}

</script>

<template>

<div class="app-container-fluid bg-lightgray">

    <ReportMessage :msg="msg" :key="msg"/>

<Sidebarextended :signedUserId="user.id" :newNotif="numberOfNewNotifications" :newMsg="numberOfNewMessages"/>

<div class="middle-container">

    <div class="settings-container">
        <div class="img-form">
                <div class="image-div">
                    <img :src="'/images/' + user.email + '/' + user.image_name" alt="User profile image">
                </div>
                <p class="name">{{ user.name }}</p>
        </div>
        <p class="success">{{ message }}</p>
        <form @submit.prevent="submitForm" class="user-data-form">
            <label for="profileimg">Promeni profilnu sliku</label>
            <input @input="form.profileImage = $event.target.files[0]" type="file" name="profileImg" id="profileimg">
            <p class="error-message">{{ errors.image }}</p>
            <label for="name">Ime</label>
            <input v-model="form.name" type="text" name="name" id="name" placeholder="Ime...">
            <p class="error-message">{{ errors.name }}</p>
            <label for="password">Lozinka</label>
            <input v-model="form.password" type="password" name="password" id="password" placeholder="Lozinka...">
            <p class="error-message">{{ errors.password }}</p>
            <input type="submit" value="Izmeni">
        </form>
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

.name{
    font-size: 2rem;
    font-weight: bold;
}
.middle-container{
    background-color: white;
    width: fit-content;
    height: fit-content;
    margin-top: 5rem;
    border-radius: 10px;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    margin-inline: auto;
}
.bg-lightgray{
  background-color: var(--lightgray);
}

.img-form{
    display: flex;
    gap: 2rem;
    align-items: center;
}

.img-form .image-div{
    width: 150px;
    height: 150px;
}
.img-form img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.user-data-form{
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 70%;
    margin-inline: auto;
    text-align: center;
}

.user-data-form label{
    font-size: 1.5rem;
}

.user-data-form input{
    padding: 0.2rem;
    margin-right: 0.5rem;
}

.user-data-form input[type="submit"]{
    padding: 0.3rem 0.8rem;
    background-color: var(--primary);
    border: 1px solid var(--primary);
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1.1rem;
}

.user-data-form input[type="text"]{
    padding: 0.4rem;
    border-radius: 5px;
    margin-top: 0.5rem;
    border: 1px solid rgb(174, 174, 174);
}

.user-data-form input[type="password"]{
    padding: 0.4rem;
    border-radius: 5px;
    margin-top: 0.5rem;
    border: 1px solid rgb(174, 174, 174);
}
.info-form{
    margin-top: 3rem;
    display: flex;
    flex-direction: column;
    width: 250px;
    gap: 1.1rem;
}

.info-form div{
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.info-form label{
    font-size: 1.4rem;
}

.info-form input{
    padding: 0.5rem;
    border: 1px solid rgb(158, 158, 158);
    border-radius: 5px;
}

.info-form input[type="submit"]{
    cursor: pointer;
    background-color: var(--primary);
    color: white;
    font-size: 1.1rem;
}

.error-message{
    color: rgb(255, 114, 114);
    font-style: italic;
}

.success{
    text-align: center;
    font-size: 1.2rem;
    margin-top: 1rem;
    color: rgb(0, 171, 0);
}

</style>
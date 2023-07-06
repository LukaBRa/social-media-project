<script>
import Sidebarextended from '../Components/Sidebarextended.vue';
import { useForm } from '@inertiajs/vue3';

export default{
    data() {
      return {
        numberOfNewNotifications: 0,
        numberOfNewMessages: 0,
      }
    },
    components: {
        Sidebarextended
    },
    props: [
      'errors',
      'signedUserId',
      'newNotifs',
      'newMsgCount'
    ],
    methods: {
      submitForm() {
        this.form.post("/addpost");
      }
    },
    setup() {
      const form = useForm({
        image: '',
        description: ''
      });

      return { form };
    },
    mounted(){
      // Listening for nitifications
        this.numberOfNewNotifications = this.newNotifs;
        this.numberOfNewMessages = this.newMsgCount;
        const notificationsListener = Echo.channel("notifications");
        notificationsListener.listen("NewNotification", (e) => {
            if(e.notification.user_id == this.signedUserId){
              this.numberOfNewNotifications++;
            }
        });
        const messagesListener = Echo.channel("chat");
        messagesListener.listen("NewMessage", (e) => {
        if(e.message.receiver_id == this.signedUserId){
          this.numberOfNewMessages++;
        }
        });
    }
};

</script>

<template>

<div class="app-container bg-lightgray">

<Sidebarextended :signedUserId="signedUserId" :newNotif="numberOfNewNotifications" :newMsg="numberOfNewMessages"/>

<div class="add-post-container">
    <h1>Dodaj objavu <i class="fa-solid fa-camera"></i></h1>
    <p v-if="success" class="success-message">{{ errors.success }}</p>
    <form @submit.prevent="submitForm">
      <label for="image">Odaberi sliku</label>
      <input @input="form.image = $event.target.files[0]" type="file" name="image" id="image">
      <p class="error-message">{{ errors.image }}</p>
      <label for="description">Dodajte opis</label>
      <textarea v-model="form.description" id="description" cols="30" rows="10" placeholder="Opis..."></textarea>
      <input type="submit" value="Objavi">
    </form>
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
  height: max-content;
  background-color: white;
  border-radius: 10px;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
}

form {
  margin-top: 2rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.fa-camera{
  color: var(--primary);
  margin-left: 1rem;
}

label{
  font-size: 1.2rem;
}

input[type="submit"]{
  background-color: var(--primary);
  border: 1px solid var(--primary);
  border-radius: 5px;
  color: white;
  padding: 0.5rem;
  font-size: 1.1rem;
  margin-top: 1rem;
  cursor: pointer;
}

.error-message{
    color: rgb(255, 114, 114);
    font-style: italic;
    text-align: center;
}

.success-message{
  background-color: rgb(167, 230, 167);
  color: green;
  border: 1px solid green;
  border-radius: 5px;
}

textarea{
  padding: 0.5rem;
  resize: none;
  border: 1px solid rgb(201, 201, 201);
  border-radius: 5px;
}

</style>
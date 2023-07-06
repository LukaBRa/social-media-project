<script>
import Modaluser from '../Components/Modaluser.vue';

export default {
    components: {
        Modaluser
    },
    props: [
        'users',
        'title',
        'signedUser'
    ],
}

</script>

<template>

<div class="modal-container">
    <div class="modal">
        <button @click="$emit('closeModal')" class="close-modal"><i class="fa-solid fa-xmark"></i></button>
        <h1>{{ title }}</h1>
        <div class="users">
            <Modaluser v-for="user in users" :key="user.id" :user="user" :signedUser="signedUser"/>
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


.modal-container{
    position: absolute;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
}

.modal{
    margin-inline: auto;
    margin-top: 2rem;
    background-color: white;
    width: 400px;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    height: 90vh;
    overflow: auto;
}

.modal h1{
    text-align: center;
    border-bottom: 1px solid rgb(231, 231, 231);
    padding-bottom: 1rem;
}

.close-modal{
    background: none;
    border: none;
    color: var(--lightred);
    align-self: flex-end;
    cursor: pointer;
    font-size: 1.2rem;
}

.users{
    margin-top: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

</style>
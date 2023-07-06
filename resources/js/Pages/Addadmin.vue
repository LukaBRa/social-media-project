<script>
import Dashboardmenu from '../Components/Dashboardmenu.vue'
import { useForm } from '@inertiajs/vue3'

export default{
    components: {
        Dashboardmenu
    },
    props: [
        'errors',
        'admin'
    ],
    setup(){
        const form = useForm({
            name: '',
            email: '',
            password: '',
        });

        return { form };
    },
    methods: {
        submitForm() {
            this.form.post('/addadmin');
        }
    }
}

</script>

<template>

<div class="app-container-fluid bg-lightgray">

<Dashboardmenu :admin="admin"/>

<div class="middle-container">

    <div class="settings-container">
        <h1>Dodaj novog administratora</h1>
        <form @submit.prevent="submitForm" class="user-data-form">
            <div>
                <label for="name">Ime</label>
                <input v-model="form.name" type="text" name="name" id="name" placeholder="Ime...">
                <p class="error-message">{{ errors.name }}</p>
            </div>
            <div>
                <label for="email">Email</label>
                <input v-model="form.email" type="text" name="email" id="email" placeholder="Email adresa...">
                <p class="error-message">{{ errors.email }}</p>
            </div>
            <div>
                <label for="password">Lozinka</label>
                <input v-model="form.password" type="password" name="password" id="password" placeholder="Lozinka...">
                <p class="error-message">{{ errors.password }}</p>
            </div>
            <input type="submit" value="Dodaj">
        </form>
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

.middle-container{
    background-color: white;
    width: fit-content;
    height: fit-content;
    margin-top: 5rem;
    border-radius: 10px;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    margin-inline: auto;
}

.error-message{
    color: rgb(255, 114, 114);
    font-style: italic;
}

form{
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    margin-top: 3rem;
    width: 100%;
}

form div{
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    width: 100%;
}

form div label{
    font-size: 1.3rem;
}

form div input{
    padding: 0.4rem;
    width: 60%;
    border: 1px solid rgb(177, 177, 177);
    border-radius: 5px;
}

form input[type="submit"]{
    background-color: var(--primary);
    width: 60%;
    color: white;
    border: 1px solid var(--primary);
    border-radius: 5px;
    cursor: pointer;
    padding: 0.4rem;
    font-size: 1.1rem;
    margin-top: 1rem;
}

</style>
<script>
import { useForm } from '@inertiajs/vue3';
import ReportMessage from '../Components/ReportMessage.vue'

export default{
    data() {
        return {
            reportMessage: '',
        }
    },
    components: {
        ReportMessage
    },
    setup(){
        const form = useForm({
            email: '',
            password: '',
        });

        return { form }
    },
    methods: {
        submitForm(){ 
            this.form.post('/login');
        }
    },
    props:{
        errors: Object
    },
    mounted(){
        if(this.errors.success){
            this.reportMessage = this.errors.success;
        }
    },
    computed: {
        showMessage() {
            return this.reportMessage != ''
        }
    }
}

</script>

<template>

<ReportMessage v-if="showMessage" :msg="reportMessage" :key="reportMessage" />

<div class="login-container">


    <div class="login-form">
        <h2><i class="fa-solid fa-hippo"></i></h2>
        <p class="error-message">{{ errors.msg }}</p>
        <form @submit.prevent="submitForm">
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
            <input type="submit" value="Prijavi se">
        </form>
        <p class="form-link">Nemate nalog? <a href="/register">Registrujte se</a></p>
        <p class="form-link"><a href="/reset-password">Zaboravili ste lozinku?</a></p>
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

.login-container{
    background-color: var(--lightgray);
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-form{
    background-color: white;
    width: max-content;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
}

.login-form h2{
    text-align: center;
    color: var(--primary);
    font-size: 5rem;
}

.login-form form{
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.login-form form div{
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.login-form form div label{
    font-size: 1.3rem;
    color: var(--secondary);
    text-align: center;
}

.login-form form input{
    width: 15rem;
    padding: 0.5rem;
    border-radius: 5px;
}

.login-form form input[type="text"], input[type="password"]{
    border: 1px solid rgb(168, 168, 168);
}

.login-form form input[type="submit"]{
    background-color: white;
    border: 1px solid var(--secondary);
    color: var(--secondary);
    font-size: 1.1rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.login-form form input[type="submit"]:hover{
    background-color: var(--primary);
    border: 1px solid var(--primary);
    color: white;
}

.form-link{
    text-align: center;
    margin-top: 1rem;
}

.form-link a{
    color: var(--secondary);
    font-weight: bold;
}

.error-message{
    color: rgb(255, 114, 114);
    font-style: italic;
    text-align: center;
}

.success-message{
    color: rgb(0, 186, 0);
    font-style: italic;
    text-align: center;
}

</style>
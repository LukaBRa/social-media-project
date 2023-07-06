<script>
import { useForm } from '@inertiajs/vue3';

export default {
    data() {
        return {
            emailField: '',
        }
    },
    props: [
        'email',
        'resetToken',
        'errors'
    ],
    setup(){
        const form = useForm({
            email: '',
            password: '',
            confirmPassword: '',
        });

        return { form }
    },
    methods: {
        submitForm(){ 
            this.form.post('/change-password');
        }
    },
    mounted() {
        this.form.email = this.email;
        if(this.errors.success){
            this.$refs.resetForm.style.display = 'none';
            this.$refs.resetForm.style.display = 'block';
        }
    }
}

</script>

<template>

<div class="login-container">

<div class="login-form" ref="resetForm">
    <h2><i class="fa-solid fa-hippo"></i></h2>
    <p class="error-message">{{ errors.msg }}</p>
    <form @submit.prevent="submitForm">
        <div>
            <input v-model="form.email" type="text" name="email" id="email" placeholder="Email adresa..." hidden>
        </div>
        <div>
            <label for="password">Lozinka</label>
            <input v-model="form.password" type="password" name="password" id="password" placeholder="Lozinka...">
            <p class="error-message">{{ errors.password }}</p>
        </div>
        <div>
            <label for="confirmed-password">Potvrdi lozinku</label>
            <input v-model="form.confirmPassword" type="password" name="confirmed-password" id="confirmed-password" placeholder="Potvrdi lozinku...">
            <p class="error-message">{{ errors.confirmPassword }}</p>
        </div>
        <input type="submit" value="Promeni lozinku">
    </form>
</div>

<div class="login-form reset-success" ref="changeSuccess">
    <h2><i class="fa-solid fa-hippo"></i></h2>
    <p class="error-message">{{ errors.success }}</p>
    <p class="form-link"><a href="/">Prijavite se</a></p>
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

.reset-success{
    display: none;
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

</style>
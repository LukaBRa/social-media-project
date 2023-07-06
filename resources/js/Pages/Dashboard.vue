<script>
import Dashboardmenu from '../Components/Dashboardmenu.vue'

export default{
    components: {
        Dashboardmenu,
    },
    props: [
        'admin'
    ]
}

</script>

<template>

<div class="app-container-fluid bg-lightgray">

    <Dashboardmenu :admin="admin"/>

    <div class="options">
        <a href="/add-admin"><i class="fa-solid fa-user"></i> Dodavanje novog administratora</a>
        <a href="/reported-posts"><i class="fa-solid fa-layer-group"></i> Pregled prijavljenih objava</a>
        <a href="/reported-comments"><i class="fa-solid fa-comments"></i> Pregled prijavljenih komentara</a>        
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

.options{
    display: flex;
    flex-direction: column;
    gap: 2rem;
    margin-inline: auto;
    margin-top: 10rem;
}

.options a{
    background-color: white;
    padding: 2rem;
    color: var(--secondary);
    text-decoration: none;
    border-radius: 15px;
    font-size: 1.2rem;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    transition: all 0.3s ease;
}

.options a:hover{
    color: var(--primary);
}

.options a i {
    margin-right: 1rem;
}

</style>
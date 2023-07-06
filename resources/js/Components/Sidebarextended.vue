<script>

export default {
    props: [
        'signedUserId',
        'newNotif',
        'newMsg'
    ],
    computed: {
        showNewNotif(){
            return this.newNotif > 0
        },
        showNewMsg(){
            return this.newMsg > 0
        }
    }
}

</script>

<template>

<nav>
    <a href="" class="logo"><i class="fa-solid fa-hippo"></i></a>
    <ul>
        <li><a href="/home"><i class="fa-solid fa-house"></i> Početna</a></li>
        <li><a :href="`/profile/${signedUserId}`"><i class="fa-solid fa-user"></i> Profil</a></li>
        <li><a href="/addpost"><i class="fa-solid fa-square-plus"></i> Dodajte objavu</a></li>
        <li><a href="/search"><i class="fa-solid fa-magnifying-glass"></i> Pretraga</a></li>
        <li><a href="/chat"><i class="fa-solid fa-envelope"></i><span v-if="showNewMsg" class="number">{{ newMsg }}</span> Poruke</a></li>
        <li><a href="/notifications"><i class="fa-solid fa-bell"></i><span v-if="showNewNotif" class="number">{{ newNotif }}</span> Obaveštenja</a></li>
        <li><a href="/settings"><i class="fa-solid fa-gear"></i> Podešavanja</a></li>
        <li><a href="/logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Odjava</a></li>
    </ul>
</nav>

</template>

<style scoped>

:root{
  --primary: #5664f9;
  --secondary: #1e1e1e;
  --lightgray: #f5f5f5;
  --lightred: #fa3678;
}

nav{
    background-color: var(--primary);
    height: 100vh;
    width: 15%;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
}

.logo{
    color: white;
    font-size: 3rem;
}

ul{
    list-style-type: none;
}

ul li{
    margin-bottom: 1.5rem;
}

ul li a{
    position: relative;
    text-decoration: none;
    font-size: 1.1rem;
    color: white;
}

.number{
    position: absolute;
    background-color: var(--lightred);
    color: var(white);
    font-size: 14px;
    padding: 0.1rem 0.3rem;
    border-radius: 50%;
    left: -10px;
    top: -10px;
}



</style>
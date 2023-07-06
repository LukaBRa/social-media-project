<script>

export default {
    props: [
        'user'
    ]
}

</script>

<template>

<nav>
    <a href="" class="logo"><i class="fa-solid fa-hippo"></i></a>
    <ul>
        <li><a href="/home"><i class="fa-solid fa-house"></i> Početna</a></li>
        <li><a :href="'/profile/' + user.id"><i class="fa-solid fa-user"></i> Profil</a></li>
        <li><a href="/addpost"><i class="fa-solid fa-square-plus"></i> Dodajte objavu</a></li>
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
    text-decoration: none;
    font-size: 1.1rem;
    color: white;
}


</style>
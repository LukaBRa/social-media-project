<script>

export default {
    props: [
        'admin'
    ],
}

</script>

<template>


<nav>
    <a href="/dashboard" class="logo">
        <i class="fa-solid fa-user"></i>
        <p>{{ admin.name }}</p>
        <p>(Administrator)</p>
    </a>
    <ul>
        <li><a href="/dashboard"><i class="fa-solid fa-house"></i> Pocetna</a></li>
        <li><a href="/logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Odjava</a></li>
    </ul>
</nav>

</template>

<style>

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
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    gap: 1rem;
}

.logo p{
    font-size: 1.2rem;
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
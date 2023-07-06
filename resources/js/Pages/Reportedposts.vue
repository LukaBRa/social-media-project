<script>
import Dashboardmenu from '../Components/Dashboardmenu.vue'
import Reportedpost from '../Components/Reportedpost.vue'

export default{
    data() {
        return {
            reportedPostsMutable: [],
        }
    },
    props: [
        'admin',
        'reportedposts'
    ],
    components: {
        Dashboardmenu, Reportedpost
    },
    methods: {
        deletePost(postId){
            this.reportedPostsMutable = this.reportedPostsMutable.filter(post => post.id != postId)
        }
    },
    mounted(){
        this.reportedPostsMutable = [...this.reportedposts];
    }
}

</script>

<template>

<div class="app-container-fluid bg-lightgray">

<Dashboardmenu :admin="admin"/>

<div class="middle-container">

    <div class="content">

        <h1>Prijavljene objave</h1>

        <div class="reported-posts">

            <Reportedpost v-for="reportedpost in reportedPostsMutable" :reportedpost="reportedpost" :admin="admin" @deletedPost="deletePost"/>

        </div>
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

.middle-container{
    width: 85%;
}

.bg-lightgray{
    background-color: var(--lightgray);
}

.content{
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem 10rem;
    margin-inline: auto;
    width: max-content;
    height: 95%;
    overflow: auto;
    background-color: white;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
}

.content h1{
    padding-bottom: 1rem;
    border-bottom: 2px solid rgb(234, 234, 234);
    width: 100%;
    text-align: center;
    margin-bottom: 1rem;
}

.reported-posts{
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10rem;
    height: 100%;
    overflow: auto;
}


</style>
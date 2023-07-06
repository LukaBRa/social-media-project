<script>
import Dashboardmenu from '../Components/Dashboardmenu.vue'
import Reportedcomment from '../Components/Reportedcomment.vue'

export default{
    data() {
        return {
            reportedCommentsMutable: [],
        }
    },
    props: [
        'admin',
        'reportedcomments'
    ],
    components: {
        Dashboardmenu, Reportedcomment
    },
    methods: {
        deleteComment(postId){
            this.reportedCommentsMutable = this.reportedCommentsMutable.filter(post => post.id != postId)
        }
    },
    mounted(){
        this.reportedCommentsMutable = [...this.reportedcomments];
    }
}


</script>

<template>

<div class="app-container-fluid bg-lightgray">

<Dashboardmenu :admin="admin"/>

<div class="middle-container">

    <div class="content">

        <h1>Prijavljeni komentari</h1>

        <div class="reported-comments">

            <Reportedcomment v-for="reportedcomment in reportedCommentsMutable" :reportedcomment="reportedcomment" :admin="admin" @deletedComment="deleteComment"/>

        </div>
    </div>

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
    padding: 2rem 5rem;
    margin-inline: auto;
    width: max-content;
    height: 95%;
    width: 50%;
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

.reported-comments{
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    height: 100%;
    overflow: auto;
    width: 100%;
}

</style>
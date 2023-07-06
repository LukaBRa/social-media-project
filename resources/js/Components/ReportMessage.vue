<script>

export default{
    props: [
        'msg'
    ],
    mounted(){
        if(this.msg != null){
            this.$refs.msg.style.display = "block"
            setTimeout(() => {
                this.$refs.msg.style.display = "none"
            }, 3000);
        }
    }
}

</script>

<template>

<div ref="msg" class="report-message">
    <p>{{ msg }}</p>
</div>

</template>

<style>

.report-message{
    display: none;
    position: absolute;
    background-color: rgb(0, 195, 0);
    color: white;
    padding: 1rem;
    width: max-content;
    left: 50%;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
}

.report-message p{
    font-size: 1.2rem;
}


</style>
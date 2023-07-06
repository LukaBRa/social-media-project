<script>

export default{
    data(){
        return{
            messageClass: '',
        }
    },
    props: [
        'senderId',
        'signedUser',
        'message',
    ],
    mounted(){
        if(this.senderId == this.signedUser.id){
            this.messageClass = 'user-message';
        }else{
            this.messageClass = 'friend-message';
        }
    }
}

</script>

<template>

<div :class="messageClass">
    <p>{{ message }}</p>
</div>

</template>

<style>

:root{
  --primary: #5664f9;
  --secondary: #1e1e1e;
  --lightgray: #f5f5f5;
  --lightred: #fa3678;
}

.friend-message{
    background-color: var(--primary);
    padding: 0.5rem;
    max-width: 45%;
    color: white;
    border: 1px solid var(--primary);
    border-radius: 10px 10px 10px 00px;
    align-self: flex-start;
}

.user-message{
    padding: 0.5rem;
    max-width: 45%;
    color: var(--secondary);
    border: 1px solid var(--secondary);
    border-radius: 10px 10px 0px 10px;
    align-self: flex-end;
}


</style>
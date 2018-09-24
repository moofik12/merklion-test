<template>
    <div class="column is-8">
        <div class="box message-window">
            <div v-for="message in messages">
                <p><b>{{message.user.name}}: </b>{{message.message}}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        computed: {
            ...mapState({
                messages: store => store.messages
            })
        },
    }
</script>

<style>
    .message-window {
        overflow-y: auto;
        min-height: 500px;
        max-height: 500px;
    }
</style>
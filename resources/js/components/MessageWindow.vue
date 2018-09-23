<template>
    <div class="column is-8">
        <div class="box">
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
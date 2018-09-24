<template>
    <section class="hero is-dark is-fullheight">
        <div class="hero-head">
            <div class="container">
                <h1 class="title">
                    Chat room #1
                </h1>
            </div>
        </div>

        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <message-window></message-window>
                    <users-list></users-list>
                </div>
            </div>
        </div>

        <div class="hero-foot">
            <chat-input :user="user"></chat-input>
        </div>
    </section>
</template>

<script>
    import MessageWindow from '../components/MessageWindow';
    import ChatInput from '../components/ChatInput';
    import UsersList from '../components/UsersList';
    import {mapActions} from 'vuex';

    export default {
        props: ['user'],
        methods: {
            ...mapActions([
                'getMessages',
                'listenBroadcastChannel',
                'listenPresenceChannel'
            ])
        },
        components: {
            'message-window': MessageWindow,
            'users-list': UsersList,
            'chat-input': ChatInput
        },
        created: function () {
            this.getMessages();
            this.listenBroadcastChannel();
            this.listenPresenceChannel(this.channel);
        },
        computed: {
            channel() {
                return window.Echo.join('chat.members');
            }
        }
    }
</script>
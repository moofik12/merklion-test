import Echo from 'laravel-echo';
import Pusher from 'pusher-js'
import Vue from 'vue';
import Buefy from 'buefy';
import 'buefy/dist/buefy.css'
import store from './store/index';
import ChatRoom from './pages/ChatRoom';
import axios from 'axios';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '8b6b51ff736b2c101a37',
    cluster: 'ap2',
    encrypted: true
});


Vue.use(Buefy);

const app = new Vue({
    el: '#app',
    store,
    components: {
        'chat': ChatRoom
    }
});
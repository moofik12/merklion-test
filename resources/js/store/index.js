import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        messages: [],
        users: []
    },
    mutations: {
        saveManyMessages(state, data) {
            state.messages = data;
        },
        saveOneMessage(state, message) {
            state.messages.push(message);
        }
    },
    actions: {
        getMessages({commit}) {
            axios.get('/messages').then(response => {
                commit('saveManyMessages', response.data);
            });
        },
        listenBroadcastChannel({commit}) {
            Echo.private('chat')
                .listen('MessageSent', (e) => {
                    let message = {
                        message: e.message.message,
                        user: e.user
                    };

                    commit('saveOneMessage', message);
                });
        },
        sendMessage({commit}, message) {
            commit('saveOneMessage', message);

            let socketId = Echo.socketId();

            axios.post('/messages', message, {
                headers: {
                    "X-Socket-ID": socketId
                }
            }).then(response => {
                console.log(response.data);
            });
        }
    }
});
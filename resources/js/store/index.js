import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        messages: [],
        users: [],
        isAvatarModalActive: false,
        uploadStatus: true,
        systemMessage: ''
    },
    mutations: {
        saveUsers(state, data) {
            state.users = data;
        },
        saveManyMessages(state, data) {
            state.messages = data;
        },
        saveOneMessage(state, data) {
            state.messages.push(data);
        },
        changeUploadStatus(state, payload) {
            state.uploadStatus = payload.uploadStatus;
            state.systemMessage = payload.systemMessage;
        },
        pushCurrentUser(state, data) {
            state.users.push(data);
        }
    },
    actions: {
        getUsers({commit}) {
            axios.get('/users').then(response => {
                commit('saveUsers', response.data);
            });
        },
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
        listenPresenceChannel({dispatch, commit}, channel) {
            channel
                .joining(data => {
                    let notification = data.user.name + ' joined chat.';
                    let message = {
                        message: notification,
                        user: {
                            name: 'Notification bot'
                        }
                    };

                    commit('saveOneMessage', message);
                })
                .leaving(data => {
                    let notification = data.user.name + ' left chat.';
                    let message = {
                        message: notification,
                        user: {
                            name: 'Notification bot'
                        }
                    };

                    commit('saveOneMessage', message);
                })
                .listen('UserJoined', (e) => {
                    dispatch('getUsers');
                })
                .listen('UserLeft', (e) => {
                    dispatch('getUsers');
                })
                .listen('AvatarChanged', (e) => {
                    dispatch('getUsers');
                });
        },
        sendMessage({commit}, message) {
            commit('saveOneMessage', message);

            let socketId = Echo.socketId();

            axios.post('/messages', message, {
                headers: {
                    "X-Socket-ID": socketId
                }
            })
                .then(response => {
                    console.log(response.data);
                });
        },
        uploadAvatar({dispatch, commit}, file) {
            return axios.post('/upload', {
                image: file,
            })
                .catch((e) => {
                    commit('changeUploadStatus', {
                        uploadStatus: false,
                        systemMessage: e.response.data.message
                    });
                })
                .then(() => {
                    dispatch('getUsers');
                });

        }
    }
});
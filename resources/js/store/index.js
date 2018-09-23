import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        messages: [
            {username: "Mary", message: "Hello, Pete!"},
            {username: "Pete", message: "Yo, Mary!"},
        ],
        users: [
            {name: "Mary", avatar: ""},
            {name: "Pete", avatar: ""}
        ]
    },
    mutations: {

    },
    actions: {
        sendMessage(message) {

        }
    }
});
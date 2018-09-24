<template>
    <div class="column is-4">
        <div class="box">
            <div class="list-header">USERS ONLINE:</div>
            <div v-for="user in users">
                <b>{{user.name}}</b>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState, mapActions} from 'vuex';

    export default {
        methods: {
            ...mapActions([
                'getUsers'
            ])
        },
        computed: {
            ...mapState({
                users: store => store.users
            })
        },
        created: function() {
            this.getUsers();
        }
    }
</script>
<style>
    .list-header {
        font-weight: 700;
        font-size: 1.2em;
        color: #606f7b;
        margin-bottom: 1em;
    }
</style>
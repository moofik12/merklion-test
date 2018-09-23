<template>
    <div class="column is-4">
        <div class="box">
            <div v-for="user in users">
                <b>{{user.name}}</b>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        computed: {
            ...mapState({
                users: store => store.users
            })
        },
    }
</script>
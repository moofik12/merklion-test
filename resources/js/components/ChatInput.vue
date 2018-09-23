<template>
    <div class="container">
        <div class="columns margin-bottom">
            <div class="column is-10">
                <b-field type="is-warning">
                    <b-input :value="message" type="text" maxlength="30"></b-input>
                </b-field>
            </div>
            <div class="column is-2">
                <button class="button is-warning" @click="send">Send message</button>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';

    export default {
        data: function () {
            return {
                message: 'Hello, everyone.'
            }
        },
        methods: {
            ...mapActions([
                'sendMessage'
            ]),
            send() {
                this.sendMessage(this.message);

                this.message = '';
            }
        }
    }
</script>

<style scoped>
    .margin-bottom {
        margin-bottom: 0.25em;
    }
</style>
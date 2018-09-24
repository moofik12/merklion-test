<template>
    <div class="column is-4">
        <div class="box users-list">
            <div class="list-header">USERS ONLINE:</div>
            <div v-for="user in users">
                <div @click="changeAvatar(user)" class="avatar-thumb">
                    <img :src="user.avatar"/>
                </div>
                <b>{{user.name}}</b>
            </div>
        </div>
        <b-modal :active.sync="isAvatarModalActive" scroll="keep">
            <div class="card">
                <div class="card-image">
                    <figure class="image">
                        <img :src="currentAvatar" alt="Image">
                    </figure>
                </div>
                <div v-show="canUploadAvatar" class="card-content">
                    <div class="media">
                        <div class="media-content">
                            <b-field>
                                <b-upload v-model="files">
                                    <a class="button is-warning is-fullwidth">
                                        <b-icon icon="upload"></b-icon>
                                        <span>Click to upload</span>
                                    </a>
                                </b-upload>
                                <span class="file-name"
                                      v-if="files && files.length">
                                    {{ files[0].name }}
                                </span>
                            </b-field>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import {mapState, mapActions} from 'vuex';

    export default {
        props: ['user'],
        data: function() {
            return {
                files: [],
                image: '',
                isAvatarModalActive: false,
                canUploadAvatar: false
            }
        },
        methods: {
            ...mapActions([
                'getUsers',
                'uploadAvatar',
                'currentAvatar'
            ]),
            changeAvatar(user) {
                this.canUploadAvatar = false;
                this.currentAvatar = user.avatar;
                this.isAvatarModalActive = true;

                if (user.name === this.user.name) {
                    this.canUploadAvatar = true;
                }
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;

                reader.onload = (e) => {
                    vm.image = e.target.result;
                    this.uploadAvatar(vm.image).then(function () {
                        vm.files = [];
                        vm.isAvatarModalActive = false;
                    });
                };

                reader.readAsDataURL(file);
            }
        },
        computed: {
            ...mapState({
                users: store => store.users
            })
        },
        watch: {
            files: function () {
                if (!this.files.length) {
                    return;
                }

                this.createImage(this.files[0]);
            }
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

    .users-list {
        overflow-y: auto;
        max-height: 500px;
        min-height: 500px;
    }

    .avatar-thumb {
        overflow: hidden;
        width: 50px;
        height: 50px;
        border: 1px solid black;
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
    }

    .avatar-thumb + b {
        margin-left: 10px;
        vertical-align: top;
    }

    .avatar-thumb img {
        width: 50px;
    }
</style>
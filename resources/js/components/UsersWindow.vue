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
    import {mapState, mapActions, mapMutations} from 'vuex';

    export default {
        props: ['user'],
        data: function () {
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
            ...mapMutations([
                'changeUploadStatus',
                'pushCurrentUser'
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
            },
        },
        computed: {
            ...mapState({
                users: store => store.users,
                uploadStatus: store => store.uploadStatus,
                systemMessage: store => store.systemMessage
            })
        },
        watch: {
            files: function () {
                if (!this.files.length) {
                    return;
                }

                this.createImage(this.files[0]);
            },
            uploadStatus: function () {
                if (!this.uploadStatus) {
                    this.$toast.open({
                        duration: 5000,
                        message: this.systemMessage,
                        position: 'is-top',
                        type: 'is-warning'
                    });

                    this.changeUploadStatus({
                        uploadStatus: true,
                        systemMessage: ''
                    });
                }
            }
        },
        created: function () {
            this.pushCurrentUser(this.user);
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
        position: relative;
    }

    .avatar-thumb + b {
        margin-left: 10px;
        vertical-align: top;
    }

    .avatar-thumb img {
        object-fit: cover;
        width: 50px;
        height: 50px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
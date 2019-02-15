<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="members-frame">
                    <div class="list-group">
                        <div class="list-group-item"
                             v-bind:key="index"
                             v-for="(member, index) in members">
                            {{ member.name }}
                        </div>
                    </div>
                </div>
                <div class="chat-frame">
                    <div class="chat-window">
                        <div class="list-group">
                            <infinite-loading v-if="hasNewMessages" slot="append" direction="top" @infinite="getMoreMessages"></infinite-loading>
                            <div class="list-group-item" v-for="(message, index) in messages" v-bind:key="index">
                                {{ message.user.name }}
                                <br />
                                {{ message.body }}
                            </div>
                        </div>
                    </div>
                    <form v-on:submit.prevent="sendMessage">
                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   v-model="message"
                                   placeholder="Write message...">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit">Send</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
    window._ = require('lodash');
    import InfiniteLoading from 'vue-infinite-loading';
    export default {
        name: "room",
        props: ['app'],
        components: {
            InfiniteLoading,
        },
        data()
        {
            return {
                members: [],
                message: '',
                messages: [],
                currentPage: 1,
                hasNewMessages: false,
                channel: null,
            }
        },
        beforeRouteLeave(to, from, next)
        {
            let roomId = this.app.$route.params.id;
            pusher.unsubscribe('presence-chat.'+roomId);
            this.decreaseActive();
            next();
        },
        mounted()
        {
            if (!this.app.user)
            {
                this.app.$router.push({
                    name: 'login'
                });
            }

            let roomId = this.app.$route.params.id;

            this.channel = pusher.subscribe('presence-chat.'+roomId);
            this.channel.bind('pusher:subscription_succeeded',(members) => {
                members.each((member) => {
                    this.members.push(member.info);

                });

                this.updateActive();
            });

            this.channel.bind('pusher:member_added', (member) => {
                // for example:
                this.members.push(member.info);
            });

            this.channel.bind('pusher:member_removed',(member) => {
                for (let i = 0; i < this.members.length; i++)
                {
                    if (member.info.id === this.members[i].id)
                    {
                        this.members.splice(i, 1);
                    }
                }
            });

            this.channel.bind('NewMessage', (data) => {
                if (data.message.user_id !== this.app.user.id)
                {
                    this.messages.push(data.message);
                    this.scrollToBottom();
                }
            });

            this.getMessages();
        },
        methods: {
            updateActive()
            {
                let data = {
                    room_id: this.app.$route.params.id,
                    active: this.channel.members.count
                };

                this.app.req.post('room/update-active', data);
            },

            decreaseActive()
            {
                let data = {
                    room_id: this.app.$route.params.id
                };

                this.app.req.post('room/decrease-active', data);
            },

            sendMessage()
            {
                if (!this.message)
                {
                    return;
                }

                let data = {
                    body: this.message,
                    room_id: this.app.$route.params.id
                };


                let message = {
                    body: this.message,
                    user: this.app.user,
                };

                this.message = '';

                this.messages.push(message);
                this.scrollToBottom();
                this.app.req.post('message/send', data).then((response) => {

                })
            },

            getMessages()
            {
                this.app.req.get('message/'+this.app.$route.params.id+'?page='+this.currentPage).then((response) => {
                    this.messages.unshift(...response.data.data.reverse());
                    this.currentPage++;
                    this.scrollToBottom();

                    setTimeout(() => {
                        this.hasNewMessages = true;
                    }, 300);

                });
            },

            getMoreMessages($state)
            {
                this.app.req.get('message/'+this.app.$route.params.id+'?page='+this.currentPage).then((response) => {
                    if (response.data.data.length) {
                        this.messages.unshift(...response.data.data.reverse());
                        this.currentPage++;
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                });

            },

            scrollToBottom()
            {
                let $chatWindow = $('.chat-window');

                $chatWindow.animate({scrollTop: $chatWindow[0].scrollHeight}, 200);

            }
        }
    }
</script>

<style scoped>
    .members-frame {
        border: 1px solid black;
        height: 700px;
        overflow-y: auto;
        width: 30%;
        margin-right: 5px;
        background-color: white;
        display: inline-block;
    }

    .chat-frame
    {
        width: calc(100% - 30% - 10px);
        display: inline-block;
        vertical-align: top;
    }



    .chat-window {
        border: 1px solid black;
        height: 660px;
        overflow-y: auto;
        background-color: white;
    }
</style>
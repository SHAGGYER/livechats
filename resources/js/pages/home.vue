<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="home-container">
                    <h1>Rooms</h1>

                    <spinner v-if="loading"></spinner>
                    <div v-else>
                        <div class="row">
                            <div class="col-sm-3" v-for="(room, index) in rooms" v-bind:key="index">
                                <div class="card" style="text-align: center">
                                    <div class="card-header">
                                        {{ room.title }}
                                    </div>

                                    <div class="card-body">
                                        {{ room.active }} <strong>Active</strong>
                                    </div>

                                    <div class="card-footer" v-if="app.user">
                                        <router-link :to="{ name: 'room', params: { id: room.id } }"
                                                     class="btn btn-secondary">
                                            Join
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "home",
        props: ['app'],
        data()
        {
            return {
                rooms: [],
                loading: false
            }
        },
        mounted()
        {
            this.getRooms();
        },
        methods: {
            getRooms()
            {
                this.loading = true;

                this.app.req.get('room/all').then((response) => {
                    this.loading = false;
                    this.rooms = response.data;
                })
            }
        }
    }
</script>

<style scoped>
    .home-container {
        border-radius: 12px;
        background-color: wheat;
        padding: 25px
    }
</style>
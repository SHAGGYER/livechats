<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <router-link :to="{ name: 'home' }" class="navbar-brand">LiveChats</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <router-link :to="{ name: 'home' }" class="nav-link">Home</router-link>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ app.user ? app.user.name : 'Account' }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <router-link v-if="!app.user" :to="{ name: 'login' }" class="dropdown-item">Login</router-link>
                            <router-link v-if="!app.user" :to="{ name: 'register' }" class="dropdown-item">Register</router-link>
                            <a v-if="app.user" @click="logout" class="dropdown-item" href="javascript:;">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>

<script>
    export default {
        name: "navbar",
        props: ['app'],
        methods: {
            logout()
            {
                this.app.req.post('logout').then(() => {
                    this.app.user = null;
                })
            }
        }
    }
</script>

<style scoped>

</style>
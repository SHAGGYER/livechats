<template>
    <div>
        <spinner v-if="loading" style="margin-top: 50px"></spinner>
        <div v-else-if="initiated">
            <navbar :app="this"></navbar>
            <router-view :app="this" style="margin-top: 20px"/>
        </div>
    </div>
</template>

<script>
    import Navbar from "./components/navbar";
    export default {
        name: "App",
        components: {Navbar},
        data()
        {
            return {
                user: null,
                loading: false,
                req: axios.create({
                    baseURL: BASE_URL
                }),
                initiated: false,
            }
        },
        mounted() {
            this.init();
        },
        methods: {
            init()
            {
                this.loading = true;

                this.req.get('init').then((response) => {
                    this.loading = false;
                    this.initiated = true;
                    this.user = response.data.user;
                })
            }
        }
    }
</script>

<style>
    body {
        background-color: #bb000f;
    }
</style>
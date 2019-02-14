<template>
    <div>
        <spinner v-if="loading"></spinner>
        <div class="container-fluid" v-else-if="initiated && user" style="padding: 0">
            <div class="row" style="margin-right: 0;">
                <div class="col-md-2" style="padding-right: 0">
                    <left-navbar v-if="user" :app="this"></left-navbar>
                </div>
                <div class="col-md-10" style="padding-left: 0">
                    <router-view :app="this"></router-view>
                </div>
            </div>
        </div>
        <div v-else>
            <router-view :app="this"></router-view>
        </div>
    </div>

</template>

<script>
    import Resources from './resources';
    import LeftNavbar from "./components/left-navbar";
    export default {
        name: "Admin",
        components: {LeftNavbar},
        data()
        {
            return {
                resources: Resources,
                loading: false,
                initiated: false,
                req: axios.create({
                    baseURL: BASE_URL
                }),
                user: null,
            }
        },
        mounted() {
            this.init();
        },

        methods: {
            init()
            {
                this.loading = true;
                this.req.get('admin/init')
                    .then((response) => {
                        this.loading = false;
                        this.initiated = true;
                        this.user = response.data.user;

                        if (!this.user)
                        {
                            this.$router.push({
                                name: 'login'
                            })
                        }
                })
                    .catch(err => console.log(err));
            },

            findResource()
            {
                return this.resources.filter(resource => resource.route === this.$route.query.action)[0];
            }
        }
    }
</script>

<style scoped>

</style>
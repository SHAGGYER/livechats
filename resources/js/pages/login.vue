<template>
    <div class="container page-container" style="position: relative">
        <div class="card">
            <div class="card-header">Login</div>
            <div class="row">


                <div class="col-md-6 offset-md-3">

                    <spinner v-if="loading"></spinner>
                    <form v-else v-on:submit.prevent="onSubmit" style="margin: 25px">

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text"
                                   v-model="username"
                                   class="form-control"
                                   v-bind:class="{ 'is-invalid': errorUsername }"
                                   placeholder="Username">
                            <div class="invalid-feedback">
                                {{ errorUsername }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input v-model="password"
                                   type="password"
                                   class="form-control"
                                   v-bind:class="{ 'is-invalid': errorPassword }"
                                   placeholder="Password">
                            <div class="invalid-feedback">
                                {{ errorPassword }}
                            </div>
                        </div>


                        <button :disabled="loading" type="submit" class="btn btn-secondary">Login</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
    export default {
        name: "login",
        props: ['app'],
        data()
        {
            return {


                username: '',
                errorUsername: '',

                password: '',
                errorPassword: '',

                loading: false,

                errors: []
            }
        },

        mounted()
        {
            if (this.app.user)
            {
                this.app.$router.push({name:'home'});
            }
        },

        methods: {
            onSubmit()
            {
                this.errors = [];

                if (!this.username && this.username.length < 4)
                {
                    this.errorUsername = 'Username has to be at least 4 characters long.';
                    this.errors.push(this.errorUsername);
                }
                else
                {
                    this.errorUsername = null;
                }

                if (!this.password && this.password.length < 6)
                {
                    this.errorPassword = 'Password has to be at least 6 characters long.';
                    this.errors.push(this.errorPassword);
                }
                else
                {
                    this.errorPassword = null;
                }


                if (!this.errors.length)
                {
                    this.loading = true;

                    let data = {
                        username: this.username,
                        password: this.password,
                    };

                    this.app.req.post('login', data).then((response) => {

                        this.loading = false;

                        if (response.data.id)
                        {
                            this.app.user = response.data;
                            this.app.$router.push({name:'home'});
                        }
                        else if (response.data.error)
                        {
                            this.errorPassword = response.data.error;
                            this.password = '';
                        }
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>
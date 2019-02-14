<template>
    <div class="container page-container" style="position: relative">

        <div class="card">
            <div class="card-header">Register</div>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <spinner v-if="loading"></spinner>
                    <form v-else v-on:submit.prevent="onSubmit" style="margin: 25px">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text"
                                   v-model="name"
                                   class="form-control"
                                   v-bind:class="{ 'is-invalid': errorName }"
                                   placeholder="Name">
                            <div class="invalid-feedback">
                                {{ errorName }}
                            </div>
                        </div>
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
                        <div class="form-group">
                            <label>Password Again</label>
                            <input v-model="passwordConf"
                                   type="password"
                                   class="form-control"
                                   v-bind:class="{ 'is-invalid': errorPasswordConf }"
                                   placeholder="Password Again">
                            <div class="invalid-feedback">
                                {{ errorPasswordConf }}
                            </div>
                        </div>

                        <button type="submit" class="btn btn-secondary">Register</button>
                    </form>
                </div>
            </div>

        </div>

    </div>

</template>

<script>
    export default {
        name: "register",
        props: ['app'],
        data()
        {
            return {
                name: '',
                errorName: '',

                username: '',
                errorUsername: '',

                password: '',
                passwordConf: '',
                errorPassword: null,
                errorPasswordConf: null,

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

                if (!this.name)
                {
                    this.errorName = 'Name is required.';
                    this.errors.push(this.errorName);
                }
                else
                {
                    this.errorName = null;
                }


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

                if (!this.passwordConf && this.passwordConf.length < 6)
                {
                    this.errorPasswordConf = 'Password confirmation has to be at least 6 characters long.';
                    this.errors.push(this.errorPasswordConf);
                }
                else if (this.password !== this.passwordConf)
                {
                    this.errorPasswordConf = 'Passwords do not match.';
                    this.errors.push(this.errorPasswordConf);
                }
                else
                {
                    this.errorPasswordConf = null;
                }

                if (!this.errors.length)
                {
                    this.loading = true;

                    let data = {
                        name: this.name,
                        username: this.username,
                        password: this.password,
                    };

                    this.app.req.post('register', data).then((response) => {

                        this.loading = false;

                        if (response.data.id)
                        {
                            this.app.user = response.data;
                            this.app.$router.push({name:'home'});
                        }
                        else if (response.data.error === 'username_taken')
                        {
                            this.errorUsername = 'This username is taken';
                            this.username = '';
                        }
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>
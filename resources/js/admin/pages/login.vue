<template>
    <div class="flex-container">
        <form v-on:submit.prevent="login">
            <div class="flex-row">
                <div class="flex-item">
                    <h1>Admin Control Panel</h1>
                </div>
                <div v-if="errors.length" class="flex-item">
                    <div class="alert alert-danger" style="text-align: center">
                        <ul style="margin-bottom: 0">
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                </div>
                <div class="flex-item">
                    <div class="form-group">
                        <input type="text" v-model="email" placeholder="Email" class="form-control">
                    </div>
                </div>
                <div class="flex-item">
                    <div class="form-group">
                        <input ref="password" type="password" v-model="password" placeholder="Password" class="form-control">
                    </div>
                </div>
                <div class="flex-item">
                    <button type="submit" class="login-btn">Login</button>
                </div>
            </div>
        </form>

    </div>
</template>

<script>
    export default {
        name: "login",
        props: ['app'],
        data()
        {
            return {
                email: '',
                password: '',
                errors: []
            }
        },
        mounted()
        {
            if (this.app.user)
            {
                this.app.$router.push({
                    name: 'dashboard'
                });
            }
        },
        methods: {
            login()
            {
                this.errors = [];

                if (!this.email)
                {
                    this.errors.push('You must provide an email.');
                }

                if (!this.password)
                {
                    this.errors.push('You must provide a password.');
                }

                if (!this.errors.length)
                {
                    let data = {
                        email: this.email,
                        password: this.password
                    };

                    this.app.req.post('admin/login', data)
                        .then((response) => {
                            this.app.user = response.data;
                            this.app.$router.push({
                                name: 'dashboard'
                            })
                    }).catch(error => {
                        this.password = '';
                        this.$refs.password.focus();
                        this.errors.push(error.response.data.error);
                    })
                }
            }
        }
    }
</script>

<style scoped>
    ul {
        list-style-type: none;
        padding-left: 0;
    }

    input {
        width: 250px;
    }

    .flex-container {
        height: 100vh;
        padding: 0;
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .flex-row {
        width: auto;
        margin: auto;
        border: 3px solid #2141bb;
        background-color: #9fa1ff;
    }
    .flex-item {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
        width: 400px;
        margin: 10px;
        line-height: 20px;
        font-weight: bold;
        text-align: center;
    }

    .login-btn  {
        border: 2px solid #f38309;
        background-color: #ffcf5d;
        padding: 10px;
        font-size: 15px;
        text-align: center;
        cursor: pointer;
    }

    .login-btn:hover,
    .login-btn:active
    {
        border: 2px solid #f38309;
        background-color: white;
    }
</style>
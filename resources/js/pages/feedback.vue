<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Feedback
                    </div>
                    <div class="card-body">
                        <spinner v-if="loading"></spinner>
                        <form v-else-if="!submitted" v-on:submit.prevent="onSubmit">
                            <div class="form-group">
                                <label>Content</label>
                                <textarea v-model="body"
                                          class="form-control"
                                          :class="{ 'is-invalid': errorBody }">
                                </textarea>
                                <div class="invalid-feedback">
                                    {{ errorBody }}
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit">Send Feedback</button>
                        </form>
                        <div v-else class="alert alert-primary">
                            You have successfully sent the feedback. Thank you!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "feedback",
        props: ['app'],
        data()
        {
            return {
                body: '',
                errorBody: null,
                loading: false,
                submitted: false,
            }
        },
        mounted() {
            if (!this.app.user)
            {
                this.$router.push({
                    name: 'home'
                });
            }
        },
        methods: {
            onSubmit()
            {
                if (!this.body)
                {
                    this.errorBody = 'You must enter some content.';
                }
                else {
                    this.errorBody = null;
                }

                if (!this.errorBody)
                {
                    let data = {
                        body: this.body
                    };

                    this.loading = true;

                    this.app.req.post('feedback/create', data).then(() => {
                        this.loading = false;
                        this.submitted = true;
                    })
                }
            }
        }
    }
</script>

<style scoped>

</style>
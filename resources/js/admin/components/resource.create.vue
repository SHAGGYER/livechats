<template>
    <!-- Modal -->
    <div class="modal fade" id="resource-create" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create {{ resource.title }}</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <spinner v-if="loading"></spinner>
                    <form v-else v-on:submit.prevent="create">
                        <div class="form-group" v-for="(field, index) in resource.fields" v-bind:key="index">
                            <div v-if="field.auto !== true">
                                <label>{{ field.name }}</label>
                                <input class="form-control" type="text" v-if="field.type === 'text'" v-model="field.model">
                                <input class="form-control" type="password" v-if="field.type === 'password'" v-model="field.model">
                                <textarea class="form-control" v-else-if="field.type === 'textarea'" v-model="field.model">
                                </textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "resource-create",
        props: ['app', 'resource'],
        data()
        {
            return {
                loading: false
            }
        },

        methods: {
            create()
            {
                let data = {
                    model: this.resource.model,
                    resource: this.resource
                };

                this.loading = true;

                this.app.req.post('admin/resource/create', data).then((response) => {
                    this.loading = false;
                    this.app.$emit('new-resource', response.data);
                    this.close();
                });
            },

            clearModels()
            {

            },

            close()
            {
                $('#resource-create').modal('hide');
            }
        }
    }
</script>

<style scoped>

</style>
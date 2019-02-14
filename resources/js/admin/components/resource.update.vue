<template>
    <!-- Modal -->
    <div class="modal fade" id="resource-update-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Resource</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <spinner v-if="loading"></spinner>
                    <form v-else v-on:submit.prevent="update">
                        <div class="form-group" v-for="(field, index) in fields" v-bind:key="index">
                            <label>{{ field.name }}</label>
                            <input class="form-control" type="text" v-if="field.type === 'text'" v-model="field.model">
                            <input class="form-control" type="password" v-if="field.type === 'password'" v-model="field.model">
                            <textarea class="form-control" v-else-if="field.type === 'textarea'" v-model="field.model">
                            </textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
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
        name: "resource-update",
        props: ['app', 'fields', 'currentResource', 'resource'],
        data()
        {
            return {
                loading: false
            }
        },

        methods: {
            update()
            {
                let data = {
                    model: this.currentResource.model,
                    resource_id: this.resource.id,
                    fields: this.fields
                };

                this.loading = true;

                this.app.req.post('admin/resource/update', data).then((response) => {
                    this.loading = false;
                    this.app.$emit('updated-resource', response.data);
                    this.close();
                });
            },

            close()
            {
                $('#resource-update-modal').modal('hide');
            }
        }
    }
</script>

<style scoped>

</style>
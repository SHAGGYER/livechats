<template>
    <!-- Modal -->
    <div class="modal" id="add-relationship" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Relationship</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <spinner v-if="loading"></spinner>
                    <div v-else>
                        <h3>{{ currentWith ? currentWith.title : '' }}</h3>

                        <select v-model="currentResource" class="form-control">
                            <option value="">Choose {{ currentWith ? currentWith.title : '' }}</option>
                            <option v-bind:key="index"
                                    v-for="(relationship, index) in relationships"
                                    :value="relationship.id">
                                {{ currentWith ? relationship[currentWith.selectColumn] : '' }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button @click="addRelationship" type="button" class="btn btn-success">Add Relationship</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "add-relationship",
        props: ['app', 'currentWith', 'resource'],
        data()
        {
            return {
                loading: false,
                relationships: null,
                currentResource: '',
            }
        },
        mounted()
        {

            $('#add-relationship').on('shown.bs.modal', (e) => {

                this.getRelationship();

            })


        },
        methods: {
            getRelationship()
            {
                let data = {
                    model: this.currentWith.model
                };

                this.app.req.post('admin/resource/get-relationship', data).then((response) => {
                    this.relationships = response.data.data;
                });
            },

            addRelationship()
            {
                let data = {
                    model: this.app.findResource().model,
                    resource_id: this.resource.id,
                    attached_resource_id: this.currentResource,
                    with: this.currentWith.relationship,
                    current_with: this.currentWith,
                };

                this.app.req.post('admin/resource/attach-relationship', data).then(() => {
                    this.close();
                    this.$emit('added-relationship');
                })
            },

            close()
            {
                $('#add-relationship').modal('hide');
            }
        }
    }
</script>

<style scoped>

</style>
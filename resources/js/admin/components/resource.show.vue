<template>
    <!-- Modal -->
    <div class="modal fade" id="resource-show-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Resource</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-for="(field, index) in fields" v-bind:key="index" v-if="field.type !== 'password'">
                        <label><strong>{{ field.name }}</strong></label>
                        <p style="margin-bottom: 0">{{ field.model }}</p>
                    </div>
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
        name: "resource-show",
        props: ['app', 'fields'],
        methods: {

            close()
            {
                $('#resource-show-modal').modal('hide');
            }
        }
    }
</script>

<style scoped>

</style>
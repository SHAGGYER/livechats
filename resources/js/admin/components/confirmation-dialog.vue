<template>
    <div class="modal" tabindex="-1" role="dialog" :id="this.id">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm</h5>

                    <button type="button" class="close" @click="close">
                        <span>&times;</span>
                    </button>
                </div>



                <div class="modal-body">
                    <spinner v-if="loading"></spinner>
                    <nav v-else class="nav nav-pills nav-justified">
                        <a @click="processOnYes" class="nav-item nav-link" href="javascript:;">Yes</a>
                        <a @click="close" class="nav-item nav-link" href="javascript:;">No</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "confirmation-dialog",
        props: ['onYes', 'id'],
        data()
        {
            return {
                loading: false,
            }
        },
        methods: {
            processOnYes()
            {
                this.loading = true;

                this.onYes(() => {
                    this.loading = false;
                    this.close();
                })
            },

            close()
            {
                $('#'+this.id).modal('hide');
            }
        }
    }
</script>

<style scoped>
    .nav .nav-link
    {
        font-size: 18px;
        margin-right: 3px;
        border: 1px solid black;
    }

    .modal-sm
    {
        max-width: 35%;
    }
</style>
<template>
    <div class="container-fluid" style="margin-top: 20px">
        <div class="row">
            <div class="col-md-12">
                <spinner v-if="loading"></spinner>
                <div v-else>
                    <h1>{{ currentResource.plural }}</h1>

                    <button @click="openCreateModal" class="btn btn-primary" style="margin-bottom: 20px">Create New</button>

                    <form v-on:submit.prevent="search">
                        <div class="input-group" style="width: 250px; margin-bottom: 20px">
                            <input type="text" v-model="searchQuery" class="form-control" placeholder="Search...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">Go!</button>
                            </div>
                        </div>
                    </form>



                    <table class="table table-bordered table-hover" v-if="resources.length">
                        <thead>
                            <tr>
                                <th v-for="field in currentResource.fields" v-if="field.type !== 'password'">{{ field.name }}</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(resource, index) in resources" v-bind:key="index">
                                <td v-for="field in currentResource.fields" v-if="field.type !== 'password'">{{ resource[field.column] }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <router-link :to="{ name: 'resource.show', query: { resource_id: resource.id, action: currentResource.route } }"
                                                         class="dropdown-item">
                                                View
                                            </router-link>

                                            <a @click="openEditModal(resource)"
                                               href="javascript:;"
                                               class="dropdown-item">
                                                Edit
                                            </a>
                                            <a @click="openDeleteResourceDialog(resource)" class="dropdown-item" href="javascript:;">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <confirmation-dialog :on-yes="deleteResource" id="delete-resource-dialog"></confirmation-dialog>
        <resource-create :resource="currentResource" :app="app"/>
        <resource-update v-if="fields"
                         :resource="resource"
                         :current-resource="currentResource"
                         :fields="fields"
                         :app="app" />
        <resource-show :fields="fields" :app="app"></resource-show>
    </div>
</template>

<script>
    import ConfirmationDialog from '../components/confirmation-dialog';
    import ResourceCreate from "../components/resource.create";
    import ResourceUpdate from "../components/resource.update";
    import ResourceShow from "../components/resource.show";
    export default {
        name: "resource-index",
        components: {ResourceShow, ResourceUpdate, ResourceCreate, ConfirmationDialog},
        props: ['app'],
        data()
        {
            return {
                resources: [],
                fields: [],
                resource: null,
                loading: false,
                currentResource: this.app.findResource(),
                searchQuery: ''
            }
        },
        mounted()
        {
            this.getResource();

            this.app.$on('new-resource', (resource) => {
                this.resources.push(resource);
            });

            this.app.$on('updated-resource', (resource) => {
                for (let i = 0; i < this.resources.length; i++)
                {
                    if (resource.id === this.resources[i].id)
                    {
                        for(var field in resource)
                        {
                            this.resources[i][field] = resource[field];
                        }

                    }
                }
            });
        },

        watch: {
            '$route.query.action': function() {
                this.resources = [];
                this.fields = [];
                this.resource = null;
                this.currentResource = this.app.findResource();
                this.getResource();
            }
        },

        methods: {
            search()
            {

            },

            getResource()
            {
                let data = {
                    resource: this.currentResource,
                    model: this.currentResource.model
                };

                this.app.req.post('admin/resource', data).then((response) => {
                    this.resources = response.data.data;
                })
            },

            openCreateModal()
            {
                $('#resource-create').modal({
                    backdrop: 'static',
                    keyboard: false,
                })
            },

            openEditModal(resource)
            {
                let fields = [];

                for (let i = 0; i < this.currentResource.fields.length; i++)
                {
                    let field = this.currentResource.fields[i];

                    if (!field.auto)
                    {
                        fields.push({
                            name: field.name,
                            column: field.column,
                            type: field.type,
                            model: resource[field.column],
                            bcrypt: field.bcrypt ? true : false
                        })
                    }
                }

                this.fields = fields;
                this.resource = resource;

                $('#resource-update-modal').modal({
                    backdrop: 'static',
                    keyboard: false,
                })
            },

            openViewResourceModal(resource)
            {
                let fields = [];

                for (let i = 0; i < this.currentResource.fields.length; i++)
                {
                    let field = this.currentResource.fields[i];

                    fields.push({
                        name: field.name,
                        column: field.column,
                        type: field.type,
                        model: resource[field.column],
                    })
                }

                this.fields = fields;

                $('#resource-show-modal').modal({
                    backdrop: 'static',
                    keyboard: false,
                })

            },

            openDeleteResourceDialog(resource)
            {
                this.resource = resource;

                $('#delete-resource-dialog').modal({
                    backdrop: 'static',
                    keyboard: false,
                })
            },

            deleteResource(onFinish)
            {
                let data = {
                    resource_id: this.resource.id,
                    model: this.currentResource.model
                };

                this.app.req.post('admin/resource/delete', data).then(() => {
                    let index = this.resources.indexOf(this.resource);
                    this.resources.splice(index, 1);

                    onFinish();
                })
            },
        }
    }
</script>

<style scoped>

</style>
<template>
    <div class="container-fluid">

        <spinner v-if="loading"></spinner>
        <div v-else-if="resource">
            <h1></h1>


            <div>
                <div v-for="(field, index) in fields" v-bind:key="index">
                    <label><strong>{{ field.name }}</strong></label>
                    <div>{{ field.model }}</div>
                </div>
            </div>

            <div v-if="currentResource.with" style="margin-top: 20px">
                <div v-for="_with in currentResource.with">
                    <h1>{{ _with.title }}</h1>

                    <div v-if="_with.type === 'belongsTo'">
                        <button v-if="_with.type === 'belongsTo' && !resource[_with.relationship]"
                                @click="openAddRelationshipModal(_with)"
                                class="btn btn-sm btn-success">
                            Add Relationship
                        </button>
                        <button @click="detachRelationship(_with)" v-else class="btn btn-sm btn-danger">Detach Relationship</button>
                    </div>


                    <button v-else-if="_with.type === 'hasMany'"
                            @click="openAddRelationshipModal(_with)"
                            class="btn btn-sm btn-success">
                        Add Relationship
                    </button>


                    <table v-if="resource[_with.relationship]" class="table table-bordered" style="margin-top: 20px">
                        <thead>
                        <tr>
                            <th v-for="field in _with.fields">
                                {{ field.name }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="_with.type === 'belongsTo' && resource[_with.relationship]">
                            <td v-for="field in _with.fields">
                                {{ resource[_with.relationship][field.column] }}
                            </td>
                        </tr>
                        <tr v-else-if="_with.type === 'hasMany' && resource[_with.relationship]"
                            v-for="_resource in resource[_with.relationship]">
                            <td v-for="field in _with.fields">
                                {{ _resource[field.column] }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <add-relationship @added-relationship="addedRelationship" :app="app" :current-with="currentWith" :resource="resource"></add-relationship>

    </div>
</template>

<script>
    import AddRelationship from "../components/add-relationship";
    export default {
        name: "resource-show",
        components: {AddRelationship},
        props: ['app'],
        data()
        {
            return {
                resource: null,
                currentResource: this.app.findResource(),
                currentWith: null,
                loading: false,
                fields: []
            }
        },
        mounted()
        {
            this.getResource();
        },
        methods: {
            openAddRelationshipModal(_with)
            {
                this.currentWith = _with;

                setTimeout(() => {
                    $('#add-relationship').modal({
                        backdrop: 'static',
                        keyboard: false,
                    })
                }, 200);

            },


            getResource()
            {
                let data = {
                    resource_id: this.app.$route.query.resource_id,
                    model: this.currentResource.model,
                    with: this.currentResource.with ? this.currentResource.with : []
                };

                this.loading = true;

                this.app.req.post('admin/resource/find-by-id', data).then((response) => {
                    this.resource = response.data;

                    for (let i = 0; i < this.currentResource.fields.length; i++)
                    {
                        let field = this.currentResource.fields[i];

                        this.fields.push({
                            name: field.name,
                            column: field.column,
                            type: field.type,
                            model: this.resource[field.column],
                        })
                    }

                    this.loading = false;
                });
            },

            addedRelationship()
            {
                this.currentWith = null;
                this.fields = [];
                this.getResource();
            },

            detachRelationship(_with)
            {
                let data = {
                    model: this.app.findResource().model,
                    resource_id: this.resource.id,
                    attached_resource_id: this.getAttachedResourceId(_with),
                    with: _with.relationship
                };

                this.app.req.post('admin/resource/detach-relationship', data).then(() => {
                    this.currentWith = null;
                    this.fields = [];
                    this.getResource();
                })
            },

            getAttachedResourceId(_with)
            {
                if (_with.type === 'belongsTo')
                {
                    return this.resource[_with.relationship].id;
                }
            }
        }
    }
</script>

<style scoped>

</style>
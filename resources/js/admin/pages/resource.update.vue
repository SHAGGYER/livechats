<template>
    <div class="container-fluid">
        <h1>Resource Update</h1>

        <div>
            <div v-for="(field, index) in fields" v-bind:key="index">
                <label><strong>{{ field.name }}</strong></label>
                <div>{{ field.model }}</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "resource-update",
        props: ['app'],
        data()
        {
            return {
                resource: null,
                currentResource: this.app.findResource(),
                fields: []
            }
        },
        mounted()
        {
            this.getResource();
        },
        methods: {
            getResource()
            {
                let data = {
                    resource_id: this.app.$route.query.resource_id,
                    model: this.app.findResource().model,
                };

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

                });
            }
        }
    }
</script>

<style scoped>

</style>
<template>
    <div style="float: right">
        <form v-on:submit.prevent="clickPage">
            <button type="button" @click="previous" class="btn btn-secondary">Prev</button>
            <input style="max-width: 30px; text-align: center" type="text" v-model="currentPage">
            of {{ totalPages }}
            <button type="button" @click="next" class="btn btn-secondary">Next</button>
        </form>

    </div>
</template>

<script>
    export default {
        name: "pagination",
        props: ['onClickPage', 'totalPages', 'page', 'app'],
        data()
        {
            return {
                currentPage: parseInt(this.page),
                lastPage: 0
            }
        },

        mounted()
        {
            this.app.$pagination = this;
        },

        methods: {
            previous()
            {
                if (this.currentPage === 1)
                {
                    return;
                }

                this.currentPage--;

                this.clickPage(this.currentPage);
            },

            next()
            {
                if (this.currentPage === this.totalPages)
                {
                    return;
                }

                this.currentPage++;

                this.clickPage(this.currentPage);
            },

            clickPage(page)
            {
                let _page = page ? page : this.currentPage;

                this.onClickPage(_page);
            }
        }
    }
</script>

<style scoped>

</style>
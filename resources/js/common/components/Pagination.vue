<template>
    <nav aria-label="Page navigation" v-if="isVisible">
        <ul class="pagination pagination-sm justify-content-end">
            <li :class="{'page-item': true, active: link.active}" v-for="link in pagination?.links">
<!--                <a class="page-link" :href="removeApiPrefix(link.url)" v-html="link.label"></a>-->
                <router-link class="page-link" :to="removeApiPrefix(link.url)" v-html="link.label"></router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: "Pagination",
    props: {
        pagination: {
            type: Object,
            //required: true
        }
    },

    mounted() {
        //console.log(this.pagination?.total % this.pagination?.per_page);
    },

    computed: {
        isVisible() {
            return this.pagination?.total > 15;
        }
    },

    methods: {

        /**
         * @param url {string}
         * @return {*}
         */
        removeApiPrefix(url) {
            // console.log(import.meta.env.VITE_APP_URL_API);
            if(!url) return '#';
            return url?.replace(import.meta.env.VITE_APP_URL_API, '');
            // return url?.replace('api/', '').replace(import.meta.env.VITE_APP_URL_API, '');
        },
    }
}
</script>

<style scoped>
.page-link {
    color: var(--bs-success);
}

.active .page-link {
    color: white !important;
    background: green !important;
}
</style>

<template>
    <div class="table-responsive table-wrapper">
        <table class="table table-responsive table-hover">
            <thead>
            <tr>
                <th scope="row">#</th>
                <th scope="col" v-for="col of cols">{{ col }}</th>
            </tr>
            </thead>
            <slot />
        </table>
    </div>
</template>

<script>
export default {
    name: "QTableExtended",
    props: {
        cols: {
            type: Object,
        },
        /*rows: {
            type: Array,
        }*/
    },
    /*emits: ['onRowClick'],
    methods: {
        onRowClick(e) {
            this.$emit('onRowClick', e)
        }
    }*/
}
</script>

<style scoped>

</style>

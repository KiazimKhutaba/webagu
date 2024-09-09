<template>
    <div class="table-responsive table-wrapper">
        <table class="table table-responsive table-hover">
            <thead>
            <tr>
                <th scope="row">#</th>
                <th scope="col" v-for="col of cols">{{ col }}</th>
            </tr>
            </thead>
            <tbody>
            <tr class="cursor-pointer" v-for="(row, counter) in rows" @click="onRowClick" :data-item-id="row.id">
                <th scope="row">{{ ++counter }}</th>
                <slot name="item" :item="row"/>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "QTable",
    props: {
        cols: {
            type: Object,
        },
        rows: {
            type: Array,
        }
    },
    emits: ['onRowClick'],
    methods: {
        onRowClick(e) {
            this.$emit('onRowClick', e)
        }
    }
}
</script>

<style scoped>
thead {
    background: #0b5ed7;
    color: white;
}

tbody tr:hover {
    background: #f4f4f4;
}

tbody tr {

}

</style>

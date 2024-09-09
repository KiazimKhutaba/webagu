<template>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" :id="id" :checked="checked" @input="onToggle">
        <label class="form-check-label cursor-pointer" :for="id">
            <slot />
        </label>
    </div>
</template>

<script>
export default {
    name: "Switcher",
    props: {
        id: {
            type: String,
            required: true,
        },
        checked: {
            type: [Boolean, String],
            required: true
        }
    },

    emits: [
        'update:checked',
        // 'onChecked'
    ],

    methods: {
        onToggle(e) {
            this.$emit('update:checked', e.target.checked);
        }
    }
}
</script>

<style scoped>
.form-check-input:checked {
    background-color: var(--bs-success);
    border-color: var(--bs-success);
    outline: none;
}
</style>

<template>
    <button class="nav-link" :id="id" data-bs-toggle="tab" :data-bs-target="dataTarget" type="button"
            role="tab" :aria-controls="ariaControls" :aria-selected="disabled" :disabled="disabled">
        {{ label }}
    </button>
</template>

<script>
export default {
    name: "NavTabLink",
    props: {
        name: {
            type: String,
            required: true
        },
        label: {
            type: String,
            required: true
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        id() {
            return `nav-${this.name}-tab`;
        },

        dataTarget() {
            return `#nav-${this.name}`;
        },

        ariaControls() {
            return `nav-${this.name}`;
        },

        ariaSelected() {
            return ``;
        }
    }
}
</script>

<style scoped>
.nav-link {
    color: var(--bs-success);
}

.nav-link .active {
    color: #0f5132;
}
</style>

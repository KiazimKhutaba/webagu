<template>
    <div class="input-group mb-2">
        <div class="input-group-text">
            <input class="form-check-input mt-0" :name="name" :type="controlType" :checked="isChecked" @change="onSelected"
                   aria-label="">
        </div>
        <input type="text" class="form-control" :value="text" @input="$emit('update:text', $event.target.value)"
               @keyup.enter="onEnterKey" @paste="onPaste" aria-label="">
        <div class="input-group-text cursor-pointer" @click="onRemove">
            <icon-remove/>
        </div>
    </div>
<!--    @input="$emit('update:is-checked', $event.target.checked)"-->
</template>



<script>
import IconRemove from "./icons/IconRemove.vue";

export default {
    name: "QuestionOption",
    components: {IconRemove},
    props: {
        id: {
            type: String
        },
        questionId: {
            type: String
        },
        parentId: String,
        controlType: {
            type: String,
            default: 'checkbox'
        },
        name: {
            type: String
        },
        text: {
            type: String
        },
        isChecked: {
            type: Boolean
        },
        /*isCorrect: {
            type: Boolean,
            default: false
        },*/
    },

    emits: ['onSelected', 'onRemove', 'onEnterKey', 'onPaste', 'update:text', 'update:is-checked'],

    data: () => ({

    }),

    methods: {

        onSelected(e) {
            this.$emit('onSelected', { id: this.id, checked: e.target.checked, type: this.controlType });
        },

        onRemove() {
            this.$emit('onRemove', this.id);
        },

        onEnterKey() {
            this.$emit('onEnterKey', this.questionId);
        },

        /**
         *
         * @param e {ClipboardEvent}
         */
        onPaste(e) {
            const clipboardText = e.clipboardData.getData('text/plain');
            this.$emit('onPaste', clipboardText, this.id);
        }
    }
}
</script>

<style scoped>

</style>

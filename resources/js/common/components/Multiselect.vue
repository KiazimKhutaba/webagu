<template>
    <detect-outside-click @detect="onOutsideClick">
        <div class="multiselect">
            <div class="input-group input-group-sm mb-2">
                <input type="text" class="multiselect__input form-control" :placeholder="placeholder"
                       v-model="inputValue" readonly @click="toggleDropdown">
                <div class="input-group-text text-secondary" @click="toggleDropdown">
                    <i class="bi-caret-down"/>
                </div>
            </div>
            <div class="multiselect__dropdown" v-if="dropdownVisible">
                <ul class="list-group overflow-y-auto">
                    <li class="list-group-item list-group-item-action d-flex"
                        v-for="option in _options"
                        :key="option.id">
                        <input class="form-check-input ms-0 me-2" type="checkbox" :id="'multiselect_option' + option.id"
                               :value="option" v-model="chosenOptions" @change="onOptionSelected">
                        <label class="form-check-label flex-grow-1"
                               :for="'multiselect_option' + option.id">{{ option.value }}</label>
                    </li>
                </ul>
            </div>
        </div>
    </detect-outside-click>
</template>

<script>
import DetectOutsideClick from "./DetectOutsideClick.vue";
import {toRaw} from "vue";

export default {
    name: "Multiselect",
    components: {DetectOutsideClick},

    emits: ['onSelected', 'update:modelValue'],

    props: {

        modelValue: {
            type: Array,
            required: true,
        },

        placeholder: {
            type: String,
            required: true
        },

        options: {
            type: Array,
            required: true
        },

        selected: {
            type: Array,
            required: false,
        },

    },

    data() {
        return {
            //chosenOptions: this.selected || [],
            dropdownVisible: false
        }
    },

    computed: {

        _options() {
            return this.options.sort((o1, o2) => {
                if(o1.value > o2.value) return 1;
                if(o1.value === o2.value) return 0;
                if(o1.value < o2.value) return -1;
            })
        },

        chosenOptions: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit("update:modelValue", value);
            },
        },

        inputValue() {
            return this.chosenOptions.map(o => o.value).join(', ');
        },
    },

    methods: {
        toggleDropdown() {
            this.dropdownVisible = !this.dropdownVisible;
        },

        onOutsideClick() {
            this.dropdownVisible = false;
        },

        onOptionSelected() {
            this.$emit("update:modelValue", this.chosenOptions.map(toRaw));
            //this.$emit('onSelected', this.chosenOptions.map(toRaw));
        }
    }

}
</script>

<style scoped>

.multiselect {
    position: relative;
}

.multiselect__input {

}

.input-group-text {
    max-height: 43px;
    cursor: pointer;
}


.multiselect__dropdown {
    position: absolute;
    width: 100%;
    left: 0;
    top: 40px;
    z-index: 1000;
    border: 1px solid var(--bs-secondary-bg-subtle);
    border-radius: var(--bs-border-radius);
}

.multiselect__option {

}

.list-group {
    max-height: 272px;
}

.list-group-item {
    padding-left: 9px;
    border-left: 0;
    border-right: 0;
    border-top: 0;
}

.list-group-item:first-child, .list-group-item:last-child {
    border-radius: 0;
}

.list-group-item > * {
    cursor: pointer;
}

/* width */
::-webkit-scrollbar {
    width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #888;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #929292;
}

</style>

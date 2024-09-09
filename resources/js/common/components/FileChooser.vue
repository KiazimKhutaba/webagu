<template>
    <div class="d-inline-block">
        <label :class="['btn btn-sm', this.class]" :for="id">
            {{ caption }}
            <!--            <progress v-show="loading" :value="loadingProgress" max="100"></progress>-->
        </label>
        <input :id="id" :accept="accept" :disabled="disabled" :name="id" type="file" @change="onFileInputChange" />
    </div>
</template>

<script>

export default {
    name: "FileChooser",
    emits: ['onFileChoose'],
    props: {
        id: {
            type: String,
            default: 'choose-file'
        },
        caption: {
            type: String,
            required: true
        },
        accept: {
            type: String,
            required: false,
            default: '*/*'
        },
        'class': {
            type: String,
            default: 'btn-outline-primary'
        },
        disabled: {
            type: Boolean,
            default: false
        },
        dest: {
            type: String,
            validator(value) {
                return ['uploads', 'avatars'].includes(value);
            }
        }
    },
    data() {
        return {
            loading: false,
            loadingProgress: 0
        }
    },
    methods: {
        onFileInputChange(e) {
            this.$emit('onFileChoose',e.target.files[0]);
        }
    }
}
</script>

<style scoped>

div {

}

.ml-10px {
    margin-left: 10px;
}

label[for="choose-file"] {
    cursor: pointer;
}

input[type='file'] {
    /* opacity: 0;*/
    display: none;
    position: absolute;
    z-index: -1;
}

progress {
    display: block;
    height: 10px;
    width: 100%;
}

</style>

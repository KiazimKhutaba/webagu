<template>
    <div class="d-inline-block">
        <label for="upload-file" :class="['btn btn-sm', this.class]">
            {{ caption }}
<!--            <progress max="100" :value="loadingProgress" v-show="loading"></progress>-->
        </label>
        <input type="file" name="file" id="upload-file" @change="onFileOpened" :accept="this.accept" :disabled="disabled" />
    </div>
</template>

<script>

export default {
    name: "FileOpen",
    emits: ['onOpened'],
    props: {
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
        }
    },
    data() {
      return {
          loading: false,
          loadingProgress: 0
      }
    },
    methods: {
        async onFileOpened(e) {
            const file = e.target.files[0];
            this.$emit('onOpened', file);
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

label[for="file-update"] {
    cursor: context-menu;
}

#upload-file {
    /* opacity: 0;*/
    display: none;
    position: absolute;
    z-index: -1;
}

progress {
    display: block;
    height: 10px;
}

</style>

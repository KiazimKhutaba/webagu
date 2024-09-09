<template>
    <div class="slider">
        <div class="zoomer">
            <input type="range" class="form-range" min="1" v-model="image_zoom">
        </div>
        <div class="main-image" :style="{maxWidth: image_zoom_value}">
            <button @click="prevImage" class="nav-btn">Prev</button>
            <img v-if="is_image(current_file?.generated_name)" :src="upload_path(current_file)" class="img-fluid" :alt="current_file?.original_name"/>
            <a v-else target="_blank" :href="upload_path(current_file?.generated_name)" >
                <img src="/images/file-type-01.png" alt="file" />
            </a>
            <button @click="nextImage" class="nav-btn">Next</button>
        </div>
        <div class="thumbnails">
            <span  v-for="(file, index) in images">
                <img
                    v-if="is_image(file?.generated_name)"
                    :key="index"
                    :src="upload_path(file)"
                    :class="{ active: currentIndex === index }"
                    @click="currentIndex = index"
                    :alt="file?.generated_name"
                />
                <img v-else
                     @click="currentIndex = index"
                     src="/images/file-type-01.png"
                     class="img-fluid img-thumbnail image:w100:px me-2" alt="task file" />
            </span>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        images: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            currentIndex: 0,
            image_zoom: 1.5,
        };
    },

    mounted() {
        document.addEventListener('keyup', this.keyboardImageSwitch);
    },

    beforeUnmount() {
        document.addEventListener('keyup', this.keyboardImageSwitch);
    },

    computed: {
        image_zoom_value() {
            return (500 * this.image_zoom) + 'px';
        },

        current_file() {
            return this.images[this.currentIndex];
        }
    },

    methods: {
        prevImage() {
            this.currentIndex = (this.currentIndex + this.images.length - 1) % this.images.length;
        },
        nextImage() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        },
        keyboardImageSwitch(e) {
            switch (e.code) {
                case 'ArrowLeft':
                    this.prevImage();
                    break;
                case 'ArrowRight':
                    this.nextImage();
                    break;
                case 'NumpadAdd':
                    this.image_zoom += 5;
                    break;
                case 'NumpadSubtract':
                    this.image_zoom -= 5;
                    break;
            }
        }
    },
};
</script>

<style scoped>
.slider {
    text-align: center;
}

.main-image {
    display: inline-block;
    max-width: 800px;
    margin: 0 auto;
}

.nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
}

.nav-btn:hover {
    background-color: rgba(0, 0, 0, 0.8);
}

.nav-btn:nth-child(1) {
    left: 0;
}

.nav-btn:nth-child(3) {
    right: 0;
}

.thumbnails {
    display: flex;
    justify-content: center;
    margin-top: 10px;
}

.thumbnails img {
    width: 50px;
    height: 50px;
    margin: 0 5px;
    cursor: pointer;
    border: 2px solid transparent;
}

.thumbnails img.active {
    border-color: red;
}
</style>

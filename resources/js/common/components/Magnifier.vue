<template>
    <div class="img-container" @mousemove="moveMagnifier" @mouseleave="hideMagnifier">
        <img ref="image" :src="imageSrc" alt="Sample Image">
        <div v-show="showMagnifier" class="magnifier" :style="magnifierStyle"></div>
    </div>
</template>

<script>
export default {
    props: {
        imageSrc: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            showMagnifier: false,
            magnifierX: 0,
            magnifierY: 0
        };
    },
    computed: {
        magnifierStyle() {
            return {
                left: `${this.magnifierX}px`,
                top: `${this.magnifierY}px`,
                backgroundImage: `url('${this.imageSrc}')`,
                backgroundSize: `${this.imageWidth * 2}px ${this.imageHeight * 2}px`,
                backgroundPosition: `-${this.magnifierX * 2}px -${this.magnifierY * 2}px`
            };
        },
        imageWidth() {
            return this.$refs.image ? this.$refs.image.width : 0;
        },
        imageHeight() {
            return this.$refs.image ? this.$refs.image.height : 0;
        }
    },
    methods: {
        moveMagnifier(event) {
            const img = this.$refs.image;
            const { left, top, width, height } = img.getBoundingClientRect();
            const x = event.clientX - left;
            const y = event.clientY - top;

            if (x > 0 && x < width && y > 0 && y < height) {
                this.showMagnifier = true;
                this.magnifierX = x - this.$refs.magnifier.offsetWidth / 2;
                this.magnifierY = y - this.$refs.magnifier.offsetHeight / 2;
            } else {
                this.showMagnifier = false;
            }
        },
        hideMagnifier() {
            this.showMagnifier = false;
        }
    }
};
</script>

<style scoped>
.img-container {
    position: relative;
    display: inline-block;
}

img {
    width: 100%;
    max-width: 500px;
}

.magnifier {
    position: absolute;
    border: 3px solid #000;
    border-radius: 50%;
    cursor: none;
    width: 100px;
    height: 100px;
    overflow: hidden;
    display: none;
    z-index: 10;
}

.magnifier img {
    position: absolute;
}
</style>

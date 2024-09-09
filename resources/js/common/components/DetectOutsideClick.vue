<template>
    <div ref="root">
        <slot />
    </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'

const emit = defineEmits(['detect'])
const root = ref(null)

const onClick = (e) => {
    if (e.target === root.value || root.value.contains(e.target)) {
        return
    }
    emit('detect')
}

onMounted(() => {
    document.body.addEventListener('click', onClick)
})

onUnmounted(() => {
    document.body.removeEventListener('click', onClick)
})
</script>

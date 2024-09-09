<template>
  <template v-if="status">
    <h2 class="fs-2">Загрузка данных...</h2>
  </template>
  <template v-else>
    <slot />
  </template>
</template>

<script>
export default {
  name: "QLoading",
  props: {
    status: {
      type: Boolean
    }
  }
}
</script>

<style scoped>

</style>

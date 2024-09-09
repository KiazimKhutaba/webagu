<template>

    <div class="card-text py-4 text-center border border-success border-left" v-if="replies[type]">
        <h5 class="h5 text-muted">{{ replies[type] }}</h5>
    </div>

    <slot v-else />
</template>

<script>
export default {
    name: "QuestionTypeReply",
    props: {
        type: {
            type: String,
        }
    },

    data: () => ({
        replies: {
            'text' : 'Пользователь должен ввести текст в поле ввода в качестве ответа',
            'image' : 'Пользователь должен загрузить изображение в качестве ответа'
        }
    })
}
</script>

<style scoped>

</style>

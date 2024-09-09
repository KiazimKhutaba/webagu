<template>
    <div class="card h-100 shadow post-card" :class="{'text-bg-success': +post?.views_count === 0}">
        <div class="card-header d-flex">
            <div class="text-secondary post-card_header" style="font-size: 0.8rem">
                {{ dt_format2(post?.created_at) }}
            </div>
        </div>
        <div class="card-body">
            <div class="card-title fw-bold">#{{ post?.id }} {{ post.title }}</div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <router-link :to="href('posts.show', { id: post?.id })" class="post-card_link">Открыть</router-link>
            <a href="#" class="text-danger" v-show="is_admin()" :data-post-id="post?.id" @click="onRemove">Удалить</a>
        </div>
    </div>
</template>

<script>
export default {
    name: "PostCard",
    props: {
        post: {
            type: Object,
            required: true
        }
    },

    emits: ['onPostRemove'],

    methods: {
        onRemove(e) {
            this.$emit('onPostRemove', e.target.dataset.postId);
        }
    }
}
</script>

<style scoped>
.card-title {
    font-size: 1.1rem;
}

.text-bg-success .post-card_link,
.text-bg-success .post-card_header {
    color: #fff !important;
}


</style>

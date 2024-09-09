<template>
    <dashboard-wrapper>
        <template v-if="!isFormOpen">
            <page-header :caption="$route.meta.caption">
                <button v-if="is_admin()" class="btn btn-sm btn-outline-primary d-flex" @click="openForm">
                    <i class="bi bi-plus-lg me-1"></i>Добавить сообщение
                </button>
            </page-header>

            <div class="mx-1">
                <div class="row row-gap-3 pb-3" v-if="posts?.length > 0">
                    <div class="col-md-3" v-for="post in posts">
                        <post-card :post="post" @on-post-remove="onPostRemove" @on-edit=""/>
                    </div>
                </div>
            </div>
        </template>

        <template v-else>
            <page-header caption="Новое сообщение">
                <button class="btn btn-sm btn-outline-primary" @click="closeForm">Закрыть форму</button>
            </page-header>

            <post-creation-form :is-editing-mode="false" @on-post-created="onPostCreated" />
        </template>

    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import PostCard from "@/modules/posts/components/PostCard.vue";
import PostCreationForm from "@/modules/posts/components/PostCreationForm.vue";
import {postsService} from "@/modules/posts/api/PostsService.js";
import {mapActions} from "vuex";

export default {
    name: "PostsPage",
    components: {PostCreationForm, PostCard, PageHeader, DashboardWrapper},

    data: () => ({
        posts: [],
        isFormOpen: false,
    }),

    async mounted() {
        this.posts = await this.getPosts();
    },

    methods: {

        ...mapActions('appSettingsStore', ['getAndSetUnreadPostsCount']),

        onPostCreated(post) {
            this.posts = [post, ...this.posts];
            this.closeForm();
            this.getAndSetUnreadPostsCount();
        },

        onPostRemove(post_id) {
            if(confirm('Вы действительно хотите удалить сообщение с номером #' + post_id + '?')) {
                postsService().remove(post_id).then(() => {
                    this.posts = this.posts.filter(post => +post.id !== +post_id);
                })
            }
        },

        async getPosts() {
            try {
                const {data} = await postsService().pluck(['id', 'title', 'created_at']);
                return data;
            }
            catch (e) {
                // alert(e);
                console.error(e);
            }
        },

        openForm() {
            this.isFormOpen = true;
        },

        closeForm() {
            this.isFormOpen = false;
        }

    }
}
</script>

<style scoped>

</style>

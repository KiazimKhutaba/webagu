<template>
    <dashboard-wrapper>
        <page-header :caption="caption">
            <router-link class="btn btn-sm btn-outline-primary" :to="href('posts.index')">Назад</router-link>
        </page-header>

        <div class="mx-1 mb-3">
            <div class="row mb-3 bg-warning-subtle rounded-2 py-2">
                <div class="col-md-6">
                    <div class="text-secondary">Опубликовано: {{ dt_format(post?.created_at) }}</div>
                </div>
                <div class="col-md-6 text-md-end">
                    <span v-show="is_admin()" class="text-secondary text-decoration-underline cursor-pointer" @click="toggleViewHistory">
                        {{ toggleViewHistoryCaption }}
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col" v-if="is_admin() && postViewHistoryFragmentVisible">
                    <post-view-history-fragment :views="views" />
                </div>
                <div class="col" v-else>
                    <div class="post-content" v-html="post?.content" v-if="post" @click="onPostContentClick"></div>
                    <div v-else>Нет данных</div>
                </div>
            </div>
        </div>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import PostViewHistoryFragment from "@/modules/posts/components/PostViewHistoryFragment.vue";
import {mapActions} from "vuex";
import {postsService} from "@/modules/posts/api/PostsService.js";
export default {
    name: "PostShowPage",
    components: {PostViewHistoryFragment, PageHeader, DashboardWrapper},

    data: () => ({
        post: null,
        views: [],
        postViewHistoryFragmentVisible: false,
    }),

    async mounted() {
        const {data} = await postsService().getById(this.$route.params?.id);
        this.post = data?.post;
        this.views = data?.views_history;

        await this.getAndSetUnreadPostsCount();
        // document.querySelectorAll('.post-content img').forEach(img => img.style.width = '300px');
    },

    computed: {
        caption() {
            return `#${this.post?.id} ${this.post?.title}` || this.$route.meta.caption;
        },

        toggleViewHistoryCaption() {
            return this.postViewHistoryFragmentVisible ? 'Закрыть историю'  : 'История просмотров';
        }
    },

    methods:{

        ...mapActions('appSettingsStore', ['getAndSetUnreadPostsCount']),

        toggleViewHistory() {
            this.postViewHistoryFragmentVisible = !this.postViewHistoryFragmentVisible;
        },

        /**
         *
         * @param e {PointerEvent}
         */
        onPostContentClick(e) {
            console.log(e.target)
        },
    },
}
</script>

<style scoped>
.post-content img {
    width: 300px;
    max-width: 300px !important;
}
</style>

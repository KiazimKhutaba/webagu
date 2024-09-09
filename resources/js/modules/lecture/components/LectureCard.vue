<template>
    <div class="card h-100 mb-3 shadow lecture-card" :data-id="lecture.id" :data-title="lecture.title">
        <div class="card-body p-4">
            <h5 class="card-title">{{ lecture.title }}</h5>
            <p class="card-text" v-html="lecture.excerpt"></p>
        </div>
        <div class="card-footer bg-success">
            <div class="row text-center">
                <div class="col-lg-4">
                    <router-link :to="`/lecture/${lecture.id}`" class="text-decoration-none">Открыть</router-link>
                </div>
                <div class="col-lg-4" v-show="is_admin()"><div @click="edit">Редактировать</div></div>
                <div class="col-lg-4" v-show="is_admin()"><div @click="remove">Удалить</div></div>
            </div>
        </div>
    </div>
</template>

<script>
import {lecturesService} from "@/modules/lecture/api/LecturesService.js";

export default {
    name: "LectureCard",
    emits: ['onRemove', 'onEdit'],
    props: {
        pageId: {
            type: Number,
            required: false
        },
        lecture: {
            type: Object,
            required: true
        }
    },
    methods: {

        edit() {
            this.$emit('onEdit', this.lecture);
        },

        async remove(e) {
            const {id, title} = e.target.closest('.card').dataset;

            if (confirm(`Вы действительно хотите удалить лекцию "${title}" ?`)) {
                const {data} = await lecturesService().remove(id);
                this.$emit('onRemove', data);
            }
        }
    }
}
</script>

<style scoped>

.lecture-card {
    --text-color: #fff;
}

.card-footer > div {
    margin-right: 10px;
    cursor: pointer;
}

.card-footer {
    color: var(--text-color);
}

.card-footer a {
    color: var(--text-color);
}

.card-footer a:hover {
    text-decoration: underline #fff;
}

</style>

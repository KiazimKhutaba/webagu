<template>
    <dashboard-wrapper :has-content="hasContent" :is-loading="isLoading">

        <template v-if="!isFormOpen">
            <page-header caption="Материалы по предмету">
                <button v-if="is_admin()" class="btn btn-sm btn-outline-primary d-flex" @click="openForm">
                    <i class="bi bi-plus-lg me-1"></i>Добавить материал
                </button>
            </page-header>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-4 mb-3" v-for="lecture in lectures">
                    <lecture-card :lecture="lecture" @on-remove="onLectureRemove" @on-edit="onLectureEdit" />
                </div>
            </div>
        </template>

        <template v-else>
            <page-header :caption="captionValue">
                <button class="btn btn-sm btn-outline-primary" @click="closeForm">Закрыть форму</button>
            </page-header>
            <create-lecture-form @created="onLectureCreate" :lecture="lecture" :is-editing-mode="isLectureEditing" />
        </template>

    </dashboard-wrapper>
</template>

<script>

import PageHeader from "@/common/components/PageHeader.vue";
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import LectureCard from "@/modules/lecture/components/LectureCard.vue";
import CreateLectureForm from "@/modules/lecture/components/CreateLectureForm.vue";
import {lecturesService} from "@/modules/lecture/api/LecturesService.js";

export default {
    name: "Dashboard",
    components: {CreateLectureForm, LectureCard, DashboardWrapper, PageHeader},
    data() {
        return {
            isFormOpen: false,
            lectures: [],
            isLectureEditing: false,
            lecture: {},
            isLoading: false,
            hasContent: false
        }
    },
    computed: {
        captionValue() {
            return this.isLectureEditing ? 'Обновить материал' : 'Добавить лекцию';
        }
    },
    async mounted()
    {
        try {

            this.isLoading = true;
            const { data } = await lecturesService().pluck(['id', 'title', 'excerpt' ]);

            this.lectures = data;
            this.hasContent = true;
        }
        catch (e) {
            this.hasContent = false;
            console.log(e)
        }
        finally {
            this.isLoading = false;
        }


    },
    methods: {

        onLectureRemove(lecture) {
            this.lectures = this.lectures.filter(_lecture => +_lecture.id !== +lecture.id);
        },

        closeForm() {

            if(confirm('Вы действительно хотите закрыть форму, все введенные данные будут потеряны?')) {
                this.isFormOpen = false;
            }
        },

        openForm() {
            this.isFormOpen = true;
            this.isLectureEditing = false;
        },

        onLectureCreate(lecture) {
            this.lectures = [...this.lectures, lecture];
            this.isFormOpen = false;
        },

        onLectureEdit(lecture) {
            this.isFormOpen = true;
            this.isLectureEditing = true;
            this.lecture = lecture;
        }
    }
}
</script>

<style scoped>

</style>

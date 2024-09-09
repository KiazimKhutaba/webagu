<template>
    <div class="mx-1">
        <q-table :cols="cols" :rows="tasks" v-if="tasks.length > 0">
            <template #item="{item}">
                <td>{{ item.lecture_title }}</td>
                <td>[#{{ item.task_id }}] {{ item.task_title }}</td>
                <td>
                    <span :class="`badge bg-${review(item.review_status)?.clazz} d-inline-block`">
                        {{ review(item.review_status)?.caption }}
                    </span>
                </td>
                <td>{{ dt_format2(item.created_at) || 'Нет' }}</td>
                <td>
                    <router-link v-if="is_admin() && item.student_id !== null" :to="`/tasks/${item.task_id}?student_id=${item.student_id}`" target="_blank">Посмотреть</router-link>
                    <router-link v-else :to="`/tasks/${item.task_id}`" target="_blank">Посмотреть</router-link>
                </td>
            </template>
        </q-table>

        <h3 v-else>Нет данных</h3>
    </div>
</template>

<script>
import {tasksService} from "@/modules/task/api/TasksService.js";
import QTable from "@/common/components/QTable.vue";
import {defaultType, getReviewByStatusName} from "@/utils/reviewTypes.js";
import store from "@/store/index.js";

export default {
    name: "UserTasksProgress",
    components: {QTable},
    props: {
        id: {
            type: Number,
            //required: true
        }
    },

    data: () => ({
        tasks: [],
        cols: {
            //task_id: 'Id',
            lecture_title: 'Тема',
            task_title: 'Задание',
            review_status: 'Статус',
            created_at: 'Сдана',
            actions: 'Действия'
        },
        student_id: 0
    }),


    async mounted() {
        this.tasks = await this.getProgressByTasks(this.userId);
    },

    computed: {
        userId() {
            return this.$route.params.id || store.getters["authStore/user"].id;
        }
    },

    watch: {
        /*async id(newValue, oldValue) {
            if(newValue !== oldValue) {
                this.tasks = await this.getProgressByTasks(this.id);
                this.student_id = this.id;
            }
        }*/
    },

    methods: {

        async getProgressByTasks(id) {
            //if(!id) return [];
            const {data} = await tasksService().getProgressByTasksForUser(id);
            return data;
        },

        review(status) {
            return getReviewByStatusName(status) || defaultType;
        },
    }
}
</script>

<style scoped>

</style>

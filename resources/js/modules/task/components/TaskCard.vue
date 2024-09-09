<template>
    <div class="card h-100 mb-3 shadow" :data-id="obj.id" :data-title="obj.title">

        <div :class="`card-header bg-${review.clazz}-subtle`" v-if="review">
            <div :class="`badge bg-${review.clazz} d-inline-block`" title="Номер задачи">#{{ obj.id }}</div>&nbsp;
            <div :class="`badge bg-${review.clazz} d-inline-block`">{{ review.caption }}</div>
        </div>

        <!--<div class="card-header d-flex justify-content-between align-items-center" v-else>
            &lt;!&ndash; <input type="datetime-local" class="form-control form-control-sm w-50 TaskDueDate" title="Task due date" v-model="created"> &ndash;&gt;
        </div>-->

        <div class="card-body">
            <h6 class="h6 text-secondary mb-3" style="font-size: 0.8em">{{ obj.lecture_title }}</h6>
            <h5 class="card-title mt-1 mb-4">
                #{{ obj.id }} {{ obj.title }}
            </h5>
            <!--            <h6 class="card-title mt-1 mb-4">{{ obj.lecture_title }}</h6>-->
            <!-- <p class="card-text" v-html="obj.description" v-if="obj.description"></p> -->
        </div>

        <div class="card-footer">
            <div :class="{'d-flex justify-content-between': is_admin(), 'd-flex justify-content-center': !is_admin()}">
                <div>
                    <router-link :to="`/${url}/${obj.id}`" class="text-decoration-none">Открыть</router-link>
                </div>

                <div v-if="is_admin()">
                    <div class="dropdown">
                        <a class="text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                           data-bs-auto-close="outside"
                           aria-expanded="false">
                            Действия
                        </a>

                        <ul class="dropdown-menu">
                            <li><span class="dropdown-item" @click="edit">Редактировать</span></li>
                            <li><span class="dropdown-item" @click="remove">Удалить</span></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <div class="dropdown-item">
                                    <switcher :id="'taskIsVisible' + obj.id" v-model:checked="is_visible">
                                        {{ is_task_visible ? 'Доступно' : 'Недоступно' }}
                                    </switcher>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-item">
                                    <switcher :id="'taskIsSeminar' + obj.id" v-model:checked="is_seminar"
                                              @change="onIsSeminarSwitched">
                                        {{ is_task_seminar ? "Семинар" : "Лекция" }}
                                    </switcher>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {defaultType, getReviewByStatusName} from "@/utils/reviewTypes.js";
import Switcher from "@/common/components/Switcher.vue";

export default {
    name: "TaskCard",
    components: {Switcher},
    emits: ['onRemove', 'onEdit', 'onToggleVisibility', 'onToggleIsSeminar'],
    props: {
        url: {
            type: String,
            required: true
        },
        obj: {
            type: Object,
            required: true
        }
    },

    data: () => ({
        is_task_visible: false,
        is_task_seminar: false
    }),

    /*mounted() {
        this.is_task_visible = this.obj.is_visible;
    },*/

    computed: {

        review() {
            if (this.is_admin()) return null;
            return getReviewByStatusName(this.obj.review_status) || defaultType;
        },

        is_visible: {
            get() {
                // return this.obj.is_visible ? 'true' : 'false';
                // v-model directive accept true or false values for working with checkbox
                this.is_task_visible = this.obj.is_visible;
                return this.obj.is_visible;
            },
            set(value) {
                this.is_task_visible = value;
                this.$emit('onToggleVisibility', {id: this.obj.id, is_visible: value});
            }
        },

        is_seminar: {
            get() {
                this.is_task_seminar = this.obj.is_seminar;
                return this.obj.is_seminar;
            },
            set(value) {
                this.is_task_seminar = value;
                this.$emit('onToggleIsSeminar', {id: this.obj.id, is_seminar: value});
            }
        },

        created: {
            get() {
                return this.obj.created_at;
            },
            set(value) {
                console.log(value)
            }
        }
    },

    methods: {

        onIsSeminarSwitched(e) {
            // console.log(e.target.checked);
        },

        edit() {
            this.$emit('onEdit', this.obj);
        },

        remove() {
            this.$emit('onRemove', this.obj);
        }
    },

    /*watch: {
        is_task_visible(oldValue, newValue) {
            console.log(oldValue, newValue);
            // this.$emit('onToggleVisibility', { id: this.obj.id, is_visible: newValue });
        }
    }*/
}
</script>

<style scoped>
.card-footer > div {
    margin-right: 10px;
    cursor: pointer;
}

.card-text {
    /*max-height: 200px;
    overflow: hidden;
    text-overflow: ' [...]';*/
}

.TaskDueDate {
    visibility: hidden;
    opacity: 0;
    transition: visibility 0s, opacity 0.5s linear;
}

.card-header:hover .TaskDueDate {
    visibility: visible;
    opacity: 1;
}

.dropdown-item {
    font-size: 0.9rem;
}

</style>

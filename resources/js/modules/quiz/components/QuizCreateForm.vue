<template>
    <form class="row g-3 mb-2" @submit.prevent="handleSubmit">

        <div class="col-md-12">
            <label for="nameTextbox" class="form-label">Название</label>
            <input type="text" class="form-control" id="nameTextbox" ref="nameTextbox" required />
        </div>

        <div class="col-md-4">
            <label for="numberOfQuestions" class="form-label" v-if="is_midterm">Количество вопросов на тему</label>
            <label for="numberOfQuestions" class="form-label" v-else>Количество вопросов</label>
            <input type="number" class="form-control" id="numberOfQuestions" ref="numberOfQuestions" min="1" required />
        </div>

        <div class="col-md-4">
            <label for="numberOfTasks" class="form-label" v-if="is_midterm">Количество задач на тему</label>
            <label for="numberOfTasks" class="form-label" v-else>Количество задач</label>
            <input type="number" class="form-control" id="numberOfTasks" ref="numberOfTasks" min="0" required />
        </div>

        <div class="col-md-4">
            <label for="numberOfQuizzes" class="form-label">Количество тестов</label>
            <input type="number" class="form-control" id="numberOfQuizzes" ref="numberOfQuizzes" min="0" required />
        </div>

        <div class="col-md-12">
            <label for="selected_themes" class="form-label">Темы</label>
            <select id="selected_themes" class="form-select" multiple v-model="selectedThemes" required>
                <option :key="theme.id" :value="theme.id" v-for="theme in themes">{{ theme.title }}</option>
            </select>
        </div>

        <div class="col-md-12" v-if="students.length > 0">
            <label for="assigned_to" class="form-label">Для кого</label>
            <select id="assigned_to" class="form-select" multiple v-model="assignedTo" required>
                <option :value="0" selected>Для всех</option>
                <optgroup :label="department_translate(department)" v-for="(group, department) in studentsGroupedByDepartment">
                    <option :value="student.id" v-for="student in group">{{ student.lastname }} {{ student.firstname }}</option>
                </optgroup>
            </select>
        </div>

        <div class="col-md-12">
            <label for="selected_quiz_type" class="form-label">Тип теста</label>
            <select id="selected_quiz_type" class="form-select" v-model="selected_quiz_type" required>
                <option :value="QuizType.None" selected disabled>Выберите тип теста</option>
                <option :key="item.type" :value="item.type" v-for="item in quiz_types">{{ item.value }}</option>
            </select>
        </div>

        <div class="col-md-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="isAvailableCheckbox" ref="isAvailable" required />
                <label class="form-check-label" for="isAvailableCheckbox">
                    Доступно
                </label>
            </div>
        </div>

        <div class="col-md-12 d-none">
            <div class="form-check mr-sm-2">
                <input class="form-check-input" type="checkbox" id="questionsCanBeRegeneratedId" ref="canRegenerate">
                <label class="form-check-label" for="questionsCanBeRegeneratedId">
                    Вопросы могут меняться
                </label>
            </div>
        </div>

        <div class="col-md-12 d-none">
            <div class="form-check mb-3 mr-sm-2">
                <input class="form-check-input" type="checkbox" id="isMidterm" ref="isMidterm" v-model="is_midterm">
                <label class="form-check-label cursor-pointer" for="isMidterm">
                    Это зачет
                </label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-sm btn-primary">Создать</button>
        </div>
    </form>
</template>

<script>
import {toRaw} from "vue";
import {v4 as uuidv4} from "uuid";
import {lecturesService} from "@/modules/lecture/api/LecturesService.js";
import {usersService} from "@/modules/users/api/UsersService.js";
import groupBy from "@/utils/groupBy.js";
import {QuizType, quizTypeTranslationsRu} from "@/modules/quiz/utils/QuizType.js";

export default {
    name: "QuizCreateForm",
    emits: ['onQuizData'],

    props: {

    },

    data() {
        return {
            selectedThemes: [],
            assignedTo: [],
            themes: [],
            students: [],
            //is_midterm: false,
            selected_quiz_type: QuizType.None
        }
    },

    async mounted() {
        this.themes = await this.getThemes();
        this.students = await this.getStudents();
    },

    computed: {
        QuizType() {
            return QuizType
        },
        studentsGroupedByDepartment() {
            return groupBy(this.students, 'department');
        },

        quiz_types() {
            return quizTypeTranslationsRu;
        },

        is_midterm() {
            return this.selected_quiz_type === QuizType.Midterm;
        }
    },

    methods: {

        async getStudents() {
            try {
                const {data} = await usersService().getStudents();
                return data;
            } catch (e) {
                console.error(e);
                alert(e);
            }
        },

        async getThemes() {
            let _data = [];

            try {
                const {data} = await lecturesService().pluck(['id', 'title']);
                _data = data;
            } catch (e) {
                console.log(e);
            }

            return _data;
        },

        handleSubmit() {

            if(this.selected_quiz_type === QuizType.None) {
                 alert('Аԥышәара ахкы алхтәуп!');
                 return;
            }

            const title = this.$refs.nameTextbox.value;
            const number_of_questions = this.$refs.numberOfQuestions.value;
            const number_of_tasks = this.$refs.numberOfTasks.value;
            const number_of_quizzes = this.$refs.numberOfQuizzes.value;
            const themes = toRaw(this.selectedThemes);
            const is_available = this.$refs.isAvailable.checked ? '1' : '0';
            //const can_regenerate = this.$refs.canRegenerate.checked ? '1' : '0';
            const can_regenerate = this.selected_quiz_type === QuizType.Training ? '1' : '0';
            //const is_midterm = this.$refs.isMidterm.checked ? '1' : '0';
            const is_midterm = this.selected_quiz_type === QuizType.Midterm ? '1' : '0';
            const assigned_to = toRaw(this.assignedTo);
            const type = this.selected_quiz_type;


            const theme_names = toRaw(this.themes.filter(th => themes.includes(+th.id)).map(th => th.title));

            const quiz = {
                uuid: uuidv4(),
                title,
                number_of_questions,
                number_of_tasks,
                number_of_quizzes,
                themes,
                theme_names,
                is_available,
                can_regenerate,
                is_midterm,
                assigned_to,
                type
            };

            this.$emit('onQuizData', quiz);
        }
    }
}
</script>

<style scoped>
.form-label {
    font-weight: bold;
}
</style>

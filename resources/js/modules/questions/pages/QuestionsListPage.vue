<template>
    <dashboard-wrapper>

        <page-header caption="Список вопросов"/>

        <section>
            <div class="mb-4 action-buttons">
                <button class="btn btn-sm btn-outline-success" @click="toggleQuestionCreateForm">
                    {{ isQuestionCreateFormOpen ? 'Закрыть форму' : 'Добавить новый вопрос' }}
                </button>
                <button class="btn btn-sm btn-outline-warning" @click="toggleMassCreationForm">
                    {{ isMassCreationFormOpen ? 'Закрыть форму' : 'Добавить много вопросов' }}
                </button>
                <file-open caption="Импортировать вопросы" accept="application/json" @on-opened="onQuestionsImport"
                           v-if="!isQuestionCreateFormOpen"/>
                <button class="btn btn-sm btn-outline-info" v-if="!isQuestionCreateFormOpen" @click="onQuestionsExport">
                    Экспортировать вопросы
                </button>
            </div>

            <template v-if="isQuestionCreateFormOpen">
                <question-create-form @create="onQuestionCreate" @update="onQuestionUpdate" @remove="onRemoveQuestion"
                                      :question="question" :themes="themes"/>
            </template>

            <template v-else-if="isMassCreationFormOpen">
                <mass-questions-add-form @on-data="onMassQuestionsCreate"/>
            </template>


            <div class="table-responsive" v-if="!isQuestionCreateFormOpen && !isMassCreationFormOpen">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Название</th>
                        <th scope="col">
                            <select class="form-select form-select-sm" v-model="_filterTaskType" title="Фильтр по типу">
                                <option value="-1" selected disabled>Тип</option>
                                <option value="0">Все вопросы</option>
                                <option value="question">Вопрос</option>
                                <option value="task">Задача</option>
                                <option value="quiz">Тест</option>
                            </select>
                        </th>
                        <th scope="col">
                            <select class="form-select form-select-sm" v-model="_filterTaskTheme">
                                <option value="-1" selected disabled>Тема</option>
                                <option value="0">Все темы</option>
                                <option :value="lecture.id" v-for="lecture in themes">{{ lecture.title }}</option>
                            </select>
                        </th>
                        <th v-if="is_admin()" scope="col">Открыт</th>
                        <th v-if="is_admin()" scope="col">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="cursor-pointer" v-for="(question, counter) in questions"
                        :data-id="question.id"
                        :data-counter="++counter"
                    >
                        <th scope="row">{{ counter }}</th>
                        <td>{{ question.title }}</td>
                        <td>
                            <select class="form-select form-select-sm" :value="question.type" :data-qid="question.id"
                                    @change="onQuestionTypeChange">
                                <option value="question">Вопрос</option>
                                <option value="task">Задача</option>
                                <option value="quiz">Тест</option>
                            </select>

                        </td>
                        <td>
                            <select class="form-select form-select-sm" :value="question.theme" :data-qid="question.id"
                                    @change="onQuestionThemeChange">
                                <option v-for="theme in themes" :value="theme.id">
                                    {{ theme.title }}
                                </option>
                            </select>
                            <!-- {{ question._theme?.title }}-->
                        </td>
                        <td v-if="is_admin()" class="text-center">
                            <input type="checkbox" class="form-check-input" :value="question.id" :checked="question.is_visible" @change="onQuestionVisibilityChange">
                            <!-- <switcher :id="question.id + ''" v-model:checked="question.is_visible" />-->
                        </td>
                        <td v-if="is_admin()">
                            <a href="#" data-action="show" @click="onFormShow">Смотреть</a> |
                            <a href="#" data-action="delete" @click="onRemove">Удалить</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="row" v-if="!isQuestionCreateFormOpen && !isMassCreationFormOpen">
                <pagination :pagination="pagination"/>
            </div>

        </section>

    </dashboard-wrapper>
</template>

<script>
import QuestionCreateForm from "../components/QuestionCreateForm.vue";
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import {mapGetters, mapMutations, mapState} from "vuex";
import {questionsService} from "@/modules/questions/api/QuestionService.js";
import FileUpload from "@/common/components/FileUpload.vue";
import FileOpen from "@/common/components/FileOpen.vue";
import {v4 as uuidv4} from 'uuid'
import Pagination from "@/common/components/Pagination.vue";
import MassQuestionsAddForm from "@/modules/questions/components/MassQuestionsAddForm.vue";
import downloadFile from "@/utils/donwloadFile.js";
import Switcher from "@/common/components/Switcher.vue";
import {lecturesService} from "@/modules/lecture/api/LecturesService.js";

export default {
    name: "QuestionsListPage",
    components: {
        Switcher,
        MassQuestionsAddForm,
        Pagination,
        FileOpen,
        FileUpload,
        PageHeader,
        DashboardWrapper,
        QuestionCreateForm
    },
    data() {
        return {
            isQuestionCreateFormOpen: false,
            isQuestionsImportFormOpen: false,
            isMassCreationFormOpen: false,
            //questions: [],
            importedQuestions: [],
            question: {
                id: '',
                title: '',
                content: ''
            },
            client: {
                type: 'admin'
            },
            themes: [],
            questions: [],
            pagination: null
        }
    },
    async mounted() {
        await this.getLectures();
        await this.getQuestions({ type: this._filterTaskType, theme: this._filterTaskTheme  });
    },
    computed: {
        ...mapGetters('authStore', ['user']),

        ...mapState('questionsListStore', ['filterTaskTheme', 'filterTaskType']),

        _filterTaskType: {
            get() {
                return this.filterTaskType;
            },
            set(value) {
                this.getQuestions({ type: value, theme: this.filterTaskTheme }).then(() => {
                    this.SET_FILTER_TASK_TYPE(value);
                });
            }
        },

        _filterTaskTheme: {
            get() {
                return this.filterTaskTheme;
            },
            set(value) {
                this.getQuestions({ type: this.filterTaskType, theme: value }).then(() => {
                    this.SET_FILTER_TASK_THEME(value);
                });

            }
        }
    },
    methods: {
        ...mapMutations('questionsListStore', ['SET_FILTER_TASK_TYPE', 'SET_FILTER_TASK_THEME']),

        /*...mapActions(['getQuestions']),*/

        async onQuestionVisibilityChange(e) {
            const id = e.target.value;
            const data = { is_visible: e.target.checked };
            console.log(data);

            await questionsService().update(id, data);
        },

        onMassQuestionsCreate(data) {

            questionsService().import(data).then(() => {
                alert('Вопросы добавлены успешно!');
                this.isMassCreationFormOpen = false;
                this.questions = [...this.questions, ...data];
            }).catch(err => {
                alert(err);
                console.error(err);
            });
        },

        translateQuestionType(type) {
            return {
                'question': 'Вопрос',
                'task': 'Задача',
                'quiz': 'Тест',
            }[type] || 'None';
        },

        async onQuestionTypeChange(e) {
            const qid = +e.target.dataset.qid;
            const type = e.target.value;

            try {
                await questionsService().update(qid, { type });
            } catch (e) {
                alert(e)
            }
        },

        async onQuestionThemeChange(e) {
            const qid = +e.target.dataset.qid;
            const theme_id = +e.target.value;

            try {
                await questionsService().updateTheme(qid, theme_id);
            } catch (e) {
                alert(e)
            }

            // console.log('question ', qid, 'theme ', e.target.value);
        },

        async getQuestions(params = {}) {
            try {
                const {data} = await questionsService().paginate({...this.$route.query, ...params});
                this.questions = data.data;
                this.pagination = data;
            } catch (e) {
                alert('Can\'t load questions!')
            }
        },

        async getLectures() {
            try {
                const {data} = await lecturesService().pluck(['id', 'title']);
                this.themes = data;
            } catch (e) {
                alert(e.statusText || e.response.data.message);
            }
        },

        async onQuestionsExport() {
            try {
                const {data} = await questionsService().export();
                downloadFile(JSON.stringify(data), 'questions.json', 'application/json');
            }
            catch (e) {
                console.error(e);
            }
        },

        // Todo: when questions import their ids update, and so previously added quizzes became unavailable
        onQuestionsImport(file) {
            const reader = new FileReader();
            reader.readAsText(file);

            reader.onload = async () => {

                if (!reader.result) return;

                const data = JSON.parse(reader.result.toString());

                const list = data.map(q => {
                    return {
                        title: q.title,
                        theme: q.theme,
                        type: q.type,
                        content: q.content,
                        solution: q.solution,
                        uuid: uuidv4()
                    }
                })

                try {
                    await questionsService().import(list)

                    this.questions = [...this.questions, ...list];
                } catch (e) {
                    alert(e);
                }

            };

            reader.onerror = () => {
                alert(reader.error)
                console.log(reader.error);
            };
        },

        importQuestions() {
            if (!confirm(`Do you really want to import ${this.importedQuestions.length} questions?`)) return;
        },

        toggleQuestionCreateForm() {
            this.isQuestionCreateFormOpen = !this.isQuestionCreateFormOpen
            this.isQuestionsImportFormOpen = false;
            this.question = {id: '', title: '', content: '', type: '', theme: ''};
        },

        toggleMassCreationForm() {
            this.isMassCreationFormOpen = !this.isMassCreationFormOpen;
            this.isQuestionsImportFormOpen = false;
        },

        async onQuestionUpdate(question) {
            try {
                await questionsService().update(question.id, question);
                const idx = this.questions.findIndex(q => +q.id === +question.id);

                if (idx !== -1) {
                    // Todo: this should change existing object not add new
                    this.questions.push(question);

                    /*console.log(this.questions[idx]);

                    this.questions[idx].title = question.title;
                    this.questions[idx].content = question.content;
                    this.questions[idx].type = question.type;
                    this.questions[idx].theme = question.theme;
                    this.questions[idx].id = question.id;
                    this.questions[idx].solution = question.solution;*/

                    this.isQuestionCreateFormOpen = false;
                }
            } catch (e) {
                alert(e)
            }
        },

        async onQuestionCreate(question) {
            try {

                const {data} = await questionsService().create(question);

                this.questions.push(data);
                this.isQuestionCreateFormOpen = false;

            } catch (e) {
                console.error("Error adding document: ", e);
            }

        },

        async onRemoveQuestion(id) {

            const choice = confirm("Вы действительно хотите удалить вопрос?");

            if (choice) {

                try {
                    await questionsService().remove(id);

                    this.isQuestionCreateFormOpen = false;
                    this.questions = this.questions.filter(q => q.id !== id)
                } catch (e) {
                    alert(e);
                }
            }
        },

        onFormShow(e) {
            e.preventDefault();

            const dataset = e.target.closest('tr').dataset;

            // find entity id
            const qid = +dataset.id;

            this.question = this.questions.find(q => +q.id === +qid);

            // open edit form
            this.isQuestionCreateFormOpen = true;
            this.isQuestionsImportFormOpen = false;
        },

        async onRemove(e) {
            e.preventDefault();

            const dataset = e.target.closest('tr').dataset;

            // find entity id
            const qid = +dataset.id;
            const counter = +dataset.counter;
            /**const action = e.target.dataset?.action;*/

            if (confirm(`Вы действительно хотите удалить объект с идентификатором ${counter}?`)) {
                await questionsService().remove(qid);
                // set component state (this state passed to child component)
                this.questions = this.questions.filter(q => +q.id !== +qid);
            }
        },
    }
}
</script>

<style scoped>

table {
    border-collapse: collapse;
    border-bottom: 1px solid #ddd;
}

thead {
    color: #fff !important;
}

thead th {
    position: sticky;
    top: 0;
    background-color: #333;
    color: #fff !important;
    font-size: 0.9rem;
}

thead, tbody {
    /*display: block;*/
}

th, td {
    padding: 8px 10px;
    border: 1px solid #ddd;
    /*width: 117px;*/
    box-sizing: border-box;
}

.action-buttons > * {
    margin-right: 5px;
}


th[scope='col'] {

   /* vertical-align: center; Todo: not working */
}


/*tbody {*/
/*  max-height: 100px;*/
/*  overflow-y: scroll*/
/*}*/

/*.table-wrapper {*/
/*  max-height: 80vh;*/
/*  overflow: auto;*/
/*  display: inline-block;*/
/*}*/
</style>

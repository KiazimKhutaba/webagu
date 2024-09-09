import {FileableType} from "@/common/enums/FileableType.js";
import {toRaw} from "vue";

export default {
    namespaced: true,

    state: ({
        quiz: null,
        quiz_started: false,
        number_of_page_switches: 0,

        _questions_reply_files: [],
        _tasks_reply_files: [],
        _quizzes_reply_files: [],
    }),

    getters: {
        isStarted: (state) => state.quiz_started,

        get_questions_reply_files: (state) => (fileable_id) => {
            return state._questions_reply_files.filter(r => +r.fileable_id === +fileable_id);
        },

        get_tasks_reply_files: (state) => (fileable_id) => {
            return state._tasks_reply_files.filter(r => +r.fileable_id === +fileable_id);
        },

        get_quizzes_reply_files: (state) => (fileable_id) => {
            return state._quizzes_reply_files.filter(r => +r.fileable_id === +fileable_id);
        },

        get_quiz_replies(state) {
            return [
                ...state._questions_reply_files,
                ...state._tasks_reply_files,
                ...state._quizzes_reply_files
            ].map(toRaw);
        }
    },

    actions: {
        addReplyFile({commit}, uploaded) {
            commit('ADD_REPLY_FILE', uploaded);
        },

        removeReplyFile({commit}, file) {
            commit('REMOVE_REPLAY_FILE', file);
        },

        /**
         *
         * @param commit
         * @param files {Array}
         */
        loadReplyFiles({commit}, {files}) {
            //commit('CLEAR_REPLY_FILES');
            files.forEach(file => commit('ADD_REPLY_FILE', file));
        }
    },

    mutations: {
        SET_QUIZ_STARTED(state, status) {
            state.quiz_started = status;
        },

        ADD_REPLY_FILE(state, uploaded) {
            switch (uploaded.fileable_type) {
                case FileableType.QuizQuestion:
                    state._questions_reply_files = [...state._questions_reply_files, uploaded];
                    break;

                case FileableType.QuizTask:
                    state._tasks_reply_files = [...state._tasks_reply_files, uploaded];
                    break;

                case FileableType.QuizQuiz:
                    state._quizzes_reply_files = [...state._quizzes_reply_files, uploaded];
                    break;

                default:
                    // Handle any other cases if necessary
                    break;
            }
        },


        REMOVE_REPLAY_FILE(state, {fileable_type, generated_name}) {
            switch (fileable_type) {
                case FileableType.QuizQuestion:
                    state._questions_reply_files = state._questions_reply_files.filter(file => file.generated_name !== generated_name);
                    break;

                case FileableType.QuizTask:
                    state._tasks_reply_files = state._tasks_reply_files.filter(file => file.generated_name !== generated_name);
                    break;

                case FileableType.QuizQuiz:
                    state._quizzes_reply_files = state._quizzes_reply_files.filter(file => file.generated_name !== generated_name);
                    break;

                default:
                    // Handle any other cases if necessary
                    break;
            }
        },

        CLEAR_REPLY_FILES(state, {quiz_id}) {
            state._questions_reply_files = [];
            state._tasks_reply_files = [];
            state._quizzes_reply_files = [];
        }
    }
}

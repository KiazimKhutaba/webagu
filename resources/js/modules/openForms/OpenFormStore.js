import {v4} from "uuid";

const buildQuiz = (id) => ({
    id,
    title: '',
    lecture_id: 0,
    description: '',
    duration: 5,
    is_available: false,
    shuffle_questions: false
});

export const buildQuestion = (id, quiz_id) => ({
    id,
    quiz_id,
    title: '',
    image: '',
    variant_type: 'radio',
    description: '',
    has_description: false,
    selected: [],
    points: 5,
    required: false,
});

export const buildOption = (id, question_id, quiz_id, text = '', is_checked = false, control_type = 'radio') => ({
    id,
    question_id,
    quiz_id,
    text,
    is_checked,
    control_type,
    //selected: false
});

export const buildTextOption = (id, question_id, quiz_id) => ({
    id,
    question_id,
    quiz_id,
    text: '',
    is_checked: false,
    control_type: 'text',
    //selected: false
});

export const buildImageOption = (id, question_id, quiz_id, images = []) => ({
    id,
    question_id,
    quiz_id,
    images,
    is_checked: false,
    control_type: 'image',
    //selected: false
});


const initialState = () => ({
    quiz: buildQuiz(v4()),
    questions: [],
    options: []
});

const state = () => initialState();

const getters = {
    // quiz
    quiz_id: state => state.quiz.id,
    title: state => state.quiz.title,
    lecture_id: state => state.quiz.lecture_id,
    description: state => state.quiz.description,
    duration: state => state.quiz.duration,
    is_available: state => state.quiz.is_available,
    shuffle_questions: state => state.quiz.shuffle_questions,

    // question
    questions: state => state.questions,
    question: state => id => state.questions.find(q => q.id === id),

    // option
    all_options: state => state.options,
    // Question options
    options: state => question_id => state.options.filter(o => o.question_id === question_id),
    option: state => id => state.options.find(o => o.id === id),
}

const mutations = {
    CLEAR_FORM: state => Object.assign(state, initialState()),

    // quiz
    SET_TITLE: (state, value) => state.quiz.title = value,
    SET_LECTURE_ID: (state, value) => state.quiz.lecture_id = value,
    SET_DESCRIPTION: (state, value) => state.quiz.description = value,
    SET_DURATION: (state, value) => state.quiz.duration = value,
    SET_IS_AVAILABLE: (state, value) => state.quiz.is_available = value,
    SET_SHUFFLE_QUESTIONS: (state, value) => state.quiz.shuffle_questions = value,

    // question
    ADD_QUESTION: (state, question) => state.questions = [...state.questions, question],
    REMOVE_QUESTION(state, question_id) {
        state.questions = state.questions.filter(q => q.id !== question_id);
        state.options = state.options.filter(o => o.question_id !== question_id);
    },
    // @see https://stackoverflow.com/questions/50416063/update-data-using-vuex
    UPDATE_QUESTION(state, payload) {
        state.questions = [...state.questions.map(q => q.id !== payload.id ? q : {...q, ...payload})]
    },

    // options - answers to question
    ADD_OPTION: (state, option) => {
        state.options = [...state.options, option];
    },

    ADD_OPTIONS: (state, options) => {
        state.options = [...state.options, ...options];
    },

    REMOVE_OPTION: (state, option_id) => state.options = state.options.filter(o => o.id !== option_id),
    UPDATE_OPTION(state, payload) {
        state.options = [...state.options.map(option => option.id !== payload.id ? option : {...option, ...payload})]
    },
    SET_OPTIONS_TYPE(state, payload) {

        if (payload.control_type === 'text' || payload.control_type === 'image') {
            // remove all previously created options
            state.options = state.options.filter(option => option.question_id !== payload.question_id);

            // add single option
            /*state.options = [
                ...state.options, payload.control_type === 'text'
                ? buildTextOption(v4(), payload.question_id, payload.quiz_id)
                : buildImageOption(v4(), payload.question_id, payload.quiz_id)
            ];*/
        } else {
            state.options = state.options.map(option => {
                if (option.question_id === payload.question_id) {
                    return {...option, control_type: payload.control_type};
                }
                return option;
            });
        }
    },
    SET_OPTIONS_CHECKED(state, payload) {
        // if(['radio', 'checkbox'].includes(payload.control_type)) {}
        state.options = state.options.map(option => {
            if (option.question_id === payload.question_id) {
                return {...option, is_checked: payload.is_checked};
            }
            return option;
        });
    }
}

const actions = {}


export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}

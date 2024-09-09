import {QuizType} from "@/modules/openForms/QuizType.js";

const initialState = () => ({
    responses: []
});


const state = () => ({
    responses: [],
    quiz_started_at: 0
});


const getters = {}


const mutations = {
    CLEAR_STATE: state => Object.assign(state, initialState()),

    ADD_RESPONSE(state, payload) {

        switch (payload.type)
        {
            case QuizType.Single:
            case QuizType.Many:
            case QuizType.Text:
                alert('Not implemented');
                break;

            case QuizType.Image:
                //console.log(payload.question_id)
                // 1. first remove quiz take response entirely from responses
                state.responses = state.responses.filter((res) => res.question_id !== payload.question_id);
                // 2. add response with updated data
                state.responses = [...state.responses, payload];
                break;
        }

        /*if (Array.isArray(payload)) {
            let _responses = [];
            for (const newResp of payload) {
                _responses = state.responses.filter((oldResp) => oldResp.question_id !== newResp.question_id);
            }
            state.responses = [..._responses];
        } else {
            state.responses = state.responses.filter((res) => res.question_id !== payload.question_id);
        }

        if (Array.isArray(payload)) {
            state.responses = [...state.responses, ...payload];
        } else {
            state.responses = [...state.responses, payload];
        }*/
    },

    RESPONSE_REMOVE_IMAGE(state, payload) {
        state.responses = state.responses.map(r => {
            if (payload.type === 'image' && r.question_id === payload.question_id) {

                const index = r.data.indexOf(payload.item_id);
                if (index > -1) {
                    r.data.splice(index, 1); // 2nd parameter means remove one item only
                }
            }

            return r;
        });
    },

    SET_QUIZ_STARTED_AT(state, timestamp) {
        state.quiz_started_at = timestamp;
    }
}

const actions = {}


export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}

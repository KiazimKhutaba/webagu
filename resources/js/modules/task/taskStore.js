export default {
    namespaced: true,

    state: () => ({
        task: {
            students: []
        },
        isExecuteTaskOpen: false,
        reviewableTaskFiles: [],
        reviewableTask: {},
        isLoading: false,
        hasContent: false,
        reviewTypes: [
            {name: 'accepted', caption: 'Принято', clazz: 'success'},
            {name: 'rejected', caption: 'Не принято', clazz: 'danger'},
            {name: 'returned', caption: 'Возвращено на доработку', clazz: 'secondary'},
            {name: 'wait_for_review', caption: 'Ожидает проверки', clazz: 'primary'},
        ],
        selectedReviewType: {name: 'wait_for_review', caption: 'Ожидает проверки', clazz: 'primary'},
        commentFiles: [],
        commentText: 'Здравствуйте! Вот мой ответ',
        commentDefaultText: 'Здравствуйте! Вот мой ответ',
        selectedStudentId: 0,
        selectedStudentComments: [],
        answerFormComments: {},
        answerFormFiles: {},
    }),

    getters: {
        _userTaskCommentText: (state) => (task_id) => {
            return state.answerFormComments[task_id] || state.commentDefaultText;
        },
        _userTaskCommentFiles: (state) => (task_id) => {
            return state.answerFormFiles[task_id] || [];
        }
    },

    actions: {

    },

    mutations: {
        UPDATE_COMMENT_TEXT(state, payload) {
            state.answerFormComments = {
                ...state.answerFormComments,
                [payload.task_id]: payload.text || ' ' // space added here, because when text is empty the default comment text applied automatically
            };
        },

        UPDATE_COMMENT_FILES(state, task_id, file_name, file_data) {
            /*state.answerFormFiles = {
                ...state.answerFormFiles,
                [task_id]: [...state.answerFormFiles[task_id] || [], payload.file]
            };
            */
            state.answerFormFiles = {
                ...state.answerFormFiles,
                [task_id]: [...state.answerFormFiles[task_id] || [], {file_name, file_data}]
            }
        },
        CLEAR_TASK_COMMENT(state) {
            state.answerFormComments = {};
            state.answerFormFiles = {};
        }
    }
}

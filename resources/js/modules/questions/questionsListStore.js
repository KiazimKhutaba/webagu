export default {
    namespaced: true,

    state: () => ({
        filterTaskType: 0,
        filterTaskTheme: 0
    }),

    getters: {

    },

    actions: {
        onTaskTypeFilterChange() {

        }
    },

    mutations: {

        SET_FILTER_TASK_TYPE(state, payload) {
            state.filterTaskType = payload;
        },

        SET_FILTER_TASK_THEME(state, payload) {
            state.filterTaskTheme = payload;
        }
    }
}

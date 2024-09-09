const initialState = () => ({
    // filterBlockVisible: false,
    selectedUsers: [],
    selectedActions: [],
    filterIPs: [],
    filterUrls: [],
    startDatetime: '',
    endDatetime: '',
})

export default {
    namespaced: true,

    state: () => ({
        filterBlockVisible: false,
        selectedUsers: [],
        selectedActions: [],
        filterIPs: [],
        filterUrls: [],
        startDatetime: 0,
        endDatetime: 0,
    }),

    getters: {

    },

    mutations: {

        FILTER_CLEAR(state) {
            Object.assign(state, initialState());
        },

        SET_FILTER_BLOCK_VISIBILITY(state, status) {
            state.filterBlockVisible = status;
        },

        SET_SELECTED_USERS(state, list) {
            state.selectedUsers = list;
        },

        SET_SELECTED_ACTIONS(state, list) {
            state.selectedActions = list;
        },

        SET_FILTER_START_DATETIME(state, dt) {
            state.startDatetime = dt;
        },

        SET_FILTER_END_DATETIME(state, dt) {
            state.endDatetime = dt;
        },

        SET_FILTER_IPS(state, ips) {
            state.filterIPs = ips
        },

        SET_FILTER_URLS(state, urls) {
            state.filterUrls = urls;
        }

    },

    actions: {

    },
}


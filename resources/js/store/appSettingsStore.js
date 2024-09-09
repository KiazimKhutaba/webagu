import {postsService} from "../modules/posts/api/PostsService.js";

const state = () => ({
    usersPageViewType: 'list',
    reportsPageSelectedReport: 'common',
    commonReportSelectedLectureId: 0,
    commonReportSelectedTaskId: 0,
    commonReportSelectedDepartment: 'all',
    commonReportSelectedTaskType: -1,
    commonReportIsCheckingMode: 0,
    tasksPageSelectedLecture: 0,
    tasksReviews: {
        selectedLecture: 0,
        selectedTask: 0
    },
    unreadPostsCount: 0,
    isDarkModeEnabled: false
});

const getters = {}

const mutations = {

    SET_USERS_PAGE_VIEW_TYPE(state, value) {
        state.usersPageViewType = value;
    },

    SET_REPORTS_PAGE_SELECTED_REPORT(state, value) {
        state.reportsPageSelectedReport = value;
    },

    SET_COMMON_REPORT_SELECTED_LECTURE_ID(state, value) {
        state.commonReportSelectedLectureId = value;
    },

    SET_COMMON_REPORT_SELECTED_TASK_ID(state, value) {
        state.commonReportSelectedTaskId = value;
    },

    SET_COMMON_REPORT_SELECTED_DEPARTMENT(state, value) {
        state.commonReportSelectedDepartment = value;
    },

    SET_COMMON_REPORT_SELECTED_TASK_TYPE(state, value) {
        state.commonReportSelectedTaskType = value;
    },

    SET_TASKS_PAGE_SELECTED_LECTURE(state, value) {
        state.tasksPageSelectedLecture = value;
    },

    SET_TASKS_REVIEWS_FILTERS(state, payload) {
        state.tasksReviews = {...state.tasksReviews, ...payload};
    },

    SET_UNREAD_POSTS_COUNT(state, value) {
        state.unreadPostsCount = value;
    },

    SET_DARK_MODE_STATE(state, value) {
        state.isDarkModeEnabled = value;
    },

    TOGGLE_COMMON_REPORT_CHECKING_MODE(state, value) {
        state.commonReportIsCheckingMode = value;
    }
}

const actions = {
    async getAndSetUnreadPostsCount({ commit } = {}) {
        const {data} = await postsService().unreadPostsCount();
        commit('SET_UNREAD_POSTS_COUNT' ,data.unread_counts);
    },

    setDarkMode({ commit, state } = {}, value) {
        const documentRoot = document.documentElement;
        documentRoot.setAttribute('data-bs-theme', value ? 'dark' : '');
        commit('SET_DARK_MODE_STATE', value);
    },

    async initDarkMode({ dispatch, state } = {}) {
        await dispatch('setDarkMode', state.isDarkModeEnabled);
    }
}


export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}

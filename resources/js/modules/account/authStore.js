import {authService} from "./api/AuthService.js";
import {accountService} from "@/modules/account/api/CreateAccountService.js";
import store from "@/store/index.js";

const initialState = () => ({
    user: null,
    isUserAuthenticated: false,
    access_token: null,
    refresh_token: null,
});

export default {
    namespaced: true,

    state: initialState(),

    getters: {

        getAccessToken: (state) => state.access_token,

        getRefreshToken: (state) => state.refresh_token,

        user: (state) => state.user,

        isAuthenticated: (state) => state.isUserAuthenticated,

        is_admin: (state) => state.user.role === 'admin',
    },

    actions: {

        async login({commit}, credentials) {
            const {data} = await authService().login(credentials);

            commit('SET_AUTH_SUCCESS', data);
        },

        async refreshToken({commit}) {
            //const { data } = await authService.
        },

        async logout({commit}) {
            authService().logout().then(() => {
                commit('CLEAR_STATE');
            }).finally(() => {
                window.localStorage.setItem('weblearning__store', JSON.stringify({}));
                // window.localStorage.clear();//removeItem('weblearning__store');
            });
        },

        async updateAvatar({commit}, avatar) {
            const {data} = await accountService().saveProfileAvatar(avatar);
            commit('SET_AVATAR', data.avatar);
        }

    },

    mutations: {

        /**
         * Todo: @see https://stackoverflow.com/questions/42295340/how-to-clear-state-in-vuex-store
         *
         * @param state
         * @constructor
         */
        CLEAR_STATE(state) {
            state = {};
            Object.assign(state, initialState());
            /*state.user = null;
            state.isUserAuthenticated = false;
            state.access_token = null;
            state.refresh_token = null;*/
        },

        SET_AUTH_SUCCESS(state, res) {
            state.user = res.user;
            state.isUserAuthenticated = true;
            state.access_token = res.access_token;
            state.refresh_token = res.refresh_token;
        },

        SET_ACCESS_TOKEN(state, token) {
            state.access_token = token;
            //Vue.$set(state, 'token', token);
        },

        SET_REFRESH_TOKEN(state, token) {
            state.refresh_token = token;
            //Vue.$set(state, 'token', token);
        },

        SET_AUTHENTICATED(state, isUserAuthenticated) {
            state.isUserAuthenticated = isUserAuthenticated;
        },

        SET_USER(state, user) {
            state.user = user;
        },

        SET_AVATAR(state, avatar) {
            state.user.avatar = avatar;
        }

    }
}

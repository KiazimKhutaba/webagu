import axios from "axios";
import store from "../store";
import authStatusCode from "./authStatusCode.js";

export const getAccessToken = () => {
    const storage = JSON.parse(localStorage.getItem('weblearning__store'));
    return storage?.authStore?.access_token?.value || '';
}

const axiosClient = axios.create({
    baseURL: import.meta.env.VITE_APP_URL_API,
    timeout: 60000,
    headers: {
        Accept: 'application/json',
        Authorization: 'Bearer ' + getAccessToken(),//store.getters["authStore/getAccessToken"]?.value || '',
        'X-Requested-With': 'XMLHttpRequest',
    }
});


function createAxiosResponseInterceptor() {
    const interceptor = axiosClient.interceptors.response.use(
        response => response,
        error => {

            /*if(error.response.status === 403) {
                window.location.href = '/';
            }*/
            // Reject promise if usual error
            if (error.response.status !== 401) {
                return Promise.reject(error);
            }

            // const userAuthenticated = store.getters['authStore/isAuthenticated'];
            const errorCode = error.response?.data?.code || 0;


            if(errorCode === authStatusCode.UserNotActivated) {
                store.commit('authStore/CLEAR_STATE');
                window.localStorage.setItem('weblearning__store', JSON.stringify({}));

                if(window.location.href === `${import.meta.env.VITE_APP_URL}/`) {
                    return Promise.reject(error);
                }

                window.location.href = '/';
                return Promise.reject(error);
            }

            if(errorCode === authStatusCode.LoginOrPasswordIncorrect) {
                return Promise.reject(error);
            }

            /*
             * When response code is 401, try to refresh the token.
             * Eject the interceptor so it doesn't loop in case
             * token refresh causes the 401 response.
             *
             * Must be re-attached later on or the token refresh will only happen once
             */
            axiosClient.interceptors.response.eject(interceptor);

            // Todo: refresh token not updated properly, so only page hard reload helps
            return axiosClient
                .post('auth/refresh', {}, {
                    headers: {
                        Authorization: "Bearer " + store.getters["authStore/getRefreshToken"]?.value || ''
                    }
                })
                .then((response) => {

                    store.commit('authStore/SET_AUTH_SUCCESS', response.data);

                    error.response.config.headers["Authorization"] = "Bearer " + response.data.access_token?.value || '';
                    localStorage.setItem('weblearning__store', JSON.stringify(store.state));

                    // Retry the initial call, but with the updated token in the headers.
                    // Resolves the promise if successful
                    return axiosClient(error.response.config);

                })
                .catch((error2) => {

                    // Retry failed, clean up and reject the promise
                    store.commit('authStore/CLEAR_STATE');
                    window.localStorage.setItem('weblearning__store', JSON.stringify({}));
                    window.location.href = '/';

                    return Promise.reject(error2);

                })
                .finally(createAxiosResponseInterceptor); // Re-attach the interceptor by running the method
        }
    );
}
createAxiosResponseInterceptor();

export default axiosClient;

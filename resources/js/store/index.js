import {createStore} from "vuex";
import VuexPersistence from "vuex-persist";
import authStore from "../modules/account/authStore.js";
import userStore from "./userStore.js";
import quizStore from "../modules/quiz/quizStore.js";
import taskStore from "../modules/task/taskStore.js";
import appSettingsStore from "./appSettingsStore.js";
import openFormStore from "../modules/openForms/OpenFormStore.js";
import ofQuizTakeStore from "../modules/openForms/OfQuizTakeStore.js";
import questionsListStore from "../modules/questions/questionsListStore.js";
import usersActivityLogsStore from "../modules/logs/usersActivityLogsStore.js";


const vuexPersist = new VuexPersistence({
    key: 'weblearning__store',
    storage: window.localStorage
});

const store = createStore({
    modules: {
        authStore,
        quizStore,
        userStore,
        taskStore,
        openFormStore,
        ofQuizTakeStore,
        appSettingsStore,
        questionsListStore,
        usersActivityLogsStore
    },
    plugins: [vuexPersist.plugin]
});

export default store;

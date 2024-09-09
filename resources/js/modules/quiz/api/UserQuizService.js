import {BASE_URL} from "@/common/config.js";
import BaseService from "@/common/api/BaseService.js";

export default class UserQuizService extends BaseService {

    constructor(baseUrl, serviceUrl = 'user_quizzes') {
        super(baseUrl, serviceUrl);
    }

    async getGeneratedQuiz(user_id, quiz_id) {
        /**
         * select * from user_quizzes where quiz_id = :quiz_id and user_id = :user_id
         *
         */

        return [];
    }
}

export const userQuizService = () => new UserQuizService(BASE_URL);

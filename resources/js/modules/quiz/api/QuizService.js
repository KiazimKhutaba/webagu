import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class QuizService extends BaseService {

    constructor(baseUrl, serviceUrl = 'quizzes') {
        super(baseUrl, serviceUrl);
    }

    async toggleStatus(quiz_id, status) {
        return await this.client().patch(this.buildUrl(this.serviceUrl, quiz_id), { status });
    }

    async getByUuid(uuid) {
        return await this.client().get(this.buildUrl(this.serviceUrl, uuid));
    }

    async addPassedQuiz() {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'passed'));
    }

    async getPassedQuizzes(query_params) {
        const searchParams = new URLSearchParams(query_params);
        return await this.client().get(this.buildUrl(this.serviceUrl, 'passed') + '?' + searchParams.toString());
    }

    async getPassedQuiz(id) {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'passed', id));
    }

    async getQuizAccessRequests(uuid){
        return await this.client().get(this.buildUrl(this.serviceUrl, uuid, 'access-requests'));
    }

    async grantUserAccessToQuiz(user_id, request_id) {
        return await this.client().post(this.buildUrl(this.serviceUrl,'grant-access'), { user_id, request_id });
    }

    /**
     *
     * @param data {FormData}
     * @param onProgress
     * @return {Promise<AxiosResponse<any>>}
     */
    async addReplyFile(data, onProgress) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'add-reply-file'), data, {
            //headers: this.config.headers,
            onUploadProgress: progressEvent => {
                onProgress((progressEvent.loaded / progressEvent.total) * 100);
            }
        });
    }

    async removeReplyFile(id) {
        return await this.client().delete(this.buildUrl(this.serviceUrl, 'remove-reply-file', id));
    }


    async sendOnReview(id) {
        return await this.client().put(this.buildUrl(this.serviceUrl, 'send-on-review'), {id});
    }
}

export const quizService = () => new QuizService(BASE_URL);

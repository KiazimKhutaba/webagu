import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class BaseFormService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'openforms') {
        super(baseUrl, serviceUrl);
    }

    async sendForReview(data) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'send-for-review'), data);
    }

    async getPassedQuizzes(query_params = {}) {
        const searchParams = new URLSearchParams(query_params);
        return await this.client().get(this.buildUrl(this.serviceUrl, 'get-passed-quizzes') + '?' + searchParams.toString());
    }

    /**
     * Show examinee quiz take for checking
     *
     * @param id
     * @return {Promise<AxiosResponse<any>>}
     */
    async getQuizTake(id) {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'quiztake', id));
    }
}

export const openFormsService = () => new BaseFormService(BASE_URL);

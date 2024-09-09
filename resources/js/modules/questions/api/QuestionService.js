import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class QuestionService extends BaseService {

    constructor(baseUrl, serviceUrl = 'questions') {
        super(baseUrl, serviceUrl);
    }

    async import(data = {}) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'import'), { list: data });
    }

    async updateTheme(qid, theme_id) {
        return await this.client().patch(this.buildUrl(this.serviceUrl, qid, 'theme'), { theme_id });
    }

    async export() {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'export'));
    }
}

export const questionsService = () => new QuestionService(BASE_URL);

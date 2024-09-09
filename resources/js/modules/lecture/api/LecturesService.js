import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class LecturesService extends BaseService {

    constructor(baseUrl, serviceUrl = 'lectures') {
        super(baseUrl, serviceUrl);
    }

    async getTasks(lecture_id) {
        return this.client().get(this.buildUrl(this.serviceUrl, lecture_id, 'tasks'));
    }

    async getTitles() {
        return await this.client().get(this.serviceUrl + '?only_id_and_title=1');
    }
}

export const lecturesService = () => new LecturesService(BASE_URL);

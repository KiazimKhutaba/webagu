import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class ReviewService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'reviews') {
        super(baseUrl, serviceUrl);
    }

    async getReviewBy(task_id, student_id) {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'task', task_id, 'student', student_id));
    }

    async getAllByTaskId(query_params) {
        const query_string = this.buildQueryString(query_params);
        return await this.client().get(`${this.serviceUrl}?${query_string}`);
    }
}

export const reviewService = () => new ReviewService(BASE_URL);

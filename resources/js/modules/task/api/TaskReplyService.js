import BaseService from "@/common/api/BaseService.js";
import axiosClient from "@/utils/axiosClient.js";
import {BASE_URL} from "@/common/config.js";

export default class TaskReplyService extends BaseService {
    constructor(baseUrl, serviceUrl = 'task-reply') {
        super(baseUrl, serviceUrl);
    }

    async getReviewableTask(lecture_id, task_id) {
        const url = this._normalizeUrl(this.serviceUrl, 'lecture', lecture_id, 'task', task_id);
        return await axiosClient.get(url);
    }

}


export const taskReplyService = () => new TaskReplyService(BASE_URL);

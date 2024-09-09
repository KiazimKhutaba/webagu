import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class TasksService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'tasks') {
        super(baseUrl, serviceUrl);
    }

    async comments(task_id, student_id) {
        return await this.client().post(this.buildUrl(this.serviceUrl, task_id, 'comments'), {
            student_id
        });
    }

    async updateTaskHistory(data) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'teacher-review'), this.buildFormData(data))
    }

    async sendForReview(data) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'send-for-review'), this.buildFormData(data))
    }

    async getStudentTasks(query_params) {
        const query_string = this.buildQueryString(query_params);
        return await this.client().get(this.buildUrl(this.serviceUrl, 'get-student-tasks') + '?' + query_string);
    }

    async getAllByLectureId(query_params) {
        const query_string = this.buildQueryString(query_params);
        return await this.client().get(`${this.serviceUrl}?${query_string}`);
    }

    async getProgressByTasksForUser(student_id) {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'progress', student_id));
    }

    async toggleVisibility({ id, is_visible }) {
        return await this.client().post(this.buildUrl(this.serviceUrl, id, 'is-visible'), { is_visible });
    }
}

export const tasksService = () => new TasksService(BASE_URL);

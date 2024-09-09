import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";


export default class ReportsService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'reports') {
        super(baseUrl, serviceUrl);
    }

    async getTaskStatuses(searchParams = {}) {
        const url = new URL(this.buildUrl(this.baseUrl, this.serviceUrl, 'get-task-statuses'));

        for (const searchKey in searchParams) {
            url.searchParams.append(searchKey, searchParams[searchKey]);
        }

        return this.client().get(url.toString());
    }

    async getTasksProgress() {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'tasks-progress'));
    }
}

export const reportsService = () => new ReportsService(BASE_URL);

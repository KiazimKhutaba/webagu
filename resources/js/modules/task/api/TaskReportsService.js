import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class TaskReportsService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'tasks/reports') {
        super(baseUrl, serviceUrl);
    }
}

export const taskReportsService = () => new TaskReportsService(BASE_URL);

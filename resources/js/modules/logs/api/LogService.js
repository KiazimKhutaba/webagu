import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";


export default class LogService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'logs') {
        super(baseUrl, serviceUrl);
    }

    async getMostActiveUsers() {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'most-active-users'));
    }

    async getUniqueIPs() {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'unique-ips'));
    }

    async getUniqueUrls() {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'unique-urls'));
    }
}

export const logsService = () => new LogService(BASE_URL);

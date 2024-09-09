import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class UserActivityService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'logs') {
        super(baseUrl, serviceUrl);
    }
}

export const userActivityService = () => new UserActivityService(BASE_URL);

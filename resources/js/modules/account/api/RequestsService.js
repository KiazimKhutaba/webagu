import BaseService from "../../../common/api/BaseService.js";
import {BASE_URL} from "../../../common/config.js";

export default class RequestsService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'requests') {
        super(baseUrl, serviceUrl);
    }

    async passwordChangeRequest(data) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'password-change-request'), data);
    }

}

export const requestsService = () => new RequestsService(BASE_URL);

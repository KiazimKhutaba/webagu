import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class UsersService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'users') {
        super(baseUrl, serviceUrl);
    }

    async login(data)
    {
        return await this.create()
    }


    async changeActivationStatus(status, user_id)
    {
        const data = new FormData();
        data.set('status', status);
        data.set('user_id', user_id);

        return  await this.client().post(this._normalizeUrl(this.serviceUrl, 'change-activation-status'), data);
    }

    async getStudents(role = 'user') {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'students'));
    }

    async search(username) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'search'), { username });
    }
}

export const usersService = () => new UsersService(BASE_URL);

import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class CreateAccountService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'account') {
        super(baseUrl, serviceUrl);
    }

    async saveProfileImage(file) {
        return await this.client().post(this._normalizeUrl(this.serviceUrl, 'avatar'), {
            avatar: file
        });
    }

    async saveProfileAvatar(avatar) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'profile-avatar'), this._normalizeFormData({avatar}))
    }
}

export const accountService = () => new CreateAccountService(BASE_URL);

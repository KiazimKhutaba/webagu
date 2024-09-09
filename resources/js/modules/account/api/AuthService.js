import {BASE_URL} from "@/common/config.js";
import BaseService from "@/common/api/BaseService.js";

export default class AuthService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'auth') {
        super(baseUrl, serviceUrl);
    }

    async login(data) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'login'), data);
    }

    async logout() {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'logout'));
    }

    async refreshToken() {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'refresh'))
    }

    async register(data) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'register'), this._normalizeFormData(data));
    }

    async getUser(token)
    {
        if(!token) {
            throw new Error('Authorization token is null');
        }

        this.config.headers = {
            ...this.config.headers,
            'Authorization': 'Bearer ' + token
        };

        return await axios.get(`${this.baseUrl}/${this.serviceUrl}/me`, this.config);
    }
}

export const authService = () => new AuthService(BASE_URL);

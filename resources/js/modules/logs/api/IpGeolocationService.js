import axios from "axios";

export default class IpGeolocationService
{
    constructor() {
        this.client = axios.create({
            baseURL:  'https://api.ipgeolocation.io',
            params: {
                'apiKey': '1b3eed8632e2485a9dfd4fabf03c696c'
            }
        });
    }

    async client(url) {
        try {
            const resp = await this.client.get(url);
            return resp.data;
        }
        catch (e) {
            return e;
        }
    }

    async getUserAgentInfo() {
        const resp = await this.client('/user-agent');
        return resp.data;
    }

    async getIpInfo() {
        const resp = await this.client('/getip');
        return resp.data;
    }

    async getIpGeo() {
        const resp = await this.client('/ipgeo');
        return resp.data;
    }
}

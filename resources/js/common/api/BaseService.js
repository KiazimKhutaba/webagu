import axiosClient from "../../utils/axiosClient.js";

export default class BaseService
{
    constructor(baseUrl, serviceUrl)
    {
        // Todo: in index.js file derived classes instantiated not lazy
        // console.log(axiosClient.defaults.headers);

        this.baseUrl = baseUrl;
        this.serviceUrl = serviceUrl;
    }


    _normalizeUrl(...args) {
        return args.join('/');
    }

    buildUrl(...args) {
        return args.filter(arg => arg?.toString().trim()?.length !== 0).join('/');
    }

    _normalizeFormData(data)
    {
        const formData = new FormData();

        for (const key in data)
        {
            if(Array.isArray(data[key])) {
                formData.set(key, JSON.stringify(data[key]));
            }
            else {
                formData.set(key, data[key]);
            }
        }

        return formData;
    }

    buildQueryString(params) {
        return Object.entries(params).map(p => p[0] + '=' + p[1]).join('&');
    }

    buildFormData(data) {
        const formData = new FormData();

        for (const key in data) {
            if (Array.isArray(data[key])) {
                for (const item of data[key]) {
                    formData.append(`${key}[]`, item);
                }
            } else {
                formData.append(key, data[key]);
            }
        }

        return formData;
    }

    async paginate(query_params) {
        const query_string = this.buildQueryString(query_params);
        return await this.client().get(`${this.serviceUrl}?${query_string}`);
    }

    async filter(query_params, filter_data = {}) {
        const query_string = this.buildQueryString(query_params);
        return await this.client().post(`${this.serviceUrl}?${query_string}`, filter_data);
    }

    async getAll(config = {}) {
        return await axiosClient.get(this.serviceUrl, config);
    }

    async getAllByParams(qs_params, config = {}) {
        const query_string = this.buildQueryString(qs_params);
        return await axiosClient.get(`${this.serviceUrl}?${query_string}`, config);
    }

    async getById(id, config = {}, search_params = {}) {
        const url = new URL(this.buildUrl(this.baseUrl, this.serviceUrl, id));
        for (const searchParamsKey in search_params) {
            url.searchParams.append(searchParamsKey, search_params[searchParamsKey]);
        }
        return await axiosClient.get(url.toString(), config);
    }

    async remove(id, config = {}) {
        return await axiosClient.delete(this._normalizeUrl(this.serviceUrl, id), config);
    }

    async update(entity_id, data, config = {})
    {
        return await axiosClient.put(this.buildUrl(this.serviceUrl, entity_id), data, config);
    }

    async create(data, config = {})
    {
        return await axiosClient.post(this.serviceUrl, this._normalizeFormData(data), config);
    }

    async save(data, config = {}) {
        return await this.create(data, config);
    }

    async save2(data, config = {}) {
        return await this.client().post(this.serviceUrl, data, config);
    }

    /**
     * This method returns columns that requested in fields
     *
     * @param fields {Array}
     * @param config
     * @return {Promise<AxiosResponse<any>>}
     */
    async pluck(fields, config = {}) {

        const _url = new URL(this.buildUrl(this.baseUrl, this.serviceUrl));

        for (const field of fields) {
            _url.searchParams.append('fields[]', field);
        }

        return await axiosClient.get(_url.toString(), config);
    }

    client() {
        return axiosClient;
    }

    async request() {

    }
}

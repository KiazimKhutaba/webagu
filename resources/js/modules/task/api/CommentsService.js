import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";


export default class CommentsService extends BaseService
{

    constructor(baseUrl, serviceUrl = 'comments') {
        super(baseUrl, serviceUrl);
    }

    async add(comment) {
        return await this.client().post(this.serviceUrl, this.buildFormData(comment));
    }

    async getTaskRepliesComments(reply_id) {
        return await this.client().get(this.buildUrl(this.serviceUrl, reply_id));
    }
}

export const commentsService = () => new CommentsService(BASE_URL);

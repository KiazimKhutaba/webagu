import {BASE_URL} from "@/common/config.js";
import BaseService from "@/common/api/BaseService.js";

export default class PostsService extends BaseService
{
    constructor(baseUrl, serviceUrl = 'posts') {
        super(baseUrl, serviceUrl);
    }

    async getViewsHistory(id) {
        return await this.client().get(this.buildUrl(this.serviceUrl, id, 'views-history'));
    }

    async unreadPostsCount() {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'unread-posts-count'));
    }
}

export const postsService = () => new PostsService(BASE_URL);

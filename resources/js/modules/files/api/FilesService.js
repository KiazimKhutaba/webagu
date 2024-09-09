import BaseService from "@/common/api/BaseService.js";
import {BASE_URL} from "@/common/config.js";

export default class FilesService extends BaseService
{
    constructor(baseUrl, url = 'files') {
        super(baseUrl, url);
    }

    async search(filename) {
        const formData = new FormData();
        formData.set('filename', filename);

        return await this.client().post(this.buildUrl(this.serviceUrl, 'search'), formData);
    }

    /**
     *
     * @param formData {FormData}
     * @param onProgress {Function}
     * @return {Promise<AxiosResponse<any>>}
     */
    async uploadFile(formData, onProgress) {

        return await this.client().post(this.buildUrl(this.serviceUrl, 'upload'), formData, {
            //headers: this.config.headers,
            onUploadProgress: progressEvent => {
                onProgress((progressEvent.loaded / progressEvent.total) * 100);
            }
        });


        /*try {


        } catch (error) {
            if (error.response) { // get response with a status code not in range 2xx
                console.log(error.response.data);
                console.log(error.response.status);
                console.log(error.response.headers);
            } else if (error.request) { // no response
                console.log(error.request);
            } else { // Something wrong in setting up the request
                console.log('Error', error.message);
            }
            console.log(error.config);
        }*/
    }

}


export const filesService = () => new FilesService(BASE_URL);

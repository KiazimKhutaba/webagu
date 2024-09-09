import BaseFormService from "./BaseFormService.js";

const BASE_URL = import.meta.env.VITE_APP_URL_API;

const formService = () => new BaseFormService(BASE_URL);

export {
    formService
}

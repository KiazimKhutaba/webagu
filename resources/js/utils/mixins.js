import datetime_format from "./datetime_format.js";
import store from "../store/index.js";
import departmentTranslate from "../utils/departmentTranslate.js";

export default {
    methods: {
        dt_format(timestamp) {
            return datetime_format(timestamp);
        },

        dt_format2(timestamp) {
            return datetime_format(timestamp, {
                year: "numeric",
                month: "numeric",
                day: "numeric",
                hour: "numeric",
                minute: "numeric"
            });
        },

        department_translate(dep) {
            return departmentTranslate(dep);
        },

        /**
         *
         * @param route_name
         * @param params
         * @return {Route}
         */
        route_url(route_name, params = {}) {
            return this.$router.resolve({name: route_name});
        },

        /**
         *
         * @return {boolean}
         */
        is_admin() {
            return store.getters["authStore/user"]?.role === 'admin';
        },

        /**
         *
         * @return {boolean}
         */
        is_student() {
            return store.getters["authStore/user"]?.role === 'user';
        },


        format_department(dep) {
            return {
                finance: 'Финансы и кредит',
                national: 'Национальная экономика',
                accountant: 'Бухгалтерский учет и аудит',
                '_default': 'Неизвестная группа'
            }[dep || '_default']
        },


        debug(value) {
            return JSON.stringify(value, null, 2);
        },

        userProfileLink(student_id) {
            return this.$router.resolve({
                name: 'user.profile',
                params: {id: student_id},
                // query: { student_id , from: 'reports' }
            });
        },

        href(name, params = {}, query = {}) {
            return this.$router.resolve({name, params, query});
        },

        url(name, params = {}, query = {}) {
          return this.href(name, params, query);
        },

        redirect_to(name, params = {}, query = {}) {
          return this.$router.push({name, params, query});
        },

        /**
         *
         * @param file {Object|string}
         * @return {*|boolean}
         */
        is_image(file) {
            if(typeof file === 'object') {
                return ['image/jpeg', 'image/png'].includes(file.type);
            }

            return file?.endsWith('jpg') || file?.endsWith('png') || file?.endsWith('jpeg');
        },

        upload_path(file) {
          return `/upload/${file?.generated_name ?? file}`;
        },

        images_path(file) {
            return `/images/${file?.generated_name ?? file}`;
        },

        goto(page) {
            this.$router.push({ name: page });
        },

        goBack() {
            this.$router.go(-1);
            //this.$router.push({ name: 'quizzes' });
        },
    },
}

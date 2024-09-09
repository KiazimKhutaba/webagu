<template>
    <div class="ps-md-2">

        <div class="row">

            <div class="col-md-3 p-0">

                <div class="card h-100 m-0">
                    <div class="card-body text-center">
                        <img :src="profileImageOrDefault(user?.avatar)" class="img-fluid rounded-3"
                             alt="profile image"/>
                    </div>
                    <div class="card-footer">
                        <div class="row d-flex justify-content-center">
                            <div class="col-auto">
                                <file-chooser caption="Загрузить" @on-file-choose="onProfileImageChoose"
                                              class="btn-primary" accept="image/*"/>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm btn-success"
                                        :disabled="update_profile_image_button_disabled"
                                        @click="onProfileImageUpdate"
                                >
                                    Обновить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <table class="table table-responsive table-bordered">
                    <tr>
                        <td>Идентификатор</td>
                        <td class="fw-bold">{{ user?.id || '0' }}</td>
                    </tr>

                    <tr>
                        <td>ФИО</td>
                        <td class="fw-bold">{{ user?.lastname || 'Doe' }} {{ user?.firstname || 'Joe' }}</td>
                    </tr>

                    <tr>
                        <td>Группа</td>
                        <td class="fw-bold">{{ format_department(user?.department || 'no') }}</td>
                    </tr>

                    <tr>
                        <td>Номер телефона</td>
                        <td class="fw-bold">{{ hideValueWithStars(user?.phone || '0000000') }}</td>
                    </tr>

                    <tr>
                        <td>Почта</td>
                        <td class="fw-bold">{{ user?.email || 'mail' }}</td>
                    </tr>

                    <tr>
                        <td>Роль</td>
                        <td class="fw-bold">{{ user?.role || 'role' }}</td>
                    </tr>

                    <tr>
                        <td>Статус</td>
                        <td class="fw-bold">{{ user?.status || 'status' }}</td>
                    </tr>

                    <tr>
                        <td>Зарегистрирован</td>
                        <td class="fw-bold">{{ dt_format(user?.created_at || Date.now()) }}</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</template>

<script>
import FileUpload from "@/common/components/FileUpload.vue";
import FileChooser from "@/common/components/FileChooser.vue";
import readFileAsDataURL from "@/utils/readAsDataUrl.js";
import {mapActions} from "vuex";

export default {
    name: "UserProfileFragment",
    components: {FileChooser, FileUpload},
    props: {
        user: {
            type: Object,
            //required: true
        }
    },

    data: () => ({
        profile_image: null,
        profile_image_data: null,
    }),

    computed: {
        update_profile_image_button_disabled() {
            return !this.profile_image_data;
        }
    },

    methods: {

        ...mapActions('authStore', ['updateAvatar']),

        profileImageOrDefault(image) {
            if (null !== this.profile_image_data) return this.profile_image_data;
            if (image === 'no_avatar.png') return '/images/no_avatar.png';
            return `/images/avatars/${image}`;
        },

        format_department(dep) {
            return {
                finance: 'Финансы и кредит',
                national: 'Национальная экономика',
                accountant: 'Бухгалтерский учет и аудит',
                '_default': 'Неизвестная группа'
            }[dep || '_default']
        },

        hideValueWithStars(value) {
            const str = String(value).trim();
            const last4DigitsStr = str.substring(5);
            return last4DigitsStr.padStart(7, '*');
        },

        async onProfileImageChoose(image) {
            const data = await readFileAsDataURL(image);
            this.profile_image = image;
            this.profile_image_data = data;
        },

        onProfileImageUpdate() {
            this.updateAvatar(this.profile_image);
            this.profile_image = null;
            this.profile_image_data = null;
        },
    }
}
</script>

<style scoped>
.row {
    /*border-bottom: 1px solid var(--bs-secondary);*/
    padding: 5px 0;
}

table tr td {
    padding: 10px;
}

</style>

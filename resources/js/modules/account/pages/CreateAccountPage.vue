<template>

    <main-wrapper>
        <main class="container h-100 d-flex flex-column align-items-md-center justify-content-md-center pt-2">

            <div class="shadow-lg rounded py-4 px-4" v-if="accountCreated">
                <h4>Ваш аккаунт создан!</h4>
                <p>После активации, перейдите на
                    <router-link :to="loginPage">страницу входа</router-link>
                    и введите Ваши данные
                </p>
            </div>

            <div class="shadow-lg rounded py-4 px-4 form-signin" v-else>
                <h2 class="mb-4">Регистрация</h2>

                <div class="alert alert-warning" v-show="errorExist">{{ errors?.message }}</div>

                <form @submit.prevent="handleSubmit">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="lastnameTextbox" class="form-label">Фамилия</label>
                            <input type="text" class="form-control" id="lastnameTextbox" ref="lastname" min="5"
                                   required/>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="firstnameTextbox" class="form-label">Имя</label>
                            <input type="text" class="form-control" id="firstnameTextbox" ref="firstname" min="5"
                                   required/>
                        </div>

                        <div class="col mb-3">
                            <label class="form-label" for="group">Направление</label>
                            <select class="form-select" id="group" ref="department" required>
                                <option value="" disabled selected>Выберите ваше направление</option>
                                <option value="finance">Финансы и кредит</option>
                                <option value="accountant">Бухгалтерский учет и аудит</option>
                                <option value="national">Национальная экономика</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="emailTextbox" class="form-label">Рабочая почта</label>
                            <input type="email" class="form-control" id="emailTextbox" ref="email" required/>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phoneTextbox" class="form-label">Номер телефона</label>
                            <input type="tel" class="form-control" id="phoneTextbox" ref="phone" pattern="(9|7)[0-9]{6}"
                                   maxlength="7"
                                   required/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label for="passwordTextbox" class="form-label">Пароль</label>
                            <input type="password" class="form-control" id="passwordTextbox" ref="password" min="6"
                                   required/>
                        </div>

                        <div class="mb-3">
                            <label for="passwordTextboxRepeat" class="form-label">Пароль еще раз</label>
                            <input type="password" class="form-control" id="passwordTextboxRepeat"
                                   ref="password_confirmation"
                                   min="6" required/>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-12">
                            <file-chooser caption="Фото профиля" accept="image/*" @on-file-choose="onProfileImageChoose" />
                        </div>
                        <div class="col-12" v-if="profile_image_data">
                            <div class="d-inline-block position-relative me-2">
                                <remove-icon class="text-danger cursor-pointer position-absolute remove-icon" @click="removeProfileImage"/>
                                <img class="img-fluid img-thumbnail image:w100:px me-2"
                                     alt="Profile image"
                                     :src="profile_image_data"
                                />
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="mt-4 btn btn-success w-100" :disabled="isLoading">
                        Зарегистрироваться
                    </button>
                </form>

                <div class="my-3 text-center">
                    Если у Вас уже есть аккаунт, то можно
                    <router-link :to="loginPage">Войти</router-link>
                </div>

            </div>
        </main>
    </main-wrapper>

</template>

<script>
import MainWrapper from "@/common/components/MainWrapper.vue";
import {ref_value} from "@/utils/index.js";
import {authService} from "@/modules/account/api/AuthService.js";
import FileChooser from "@/common/components/FileChooser.vue";
import readFileAsDataURL from "@/utils/readAsDataUrl.js";
import RemoveIcon from "@/common/components/icons/RemoveIcon.vue";

export default {
    name: "CreateAccountPage",
    components: {RemoveIcon, FileChooser, MainWrapper},
    data() {
        return {
            profile_image_data: null,
            accountCreated: false,
            isLoading: false,
            errors: null
        }
    },
    mounted() {
        document.title = 'Регистрация';
    },
    computed: {
        loginPage() {
            return this.$router.resolve({ name: 'login' }).fullPath
        },
        errorExist() {
            return this.errors !== null;
        }
    },
    methods: {
        getFormFields() {

            const value = ref_value(this);

            return {
                firstname: value('firstname'),
                lastname: value('lastname'),
                department: value('department'),
                email: value('email'),
                phone: value('phone'),
                password: value('password'),
                password_confirmation: value('password_confirmation'),
                //avatar: this.profile_image_data?.generated_name || ''
                avatar: this.profile_image
            };
        },

        async handleSubmit() {
            const account = this.getFormFields();

            try {
                this.isLoading = true;
                const {data} = await authService().register(account);

                if (data) {
                    this.accountCreated = true;
                }
            } catch (error) {
                console.log(error)
                this.errors = error.response.data;
                console.log(error.response.data);
            } finally {
                this.isLoading = false;
            }
        },

        onProfileImageChoose(image) {

            this.profile_image = image;

            // read image to show in ui
            readFileAsDataURL(image)
                .then(data => this.profile_image_data = data)
                .catch(e => {
                    alert(e)
                });
        },

        removeProfileImage() {
            this.profile_image_data = null;
        }
    }

}
</script>

<style scoped>

.form-signin {
    --width-v1: 530px;
    --width-v2: 600px;
    /*width: 100%;*/
    max-width: var(--width-v2);
}

.form-signin .checkbox {
    font-weight: 400;
}

.form-signin .form-floating:focus-within {
    z-index: 2;
}


</style>

<template>
    <main-wrapper>
        <main class="container h-100 d-flex flex-column align-items-md-center justify-content-md-center pt-2">

            <div class="shadow-lg rounded py-4 px-4" v-if="isOk">
                <h4>Ваш заявка успешно отправлена!</h4>
                <p>После одобрения, перейдите на
                    <router-link :to="href('login')">страницу входа</router-link>
                    и введите Ваши данные
                </p>
            </div>

            <div class="shadow-lg rounded py-4 px-4 form-signin" v-else>
                <h2 class="mb-4">Заявка на смену пароля</h2>

                <div class="alert alert-warning" v-show="errorExist">
                    <!--<ul class="list-group">
                        <li class="list-group-item" v-for="error in errors">{{ error }}</li>
                    </ul>-->
                    {{ error_message }}
                </div>

                <form @submit.prevent="handleSubmit">

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label for="passwordChangePhoneTextbox" class="form-label">Логин (номер телефона)</label>
                            <input type="tel" class="form-control" id="passwordChangePhoneTextbox" ref="phone" pattern="(9|7)[0-9]{6}"
                                   maxlength="7"
                                   required/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label for="passwordChangeTextbox" class="form-label">Новый пароль</label>
                            <input type="password" class="form-control" id="passwordChangeTextbox" ref="password" min="6"
                                   required/>
                        </div>

                        <div class="mb-3">
                            <label for="passwordChangeTextboxRepeat" class="form-label">Пароль еще раз</label>
                            <input type="password" class="form-control" id="passwordChangeTextboxRepeat"
                                   ref="password_confirmation"
                                   min="6" required/>
                        </div>
                    </div>


                    <button type="submit" class="mt-4 btn btn-success w-100" :disabled="is_loading">
                        Отправить заявку
                    </button>
                </form>

                <div class="my-3 text-center">
                    <router-link :to="href('login')">На страницу входа</router-link>
                </div>

            </div>
        </main>
    </main-wrapper>
</template>

<script>
import MainWrapper from "@/common/components/MainWrapper.vue";
import {ref_value} from "@/utils/index.js";
import {requestsService} from "@/modules/account/api/RequestsService.js";

export default {
    name: "PasswordChangeRequestForm",
    components: {MainWrapper},

    data: () => ({
        is_loading: false,
        errorExist: false,
        isOk: false,
        errors: [],
        error_message: ''
    }),


    methods: {
        async handleSubmit() {

            let res = '';

            try {
                this.errorExist = false;
                const formData = this.getFormFields();
                res = await requestsService().passwordChangeRequest(formData);
                this.isOk = true;
            }
            catch (e) {
                this.errorExist = true;
                this.error_message = e?.response?.data?.message;
                console.error(e);
            }
            finally {

            }
        },

        getFormFields() {
            const value = ref_value(this);

            return {
                phone: value('phone'),
                password: value('password'),
                password_confirmation: value('password_confirmation'),
            };
        },
    }
}
</script>

<style scoped>

</style>

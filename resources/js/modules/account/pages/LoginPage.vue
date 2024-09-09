<template>
    <main-wrapper>
        <main class="container h-100 d-flex flex-column align-items-md-center justify-content-md-center pt-2">
            <div class="shadow-lg rounded py-4 px-4 form-signin">
                <h2 class="mb-4">Авторизация</h2>

                <div class="alert alert-warning" v-show="error != null">{{ formatErrorMessage(error?.message) || 'Неизвестная ошибка' }}</div>

                <form @submit.prevent="handleSubmit">

                    <div class="row">
                        <!--<div class="col-md-6 mb-3">
                            <label for="emailTextbox" class="form-label">Почта</label>
                            <input type="email" class="form-control" id="emailTextbox" ref="email" required/>
                        </div>-->

                        <div class="mb-3">
                            <label for="phoneTextbox" class="form-label">Номер телефона</label>
                            <input type="tel" class="form-control" id="phoneTextbox" ref="phone" pattern="(9|7)[0-9]{6}"
                                   maxlength="7"
                                   value="" required/>
                        </div>

                        <div class="mb-3">
                            <label for="passwordTextbox" class="form-label">Пароль</label>
                            <input type="password" class="form-control" id="passwordTextbox" ref="password" min="6"
                                   value="" required/>
                        </div>
                    </div>

                    <button type="submit" class="mt-4 btn btn-success w-100" :disabled="isLoading">
                        Войти
                    </button>
                </form>

                <div class="my-3 text-center">
                    Если у Вас еще нет аккаунта, то можете здесь
                    <router-link :to="route_url('signup')">создать аккаунт</router-link>
                </div>

            </div>
        </main>
    </main-wrapper>
</template>

<script>
import MainWrapper from "@/common/components/MainWrapper.vue";
import {ref_value} from "@/utils/index.js";
import {mapActions } from "vuex";

export default {
    name: "LoginPage",
    components: {MainWrapper},
    data() {
        return {
            isLoading: false,
            error: null
        }
    },
    mounted() {
        document.title = 'Авторизация';
    },
    computed: {
        //...mapState('authStore', ['isLoading', 'error', 'token']),
        //...mapGetters('authStore', ['error']),
    },
    methods: {

        ...mapActions('authStore', ['login']),

        formatErrorMessage(error) {
            return error;

            return {
                'Unauthorized': 'Неправильный логин или пароль',
                '_default': 'Неизвестная ошибка при входе в личный кабинет'
            }[error?.message || '_default']
        },

        getFormFields() {

            const value = ref_value(this);

            return {
                phone: value('phone'),
                password: value('password')
            }
        },

        handleSubmit()
        {
            this.isLoading = true;
            this.error = null;

            this.login(this.getFormFields())
                .then(() => {

                    window.location.href = this.route_url('dashboard').fullPath;
                    //alert('Got to dashboard');
                    //this.$router.push({name: 'dashboard'});
                })
                .catch((error) => {
                    // console.error(error?.response?.data?.message);
                    this.error = error?.response?.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });

        }
    }
}
</script>

<style scoped>
.form-signin {
    --width-v1: 530px;
    --width-v2: 600px;
    /*width: 100%;*/
    max-width: var(--width-v1);
}

</style>

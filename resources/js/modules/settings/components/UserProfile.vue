<template>
  <div class="ps-md-2">

      <div class="row">

          <div class="col-md-3 p-0">

              <div class="card h-100 m-0">
                  <div class="card-body text-center">
                      <img :src="profileImageOrDefault(user?.avatar)" class="img-fluid rounded-3"  alt="profile image" />
                  </div>
                  <div class="card-footer text-center">
                    <div>
                        <file-upload caption="Загрузить" @on-upload="onProfileImageUploaded" class="btn-primary" accept="image/*" />
                    </div>
                  </div>
              </div>
          </div>

          <div class="col-md-9">
              <table class="table table-responsive table-bordered">
                <tr>
                    <td>Идентификатор</td>
                    <td class="fw-bold">{{ user.id }}</td>
                </tr>

                  <tr>
                      <td>ФИО</td>
                      <td class="fw-bold">{{ user.lastname }} {{ user.firstname }}</td>
                  </tr>

                  <tr>
                      <td>Группа</td>
                      <td class="fw-bold">{{ format_department(user.department) }}</td>
                  </tr>

                  <tr>
                      <td>Номер телефона</td>
                      <td class="fw-bold">{{ hideValueWithStars(user.phone) }}</td>
                  </tr>

                  <tr>
                      <td>Почта</td>
                      <td class="fw-bold">{{ user.email }}</td>
                  </tr>

                  <tr>
                      <td>Роль</td>
                      <td class="fw-bold">{{ user.role }}</td>
                  </tr>

                  <tr>
                      <td>Статус</td>
                      <td class="fw-bold">{{ user.status }}</td>
                  </tr>

                  <tr>
                      <td>Зарегистрирован</td>
                      <td class="fw-bold">{{ dt_format(user.created_at) }}</td>
                  </tr>
              </table>
          </div>
      </div>

  </div>
</template>

<script>
import {mapGetters} from "vuex";
import FileUpload from "@/common/components/FileUpload.vue";
import {accountService} from "@/modules/account/api/CreateAccountService.js";
import store from "../../../store/index.js";

export default {
    name: "UserProfile",
    components: {FileUpload},

    data() {
        return {
            profilePhoto: null
        }
    },

    computed: {
        ...mapGetters('authStore', ['user']),
    },

    methods: {

        profileImageOrDefault(image) {
            return !image?.trim() ? 'images/no_avatar.png' : `upload/${image}`;
        },

        format_department(dep) {
            return {
                finance: 'Финансы и Кредит',
                national: 'Национальная Экономика',
                accountant: 'Бухгалтерский учет и Аудит',
                '_default': 'Неизвестная группа'
            }[dep || '_default']
        },

        hideValueWithStars(value) {
            const str = String(value).trim();
            const last4DigitsStr = str.substring(5);
            return last4DigitsStr.padStart(7, '*');
        },

        onProfileImageUploaded(profileImage)
        {
            accountService.saveProfileImage(profileImage.generated_name).then(() => {
                store.commit('authStore/SET_AVATAR', profileImage.generated_name);
                //this.profilePhoto = profileImage;
            }).catch(err => alert(err));
        }
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


/* d-flex flex-column align-items-center justify-content-md-center */
.profile-image-container {
    max-width: 250px;
}

</style>

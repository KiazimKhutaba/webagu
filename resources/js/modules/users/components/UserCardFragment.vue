<template>
    <div class="card h-100 shadow shadow-sm">
        <img :src="profile_image" class="card-img-top max-w-30%" :alt="username">
        <div class="card-body d-flex flex-column">
            <div class="mt-auto">
                <h5 class="card-title mb-2" v-text="username"></h5>
                <p class="card-text">{{ format_department(user.department) }}</p>
                <router-link :to="`/user/${user.id}/profile`" class="w-100 btn btn-sm btn-outline-primary">Профиль</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "UserCardFragment",
    props: {
        user: {
            type: Object,
            required: false
        }
    },

    computed: {
        username() {
            return `${this.user?.lastname} ${this.user?.firstname}`;
        },

        profile_image() {
            return this.user?.avatar ? '/images/avatars/' + this.user?.avatar : '/images/no_avatar.png';
        }
    },

    methods: {

    }
}
</script>

<style scoped>
.max-w-30\% {
    /*max-width: 20%;*/
    /*max-height: 800px;*/
}
</style>

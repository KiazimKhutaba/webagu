<template>
    <header class="navbar sticky-top bg-success flex-md-nowrap p-0 shadow" data-bs-theme="dark">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">
            WebAGU
        </a>

        <div class="text-white px-3 fw-bolder welcome-text">👋 {{ username }}</div>

        <ul class="navbar-nav flex-row d-md-none">
            <li class="nav-item text-nowrap">
                <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <svg class="bi"><use xlink:href="#list"></use></svg>
                </button>
            </li>
        </ul>
    </header>
</template>

<script>
import store from "../../store/index.js";

export default {
    name: "AppHeader",

    computed: {
        username() {
            if(store.getters['authStore/user'] !== null) {
                const { lastname, firstname } = store.getters['authStore/user'];
                return `${lastname} ${firstname}`;
            }

        }
    }
}
</script>

<style scoped>
@media screen and (max-width: 576px) {
    .welcome-text {
        font-size: 0.85rem;
    }
}

</style>

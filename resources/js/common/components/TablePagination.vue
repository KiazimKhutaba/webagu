<template>
    <nav aria-label="Table posts pagination" v-show="pagination !== null">
        <ul class="pagination justify-content-end">
            <li v-for="link in pagination?.links" class="page-item">
                <router-link :class="{'page-link': true, active: link.active}" :to="link.url">{{ link.label }}</router-link>
            </li>
        </ul>
    </nav>
<!--    <nav aria-label="Posts pagination">
        <div class="container my-5">
            <ul class="pagination justify-content-center">

                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
                {% for page in 1..pages + 1 %}
                    {% if page == active_page %}
                    <li class="page-item active"><a class="page-link" href="/?page={{ page }}">{{ page }}</a></li>
                    {% else %}
                    <li class="page-item"><a class="page-link" href="/?page={{ page }}">{{ page }}</a></li>
                    {% endif %}
                {% endfor %}
                {#<li class="page-item" aria-current="page">
                <a class="page-link" href="/?page=2">2</a>
            </li>
                <li class="page-item"><a class="page-link" href="/?page=3">3</a></li>#}
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </div>
    </nav>-->
</template>

<script>
export default {
    name: "TablePagination",
    props: {
        pagination: {
            type: Object,
            /*default: {
                'current_page': 1,
                'data': [],
                'first_page_url': 'http:\/\/192.168.100.43\/api\/users?page=1',
                'from': 1,
                'last_page': 3,
                'last_page_url': 'http:\/\/192.168.100.43\/api\/users?page=3',
                'links': [
                    {
                        'url': null,
                        'label': '&laquo; Предыдущая',
                        'active': false
                    },
                    {
                        'url': 'http:\/\/192.168.100.43\/api\/users?page=1',
                        'label': '1',
                        'active': true
                    },
                    {
                        'url': 'http:\/\/192.168.100.43\/api\/users?page=2',
                        'label': '2',
                        'active': false
                    },
                    {
                        'url': 'http:\/\/192.168.100.43\/api\/users?page=3',
                        'label': '3',
                        'active': false
                    },
                    {
                        'url': 'http:\/\/192.168.100.43\/api\/users?page=2',
                        'label': 'Следующая &raquo;',
                        'active': false
                    }
                ],
                'next_page_url': 'http:\/\/192.168.100.43\/api\/users?page=2',
                'path': 'http:\/\/192.168.100.43\/api\/users',
                'per_page': 5,
                'prev_page_url': null,
                'to': 5,
                'total': 14
            }*/
        },
    },

    mounted() {
        console.log(this.pagination)
    },

    watch: {
        /*page(newValue) {
            console.log(newValue);
            this.page = newValue;
        }*/
    }
}
</script>

<style scoped>

</style>

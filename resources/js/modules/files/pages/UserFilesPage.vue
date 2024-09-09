<template>
    <dashboard-wrapper>
        <page-header caption="Загруженные файлы">
            <file-upload caption="Добавить файла" @on-upload="onFileUpload"/>
        </page-header>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Название</th>
                    <th scope="col">Тип</th>
                    <th scope="col">Размер</th>
                    <th scope="col">Создан</th>
                    <th scope="col">Автор</th>
                    <th scope="col">Источник</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr class="cursor-pointer" v-for="(file, counter) in files" :data-id="file.id"
                    :data-name="file.original_name">
                    <td>{{ ++counter }}</td>
                    <td><a :href="'/upload/' + file.generated_name" target="_blank">{{ file.original_name }}</a></td>
                    <td>{{ getFileExtension(file) }}</td>
                    <td>{{ formatBytes(file.size) }}</td>
                    <td>{{ dt_format2(file.created_at) }}</td>
                    <td>{{ username(file?.user) }}</td>
                    <td>{{ fileSource(file?.object_source) }}</td>
                    <td>
                        <span class="text-primary file-remove" @click="removeFile">Удалить</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="row">
            <pagination :pagination="pagination" />
        </div>

    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import QTable from "@/common/components/QTable.vue";
import formatBytes from "@/utils/formatBytes.js";
import FileUpload from "@/common/components/FileUpload.vue";
import ChooseFilesDialog from "@/common/components/ChooseFilesDialog.vue";
import Pagination from "@/common/components/Pagination.vue";
import {filesService} from "@/modules/files/api/FilesService.js";


export default {
    name: "UserFilesPage",
    components: {Pagination, ChooseFilesDialog, FileUpload, QTable, PageHeader, DashboardWrapper},
    data() {
        return {
            files: [],
            pagination: []
        }
    },

    async mounted() {

        const {data} = await filesService().paginate(this.$route.query);
        this.files = data.data;
        this.pagination = data;

        //this.$refs.booksListDialog.addEventListener('hide.bs.modal', this.clearFilesListState);
    },

    beforeUnmount() {
        //this.$refs.booksListDialog.removeEventListener('hide.bs.modal', this.clearFilesListState);
    },

    computed: {},

    methods: {

        onFilesAdded(files) {
            console.log(files);
        },

        username(user) {
            return user ? `${user?.lastname || ''} ${user?.firstname || ''}` : '';
        },

        fileSource(source) {
            return {
                'files.form': 'Файлы (админ)',
                'lecture': 'Лекция (админ)',
                'task.form': 'Задача (админ)',
                'task.review': 'Задача (на проверку)'
            }[source] ?? source;
        },

        getFileExtension(file) {
            return file.generated_name?.split('.')[1];
        },

        formatBytes(bytes, decimal) {
            return formatBytes(bytes, decimal);
        },

        async removeFile(e) {
            const file = e.target.closest('tr').dataset;

            if (!confirm(`Вы действительно хотите удалить файл ${file.name}?`)) return;

            try {
                const {data} = await filesService().remove(+file.id);
                //console.log(data);
                this.files = this.files.filter(_file => +_file.id !== +file.id);
            } catch (error) {
                alert(error.message);
            }

        },

        onFileUpload(uploadedFile) {
            this.files = [...this.files, uploadedFile];
        }
    }
    ,
}
</script>

<style scoped>
.file-remove:hover {
    text-decoration: underline;
}
</style>

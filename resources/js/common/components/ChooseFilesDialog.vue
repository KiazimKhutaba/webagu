<template>
    <modal-dialog :id="targetId" title="Выберите файл" ref="booksListDialog">

        <div class="mt-2 mb-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Введите название файла..."
                       aria-label="Название файла" aria-describedby="button-clear-input"
                       v-model="filename"
                       @keyup="debouncedSearch">
                <button class="btn btn-sm btn-outline-primary" type="button" id="button-clear-input" @click="clearSearch">
                    Очистить
                </button>
            </div>
        </div>

        <div class="mb-4" v-show="chosenFiles.length > 0">
            <p class="fw-bold">Выбранные файлы</p>
            <ul class="list-group overflow-y-auto py-1" style="max-height: 200px" >
                <li class="list-group-item" v-for="chosenFile in chosenFiles">
                    <div class="d-flex justify-content-between">
                        <div>{{ chosenFile.original_name }}</div>
                        <div class="cursor-pointer px-2 text-danger" @click="removeFile" :data-file-id="chosenFile.id">Убрать</div>
                    </div>
                </li>
            </ul>
        </div>

        <table class="table table-hover table-responsive overflow-x-scroll"
               v-if="searchResult.length > 0" id="foundFiles">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Название</th>
                <th class="text-center" scope="col">Выбрать</th>
            </tr>
            </thead>
            <tbody>
            <tr class="cursor-pointer" v-for="(file, counter) in searchResult" :data-id="file.id"
                :data-original="file.original_name" :data-generated="file.generated_name">
                <td>{{ ++counter }}</td>
                <td><label :for="file.generated_name">{{ file.original_name }}</label></td>
                <td class="text-center">
                    <!--@change="fileChosen"-->
                    <input class="form-check-input border-secondary" type="checkbox" :value="file" v-model="chosenFiles"/>
                </td>
            </tr>
            </tbody>
        </table>

        <div v-else-if="emptySearchResult">
            <h4 class="h6">По запросу <b>{{ filename }}</b> не найдено ни одного файла</h4>
        </div>

        <template #footer>
            <file-upload class="btn btn-sm btn-warning" caption="Загрузить файл" @upload-file="onFileUpload"  id="choose_file_dialog_btn"/>
            <button class="btn btn-sm btn-primary" data-bs-dismiss="modal" @click="addFiles">Добавить файлы</button>
        </template>

    </modal-dialog>
</template>

<script>
import ModalDialog from "../components/ModalDialog.vue";
import debounce from "@/utils/debounce.js";
import FileUpload from "./FileUpload.vue";
import {filesService} from "@/modules/files/api/FilesService.js";


export default {
    name: "ChooseFilesDialog",
    components: {FileUpload, ModalDialog},
    emits: ['onFilesAdded'],
    props: {
        targetId: {
            type: String,
            required: true
        }
    },
    data: () => ({
        chosenFiles: [],
        filename: '',
        searchResult: [],
        debouncedSearch: null,
    }),
    created() {
        // @see https://stackoverflow.com/questions/53041171/lodashs-debounce-not-working-in-vue-js
        this.debouncedSearch = debounce(this.search, 500);
    },

    mounted() {
        /*document.querySelector('#foundFiles').addEventListener('keydown', (e) => {
            if (e.key.toLowerCase() === 'a' && e.ctrlKey) {
                // Add your code here
                alert('S key pressed with ctrl');
            }
        });*/
    },

    computed: {
        emptySearchResult() {
            return this.filename.length !== 0 && this.searchResult.length === 0;
        }
    },

    methods: {


        clearSearch() {
            this.chosenFiles = [];
            this.filename = '';
            this.searchResult = [];
        },

        removeFile(e) {
            const fileId = +e.target.dataset.fileId;
            this.chosenFiles = this.chosenFiles.filter(f => +f.id !== +fileId);
        },

        async search(e) {

            const filename = e.target.value?.trim();

            if (0 === filename.length) {
                this.searchResult = [];
                return;
            }

            try {
                const {data} = await filesService().search(filename);
                this.searchResult = data;
            } catch (e) {
                //console.error(e)
                alert(e);
            }

        },

        onFileUpload(file) {
            this.chosenFiles = [...this.chosenFiles, file];
        },

        addFiles() {
            this.$emit('onFilesAdded', this.chosenFiles);
            this.clearSearch();
        }
    },
}
</script>

<style scoped>

</style>

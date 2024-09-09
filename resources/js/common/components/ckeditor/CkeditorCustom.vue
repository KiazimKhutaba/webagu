<template>
    <div>
        <div class="main-container">
            <div class="editor-container editor-container_classic-editor" ref="editorContainerElement">
                <div class="editor-container__editor">
                    <div ref="editorElement">
                        <ckeditor v-if="isLayoutReady" :model-value="content" @input="onEditorInput" :editor="editor"
                                  :config="config"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    ClassicEditor,
    AccessibilityHelp,
    Autoformat,
    AutoImage,
    AutoLink,
    Autosave,
    BalloonToolbar,
    BlockQuote,
    Bold,
    CloudServices,
    Code,
    CodeBlock,
    Essentials,
    FindAndReplace,
    FullPage,
    GeneralHtmlSupport,
    Heading,
    Highlight,
    HorizontalLine,
    HtmlComment,
    HtmlEmbed,
    ImageBlock,
    ImageCaption,
    ImageInline,
    ImageInsert,
    ImageInsertViaUrl,
    ImageResize,
    ImageStyle,
    ImageTextAlternative,
    ImageToolbar,
    ImageUpload,
    Indent,
    IndentBlock,
    Italic,
    Link,
    LinkImage,
    List,
    ListProperties,
    Paragraph,
    SelectAll,
    ShowBlocks,
    SimpleUploadAdapter,
    SourceEditing,
    SpecialCharacters,
    SpecialCharactersArrows,
    SpecialCharactersCurrency,
    SpecialCharactersEssentials,
    SpecialCharactersLatin,
    SpecialCharactersMathematical,
    SpecialCharactersText,
    Strikethrough,
    Table,
    TableCellProperties,
    TableProperties,
    TableToolbar,
    TextPartLanguage,
    TextTransformation,
    //Title,
    TodoList,
    Underline,
    Undo, Alignment
} from 'ckeditor5';

import {Ckeditor} from "@ckeditor/ckeditor5-vue";
import {BASE_URL} from "@/common/config.js";

//import translations from 'ckeditor5/translations/ru.js'

import 'ckeditor5/ckeditor5.css';
import './custom.css';
import CustomShortcuts from "@/common/components/ckeditor/Plugins/CustomShortcuts.js";

export default {
    name: "CkeditorCustom",
    components: {Ckeditor},

    props: {
        modelValue: {
            type: String,
            required: true,
        },
        csrfToken: {
            type: String,
            required: false
        },
        bearerToken: {
            type: String,
            required: true
        }
    },

    emits: ['update:modelValue'],

    data() {
        return {
            isLayoutReady: false,
            content: '',
            config: null, // CKEditor needs the DOM tree before calculating the configuration.
            editor: ClassicEditor
        };
    },
    mounted() {
        this.config = {
            tabSpaces: 4,
            toolbar: {
                items: [
                    'undo',
                    'redo',
                    '|',
                    'sourceEditing',
                    'showBlocks',
                    '|',
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    '|',
                    'link',
                    'insertImage',
                    'insertTable',
                    'highlight',
                    'blockQuote',
                    'codeBlock',
                    '|',
                    'bulletedList',
                    'numberedList',
                    'todoList',
                    'outdent',
                    'indent',
                    '|',
                    'alignment'
                ],
                shouldNotGroupWhenFull: false
            },
            simpleUpload: {
                // The URL that the images are uploaded to.
                uploadUrl: `${BASE_URL}/files/ckeditor/upload`,

                // Enable the XMLHttpRequest.withCredentials property.
                withCredentials: true,

                // Headers sent along with the XMLHttpRequest to the upload server.
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    Authorization: `Bearer ${this.bearerToken}`
                }
            },
            plugins: [
                AccessibilityHelp,
                Alignment,
                Autoformat,
                AutoImage,
                AutoLink,
                Autosave,
                BalloonToolbar,
                BlockQuote,
                Bold,
                CloudServices,
                Code,
                CodeBlock,
                Essentials,
                FindAndReplace,
                FullPage,
                GeneralHtmlSupport,
                Heading,
                Highlight,
                HorizontalLine,
                HtmlComment,
                HtmlEmbed,
                ImageBlock,
                ImageCaption,
                ImageInline,
                ImageInsert,
                ImageInsertViaUrl,
                ImageResize,
                ImageStyle,
                ImageTextAlternative,
                ImageToolbar,
                ImageUpload,
                Indent,
                IndentBlock,
                Italic,
                Link,
                LinkImage,
                List,
                ListProperties,
                Paragraph,
                SelectAll,
                ShowBlocks,
                SimpleUploadAdapter,
                SourceEditing,
                SpecialCharacters,
                SpecialCharactersArrows,
                SpecialCharactersCurrency,
                SpecialCharactersEssentials,
                SpecialCharactersLatin,
                SpecialCharactersMathematical,
                SpecialCharactersText,
                Strikethrough,
                Table,
                TableCellProperties,
                TableProperties,
                TableToolbar,
                TextPartLanguage,
                TextTransformation,
                //Title,
                TodoList,
                Underline,
                Undo,
                CustomShortcuts
            ],
            balloonToolbar: ['bold', 'italic', '|', 'link', 'insertImage', '|', 'bulletedList', 'numberedList'],
            heading: {
                options: [
                    {
                        model: 'paragraph',
                        title: 'Paragraph',
                        class: 'ck-heading_paragraph'
                    },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            htmlSupport: {
                allow: [
                    {
                        name: /^.*$/,
                        styles: true,
                        attributes: true,
                        classes: true
                    }
                ]
            },
            image: {
                toolbar: [
                    'toggleImageCaption',
                    'imageTextAlternative',
                    '|',
                    'imageStyle:inline',
                    'imageStyle:wrapText',
                    'imageStyle:breakText',
                    '|',
                    'resizeImage'
                ]
            },
            initialData: '',
            language: 'en',
            link: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://',
                decorators: {
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            menuBar: {
                isVisible: true
            },
            placeholder: 'Добавьте здесь Ваш текст...',
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
            },
            //translations: [translations]
        };

        this.isLayoutReady = true;
    },

    methods: {
        onEditorInput(text) {
            this.$emit('update:modelValue', text);
        }
    }
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap');

@media print {
    body {
        margin: 0 !important;
    }
}

.main-container {
    font-family: 'Lato', sans-serif;
}

.ck-content {
    font-family: 'Lato', sans-serif;
    line-height: 1.6;
    word-break: break-word;
}

.editor-container_classic-editor .editor-container__editor {
    /*min-width: 795px;
    max-width: 795px;*/
}

.ck-editor__editable {
    min-height: 500px !important;
}

.ck-editor .ck-editor__main .ck-content {
    min-height: 500px !important;
}

</style>

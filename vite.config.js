import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    resolve: {
        alias: [
            {
                find: '@',
                replacement:  path.resolve(__dirname, './resources/js'),
            },
            //'@OpenForms': path.resolve(__dirname, './resources/js/OpenForms')
        ]
    },
    plugins: [
        laravel({
            input: [
                'resources/css/bootstrap.min.css',
                'resources/css/bootstrap-icons.css',
                // 'resources/css/app.css',
                // 'resources/js/assets/bootstrap.min.js',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    // The Vue plugin will re-write asset URLs, when referenced
                    // in Single File Components, to point to the Laravel web
                    // server. Setting this to `null` allows the Laravel plugin
                    // to instead re-write asset URLs to point to the Vite
                    // server instead.
                    base: null,

                    // The Vue plugin will parse absolute URLs and treat them
                    // as absolute paths to files on disk. Setting this to
                    // `false` will leave absolute URLs un-touched so they can
                    // reference assets in the public directory as expected.
                    includeAbsolute: false,
                },
            },
        }),
    ],
    define: {
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: 'true'
    }
    /*server: {
        host: '0.0.0.0',
        port: 3000
    },*/
});

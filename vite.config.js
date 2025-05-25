import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    base: '/build/',
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js',
            'jquery': 'jquery/dist/jquery.min.js',
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                includePaths: ['resources/sass'],
            }
        }
    }
});

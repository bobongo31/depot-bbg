import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // Ajout du plugin Vue

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js', // Assure-toi que tu n'utilises pas de scss ici si tu n'en as plus besoin
            ],
            refresh: true,
        }),
        vue(), // Activation du plugin Vue
    ],
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js',
            'jquery': 'jquery/dist/jquery.min.js' // Alias pour activer la compilation des templates Vue
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                // Configuration pour inclure les chemins de ressources SCSS
                includePaths: ['resources/sass'],
            }
        }
    }
});

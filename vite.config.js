import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                //'resources/css/bootstrap.css',
                'resources/css/app.css',
            ],
            refresh: true,
        }),
        react(), // Ajoutez le plugin React ici
    ],
});

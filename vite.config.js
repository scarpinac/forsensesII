import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/sistema/menu.js',
                'resources/js/sistema/permissao.js',
                'resources/js/sistema/perfil.js',
                'resources/js/sistema/usuario.js',
                'resources/scss/custom.scss'
            ],
            refresh: true,
        }),
    ],
});

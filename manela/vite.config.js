import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js',
                'resources/css/san-doces.css',
                'resources/js/san-doces.js',
                'resources/css/lm-home.css',
                'resources/js/lm-home.js',
                'resources/js/cadastro_usuario.js',
                'resources/js/login.js',
                'resources/js/pedidos.js',
                'resources/js/produtos.js',
                'resources/js/carrinho.js',
                'resources/js/cadastro_produto.js',
                'resources/js/dashboard.js'
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});



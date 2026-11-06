import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                    'resources/js/app.js',
                    'resources/js/login.js',
                    'resources/js/kasir.js',
                    'resources/js/riwayat.js',
                    'resources/js/pembayaran.js',
                    'resources/js/laporanstok.js',
                    'resources/js/admin.js',
                    'resources/css/admin.css',
                    'resources/css/stok.css',
                    'resources/js/riwayatbulanan.js',
                    'resources/js/sidebar.js'
                    ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

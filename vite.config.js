import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/dashboard.jsx',
                'resources/js/chat.jsx',
                'resources/js/admin-chat.jsx',
            ],
            refresh: true,
        }),
        react(), // move this AFTER laravel()
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
    build: {
        sourcemap: false, // Disable source maps in production
    },
    server: {
        hmr: {
            overlay: true,
        },
    },
});

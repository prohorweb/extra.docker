import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        https: {
            key: fs.readFileSync('/etc/nginx/ssl/key.pem'),
            cert: fs.readFileSync('/etc/nginx/ssl/cert.pem'),
        },
        hmr: {
            host: 'extra.new',     // ваш домен
            protocol: 'wss',       // WebSocket Secure
            clientPort: 443,       // основной HTTPS-порт
            path: '/__vite_hmr',
        },
        watch: {
            usePolling: true,
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
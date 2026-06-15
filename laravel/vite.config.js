import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/home-slider.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        // Allow cross-origin requests from any *.extra.new subdomain (dev only)
        cors: {
            origin: /https?:\/\/([a-z0-9-]+\.)?extra\.new(:\d+)?$/,
            credentials: true,
        },
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
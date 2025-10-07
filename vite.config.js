import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        // Specify your custom chunk names here
                        const packageName = id.match(/\/node_modules\/([^/]+)/)[1];
                        return `chunk-${packageName}`;
                    }
                }
            }
        }
    }
});

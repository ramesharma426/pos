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
    css: {
        // Vite 8 minifies CSS with LightningCSS, which rejects the legacy IE
        // star-property hacks in third-party CSS (e.g. selectize's `*display`).
        // errorRecovery strips those invalid declarations instead of failing.
        lightningcss: {
            errorRecovery: true,
        },
    },
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

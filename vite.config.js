import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import inertia from '@inertiajs/vite';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

// Base44 sandbox: advertise the externally-reachable Vite dev server origin so
// the browser (behind the preview proxy) can load dev assets and use HMR.
// Gated on BASE44_PREVIEW_MODE so the original behavior is unchanged when unset.
const previewMode = process.env.BASE44_PREVIEW_MODE === '1';
const devOrigin = previewMode ? process.env.VITE_DEV_SERVER_ORIGIN : undefined;

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: true,
        }),
        inertia(),
        react(),
        tailwindcss(),
    ],
    server: {
        origin: devOrigin,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

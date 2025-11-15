import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { copyFileSync, existsSync } from 'fs';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'], // ✅ Correct input for Vite + Laravel
            refresh: true,
            buildDirectory: 'build', // ✅ Laravel expects manifest in /build/
        }),

        {
            name: 'copy-pwa-assets',
            closeBundle() {
                const pwaFiles = [
                    { src: 'sw.js' },
                    { src: 'browserconfig.xml' },
                    { src: 'offline.html' },
                    { src: 'site.webmanifest' }, // ✅ Your renamed PWA manifest
                ];

                pwaFiles.forEach(({ src, dest }) => {
                    const sourcePath = resolve(__dirname, 'public', src);
                    const destinationPath = resolve(__dirname, 'public/build', dest ?? src);

                    if (existsSync(sourcePath)) {
                        try {
                            copyFileSync(sourcePath, destinationPath);
                            console.log(`Copied: ${src}${dest ? ' -> ' + dest : ''}`);
                        } catch (err) {
                            console.warn(`Failed to copy ${src}`);
                        }
                    }
                });
            },
        },
    ],

    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: 'manifest.json',
    },

    server: {
        host: 'localhost',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
    },
});

import { copyFileSync, existsSync, mkdirSync } from 'fs';
import { join } from 'path';

const publicDir = './public';
const buildDir = './public/build';

// Ensure build directory exists
if (!existsSync(buildDir)) {
    mkdirSync(buildDir, { recursive: true });
}

// PWA files to copy
const pwaFiles = [
    'manifest.json',
    'sw.js',
    'browserconfig.xml',
    'offline.html'
];

console.log('📦 Copying PWA assets to build directory...');

pwaFiles.forEach(file => {
    const src = join(publicDir, file);
    const dest = join(buildDir, file);

    if (existsSync(src)) {
        try {
            copyFileSync(src, dest);
            console.log(`✅ Copied: ${file}`);
        } catch (err) {
            console.error(`❌ Failed to copy ${file}:`, err.message);
        }
    } else {
        console.warn(`⚠️  File not found: ${file}`);
    }
});

console.log('✨ PWA assets copied successfully!');

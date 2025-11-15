// generate-splash.js
import sharp from 'sharp';
import { mkdirSync, existsSync } from 'fs';

const splashDir = './public/splash';
if (!existsSync(splashDir)) {
    mkdirSync(splashDir, { recursive: true });
}

const splashSizes = [
    { name: 'iPhone_15_Pro_Max_portrait', width: 1290, height: 2796 },
    { name: 'iPhone_15_Pro_portrait', width: 1179, height: 2556 },
    { name: 'iPhone_14_Plus_portrait', width: 1284, height: 2778 },
    { name: 'iPhone_14_portrait', width: 1170, height: 2532 },
    { name: 'iPhone_13_mini_portrait', width: 1125, height: 2436 },
    { name: 'iPad_Pro_12.9_portrait', width: 2048, height: 2732 },
    { name: 'iPad_Pro_11_portrait', width: 1668, height: 2388 },
    { name: 'iPad_Air_portrait', width: 1640, height: 2360 },
    { name: 'iPad_portrait', width: 1620, height: 2160 },
    { name: 'iPad_9.7_portrait', width: 1536, height: 2048 }
];

console.log('🎨 Generating iOS splash screens...');

Promise.all(
    splashSizes.map(({ name, width, height }) => {
        const output = `${splashDir}/${name}.png`;

        // Create gradient background with logo
        return sharp({
            create: {
                width: width,
                height: height,
                channels: 4,
                background: { r: 59, g: 130, b: 246, alpha: 1 } // #3B82F6
            }
        })
            .composite([
                {
                    input: './public/Scholder-512.png',
                    blend: 'over',
                    gravity: 'center'
                }
            ])
            .png()
            .toFile(output)
            .then(() => console.log(`✅ Generated: ${name}.png`))
            .catch(err => console.error(`❌ Failed ${name}:`, err.message));
    })
).then(() => {
    console.log('✨ All splash screens generated!');
});

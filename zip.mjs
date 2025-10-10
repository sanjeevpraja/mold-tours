import { zip } from 'zip-a-folder';
import fs from 'fs';
import { readFile } from 'fs/promises';
import path from 'path';
const pkg = JSON.parse(await readFile(new URL('./package.json', import.meta.url)));

const themeName = pkg.name || 'mold-tour';
const version = pkg.version || '1.0.0';
const rootDir = process.cwd();
const outputDir = path.join(rootDir, 'dist');
const outputZip = path.join(outputDir, `${themeName}.zip`);
const tempDir = path.join(rootDir, '.zip_temp');

// Folders/files to exclude
const excludeList = [
  'node_modules',
  'src',
  '.git',
  '.gitignore',
  'package.json',
  'package-lock.json',
  'yarn.lock',
  'gulpfile.js',
  'gulpfile.mjs',
  '.zip_temp',
  'dist',
  'zip.js',
  'webpack.config.js',
  '.idea'
];

// Clean dist + temp
if (fs.existsSync(outputDir)) fs.rmSync(outputDir, { recursive: true, force: true });
if (fs.existsSync(tempDir)) fs.rmSync(tempDir, { recursive: true, force: true });

fs.mkdirSync(outputDir, { recursive: true });
fs.mkdirSync(tempDir, { recursive: true });

// Copy only allowed files into .zip_temp
fs.readdirSync(rootDir).forEach(file => {
  if (excludeList.includes(file)) return;

  const srcPath = path.join(rootDir, file);
  const destPath = path.join(tempDir, file);
  const stat = fs.statSync(srcPath);

  if (stat.isDirectory()) {
    fs.cpSync(srcPath, destPath, { recursive: true });
  } else {
    fs.copyFileSync(srcPath, destPath);
  }
});

// Create zip from .zip_temp
(async () => {
  await zip(tempDir, outputZip);
  console.log(`✅ Zip created at: ${outputZip}`);

  // Cleanup temp folder
  fs.rmSync(tempDir, { recursive: true, force: true });
})();

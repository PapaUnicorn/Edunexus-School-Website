import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  build: {
    outDir: resolve(__dirname, 'assets'),
    emptyOutDir: false,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/main.js')
      },
      output: {
        entryFileNames: 'js/main.js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: ({ name }) => {
          if (name && name.endsWith('.css')) {
            return 'css/style.[ext]';
          }
          return 'images/[name].[ext]';
        }
      }
    }
  }
});

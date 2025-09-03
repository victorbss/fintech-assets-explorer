/// <reference types="vite/client" />
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/ts/app.ts'],
      refresh: true
    }),
    vue()
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/ts')
    }
  },
  server: {
    host: '0.0.0.0',
    port: 5173,
    strictPort: true,
    hmr: {
      host: 'localhost'
    }
  }
})
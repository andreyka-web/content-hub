import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  preview: {
    port: 3001,
  },
  server: {
    host: true,
    port: 3001,
    watch: {
      usePolling: true
    }
  },
  plugins: [
    vue({
      template: {
        compilerOptions: {
          // treat all tags with a dash as custom elements
       //   isCustomElement: (tag) => tag.startsWith("ui-"),
        },
      },
    }),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  }
})

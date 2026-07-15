import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// إعدادات Vite الأساسية لمشروع وفر كاش
export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5173
  }
})

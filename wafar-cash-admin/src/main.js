import { createApp } from 'vue'
import App from './App.vue'
import router from './router/router.js'
import './assets/styles.css'

// إنشاء تطبيق Vue الرئيسي وربط الراوتر
const app = createApp(App)
app.use(router)
app.mount('#app')

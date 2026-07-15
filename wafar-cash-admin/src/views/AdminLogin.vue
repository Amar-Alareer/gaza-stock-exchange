<template>
  <!-- واجهة 1: صفحة تسجيل دخول المسؤول -->
  <div class="login-page">
    <div class="login-card">
      <!-- القسم الأيسر: النموذج -->
      <div class="login-card__form">
        <div class="login-card__badge">
          <span class="login-card__badge-icon" style="display: inline-flex; align-items: center;">
            <KeyIcon :size="14" />
          </span>
          <span>تسجيل دخول الإدارة</span>
        </div>

        <h1 class="login-card__title">تسجيل دخول المسؤول</h1>
        <p class="login-card__subtitle">أدخل بياناتك للوصول إلى لوحة التحكم</p>

        <form @submit.prevent="handleLogin">
          <label class="field-label" for="username">اسم المستخدم أو البريد الإلكتروني الإداري</label>
          <div class="field-input">
            <span class="field-input__icon" style="display: flex; align-items: center;">
              <UserIcon :size="18" />
            </span>
            <input id="username" v-model="username" type="text" placeholder="اسم المستخدم" />
          </div>

          <label class="field-label" for="password">كلمة المرور الإدارية</label>
          <div class="field-input">
            <span class="field-input__icon" style="display: flex; align-items: center;">
              <LockIcon :size="18" />
            </span>
            <input id="password" v-model="password" type="password" placeholder="••••••••" />
          </div>

          <div class="login-card__row">
            <label class="remember-me">
              <input type="checkbox" v-model="rememberMe" />
              <span>تذكرني</span>
            </label>
            <a href="#" class="forgot-link">نسيت كلمة المرور؟</a>
          </div>

          <!-- رسالة الخطأ -->
          <div v-if="errorMsg" class="error-alert">
            <AlertTriangleIcon :size="16" style="flex-shrink: 0;" />
            <span>{{ errorMsg }}</span>
          </div>

          <button type="submit" class="btn-primary" :disabled="isLoading">
            <span v-if="isLoading" class="btn-spinner"></span>
            <span>{{ isLoading ? 'جارٍ التحقق...' : 'تسجيل دخول كآدمن' }}</span>
          </button>

          <div class="divider"><span>أو</span></div>

          <button type="button" class="btn-google">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" style="display: block; flex-shrink: 0;">
              <path fill="#4285F4" d="M23.49 12.27c0-.83-.07-1.63-.2-2.4H12v4.54h6.44a5.5 5.5 0 0 1-2.39 3.62v3h3.87c2.26-2.08 3.57-5.14 3.57-8.76z"/>
              <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.87-3c-1.08.72-2.45 1.16-4.06 1.16-3.13 0-5.78-2.11-6.73-4.96H1.29v3.1A11.99 11.99 0 0 0 12 24z"/>
              <path fill="#FBBC05" d="M5.27 14.29a7.19 7.19 0 0 1 0-4.58V6.61H1.29a11.99 11.99 0 0 0 0 10.78l3.98-3.1z"/>
              <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.43-3.43C17.95 1.19 15.24 0 12 0 7.29 0 3.25 2.72 1.29 6.61l3.98 3.1c.95-2.85 3.6-4.96 6.73-4.96z"/>
            </svg>
            <span>تسجيل دخول الآدمن عبر جوجل</span>
          </button>
        </form>
      </div>

      <!-- القسم الأيمن: الترحيب -->
      <div class="login-card__welcome">
        <WafarLogo :dark="true" />

        

        <h2>مرحباً بك في لوحة تحكم الإدارة</h2>
        <p>منصة وفر كاش: إدارة الأسعار، المنتجات، والمتاجر.</p>

        <ul class="login-card__features">
          <li style="display: flex; align-items: center; gap: 8px;">
            <span class="check" style="display: inline-flex; align-items: center; color: var(--wc-green-bright); margin-left: 0;">
              <CheckIcon :size="16" stroke-width="3" />
            </span>
            <span>إدارة المنتجات والأسعار</span>
          </li>
          <li style="display: flex; align-items: center; gap: 8px;">
            <span class="check" style="display: inline-flex; align-items: center; color: var(--wc-green-bright); margin-left: 0;">
              <CheckIcon :size="16" stroke-width="3" />
            </span>
            <span>إدارة المتاجر المسجلة</span>
          </li>
          <li style="display: flex; align-items: center; gap: 8px;">
            <span class="check" style="display: inline-flex; align-items: center; color: var(--wc-green-bright); margin-left: 0;">
              <CheckIcon :size="16" stroke-width="3" />
            </span>
            <span>مراقبة المستخدمين والتحليلات</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>


<script>
import WafarLogo from '../components/WafarLogo.vue'
import apiClient from '../api/axios.js'
import {
  Key as KeyIcon,
  User as UserIcon,
  Lock as LockIcon,
  AlertTriangle as AlertTriangleIcon,
  Check as CheckIcon
} from '@lucide/vue'

export default {
  name: 'AdminLogin',
  components: {
    WafarLogo,
    KeyIcon,
    UserIcon,
    LockIcon,
    AlertTriangleIcon,
    CheckIcon
  },
  data() {
    return {
      username: '',
      password: '',
      rememberMe: false,
      isLoading: false,  // حالة التحميل أثناء الطلب
      errorMsg: ''       // رسالة الخطأ
    }
  },
  methods: {
    /**
     * handleLogin - ترسل بيانات الدخول للـ Backend وتحفظ التوكن
     * الـ endpoint المتوقع: POST /auth/login
     * الـ payload: { username, password }
     * الـ response المتوقع: { token: '...', user: { name, email, ... } }
     */
    async handleLogin() {
      this.errorMsg = ''
      this.isLoading = true

      // // ══════════════════════════════════════════════════════
      // // تجريبي مؤقت — للاختبار قبل تشغيل  Backend
      // // بيانات الدخول التجريبية:
      // //   اسم المستخدم : admin
      // //   كلمة المرور  : admin123
      // // ══════════════════════════════════════════════════════
      // if (this.username === 'admin' && this.password === 'admin123') {
      //   localStorage.setItem('wafar_token', 'demo_token_for_testing')
      //   localStorage.setItem('wafar_user', JSON.stringify({
      //     name: 'محمد الحويطي',
      //     email: 'admin@wafarcash.com',
      //     role: 'admin'
      //   }))
      //   this.$router.push({ name: 'AdminDashboard' })
      //   this.isLoading = false
      //   return
      // }
      // ══════════════════════════════════════════════════════

      try {
        const response = await apiClient.post('/auth/login', {
          username: this.username,
          password: this.password
        })

        // ——— حفظ التوكن ومعلومات المستخدم في الـ localStorage ———
        const { token, user } = response.data
        localStorage.setItem('wafar_token', token)
        localStorage.setItem('wafar_user', JSON.stringify(user))

        // ——— توجيه المسؤول للوحة التحكم ———
        this.$router.push({ name: 'AdminDashboard' })

      } catch (error) {
        // ——— معالجة الأخطاء بشكل احترافي ———
        if (error.response) {
          const status = error.response.status
          if (status === 401 || status === 422) {
            this.errorMsg = 'اسم المستخدم أو كلمة المرور غير صحيحة.'
          } else if (status === 403) {
            this.errorMsg = 'ليس لديك صلاحية الوصول لهذه اللوحة.'
          } else {
            this.errorMsg = 'حدث خطأ في الخادم، يرجى المحاولة لاحقاً.'
          }
        } else {
          this.errorMsg = 'تعذّر الاتصال بالخادم. تحقق من اتصالك بالإنترنت.'
        }
      } finally {
        this.isLoading = false
      }
    }
  }
}
</script>



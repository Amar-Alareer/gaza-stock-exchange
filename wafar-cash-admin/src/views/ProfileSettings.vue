<template>
  <!-- واجهة 3: صفحة إعدادات الملف الشخصي -->
  <div class="profile-page">
    <AdminSidebar />

    <div class="profile-main">
      <AdminHeader @logout="handleLogout" />

      <div class="profile-content">
        <h1 class="profile-content__title">إعدادات الملف الشخصي</h1>

        <!-- صورة الملف الشخصي -->
        <section class="profile-photo-card">
          <img
            class="profile-photo-card__img"
            src="https://api.dicebear.com/7.x/initials/svg?seed=Mohamed&backgroundColor=22a83e"
            alt="صورة الملف الشخصي"
          />
          <div class="profile-photo-card__info">
            <h3>محمد الحويطي</h3>
            <p>حي النفاح - غزة</p>
            <button class="btn-outline">تغيير الصورة</button>
          </div>
        </section>

        <!-- المعلومات الشخصية -->
        <section class="panel">
          <h2 class="panel__title">المعلومات الشخصية</h2>

          <div class="form-grid">
            <div class="form-field">
              <label>الاسم الكامل</label>
              <input type="text" v-model="profile.fullName" />
            </div>
            <div class="form-field">
              <label>البريد الإلكتروني</label>
              <input type="email" v-model="profile.email" />
            </div>
            <div class="form-field">
              <label>رقم الهاتف</label>
              <input type="tel" v-model="profile.phone" />
            </div>
          </div>
        </section>

        <!-- تغيير كلمة المرور -->
        <section class="panel">
          <h2 class="panel__title">تغيير كلمة المرور</h2>

          <div class="form-grid">
            <div class="form-field">
              <label>كلمة المرور الحالية</label>
              <input type="password" v-model="passwordForm.current" placeholder="••••••••" />
            </div>
            <div class="form-field">
              <label>كلمة المرور الجديدة</label>
              <input type="password" v-model="passwordForm.newPassword" placeholder="••••••••" />
            </div>
            <div class="form-field">
              <label>تأكيد كلمة المرور الجديدة</label>
              <input type="password" v-model="passwordForm.confirmPassword" placeholder="••••••••" />
            </div>
          </div>
        </section>

        <button class="btn-primary" @click="saveChanges">حفظ التغييرات</button>

        <!-- رمز الأمان من الباك اند -->
        <section class="panel token-panel">
          <h2 class="panel__title">رمز الأمان من الـ backend</h2>
          <div class="form-field">
            <label>رمز الأمان</label>
            <input type="text" :value="backendToken" readonly class="token-input" />
          </div>
          <p class="token-note">
            هذا الرمز يُقدم من الـ backend لأغراض الأمان ولن يُعرض للمستخدمين.
          </p>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import AdminSidebar from '../components/AdminSidebar.vue'
import AdminHeader from '../components/AdminHeader.vue'

export default {
  name: 'ProfileSettings',
  components: { AdminSidebar, AdminHeader },
  data() {
    return {
      // بيانات المعلومات الشخصية القابلة للتعديل
      profile: {
        fullName: 'محمد الحويطي',
        email: 'mohamed.alhwaiti@example.com',
        phone: '+970 59 000 0000'
      },
      // بيانات نموذج تغيير كلمة المرور
      passwordForm: {
        current: '',
        newPassword: '',
        confirmPassword: ''
      },
      // رمز أمان للقراءة فقط، يوفره الـ backend
      backendToken: 'wc_sk_live_9f3a7c2e1b4d8890abf22c'
    }
  },
  methods: {
    // حفظ التغييرات على المعلومات الشخصية وكلمة المرور
    saveChanges() {
      // في تطبيق حقيقي: يتم استدعاء API لحفظ البيانات
      console.log('تم حفظ التغييرات', this.profile)
    },
    // تسجيل الخروج والعودة إلى صفحة تسجيل الدخول
    handleLogout() {
      this.$router.push('/login')
    }
  }
}
</script>


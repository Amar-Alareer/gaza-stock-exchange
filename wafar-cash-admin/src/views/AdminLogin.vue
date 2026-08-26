<template>
    <!-- صفحة تسجيل دخول المسؤول - بنفس اتجاه وتنسيق صفحة المستخدم العادي -->
    <div class="auth-page-wrapper">
        <div class="auth-card-container">
            <!-- القسم الأيمن في RTL: لوحة الترحيب والمعلومات (الخلفية الخضراء الداكنة) -->
            <div class="auth-panel-welcome">
                <div class="welcome-top">
                    <div class="brand-header">
                        <div class="brand-text">
                            <div class="brand-title-ar">
                                <span class="brand-green">وفر</span>
                                <span class="brand-white">كاش</span>
                            </div>
                            <span class="brand-sub-en">CASH WAFAR</span>
                        </div>
                        <img src="/wafer-cash.svg" alt="وفر كاش" class="brand-logo-img" />
                    </div>

                    <h2 class="welcome-heading">
                        لوحة تحكم المسؤول<br />
                        إدارة شاملة لمنصة وفر كاش
                    </h2>
                    <p class="welcome-description">
                        أدر المنتجات والأسعار، تحكّم في المتاجر المسجلة والمعتمدة، وتابع التحليلات والتقارير الفورية باحترافية وسرعة.
                    </p>
                </div>

                <div class="features-list">
                    <div class="feature-item">
                        <span>إدارة المنتجات وتحديث الأسعار لحظياً</span>
                        <span class="feature-check">
                            <CheckCircle2Icon :size="18" />
                        </span>
                    </div>
                    <div class="feature-item">
                        <span>إدارة المتاجر المسجلة والمقارنات</span>
                        <span class="feature-check">
                            <CheckCircle2Icon :size="18" />
                        </span>
                    </div>
                    <div class="feature-item">
                        <span>مراقبة المستخدمين والتحليلات والتقارير</span>
                        <span class="feature-check">
                            <CheckCircle2Icon :size="18" />
                        </span>
                    </div>
                </div>
            </div>

            <!-- القسم الأيسر في RTL: نموذج تسجيل الدخول (الخلفية الفاتحة) -->
            <div class="auth-panel-form">
                <!-- شارة التبويب -->
                <div class="admin-tab-pill">
                    <ShieldCheckIcon :size="16" />
                    <span>تسجيل دخول المسؤول</span>
                </div>

                <h1 class="form-title">تسجيل الدخول</h1>
                <p class="form-subtitle">أدخل بياناتك للوصول إلى لوحة التحكم الإدارية</p>

                <!-- رسالة الخطأ -->
                <div v-if="errorMsg" class="error-alert">
                    <AlertTriangleIcon :size="18" style="flex-shrink: 0" />
                    <span>{{ errorMsg }}</span>
                </div>

                <form @submit.prevent="handleLogin" class="login-form">
                    <div class="form-group">
                        <label class="field-label" for="username">اسم المستخدم أو البريد الإلكتروني الإداري</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <UserIcon :size="18" />
                            </span>
                            <input
                                id="username"
                                v-model="username"
                                type="text"
                                placeholder="اسم المستخدم أو البريد"
                                autocomplete="username"
                                required
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="label-row">
                            <label class="field-label" for="password">كلمة المرور الإدارية</label>
                            <a href="#" class="forgot-link">نسيت كلمة المرور؟</a>
                        </div>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <LockIcon :size="18" />
                            </span>
                            <input
                                id="password"
                                v-model="password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="••••••••••••"
                                autocomplete="current-password"
                                required
                            />
                            <button
                                type="button"
                                class="password-toggle"
                                @click="showPassword = !showPassword"
                                tabindex="-1"
                                :title="showPassword ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور'"
                            >
                                <EyeOffIcon v-if="showPassword" :size="17" />
                                <EyeIcon v-else :size="17" />
                            </button>
                        </div>
                    </div>

                    <div class="remember-row">
                        <label class="remember-label">
                            <input type="checkbox" v-model="rememberMe" class="remember-checkbox" />
                            <span>تذكرني</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="btn-submit"
                        :disabled="isLoading || isGoogleLoading"
                    >
                        <span v-if="isLoading" class="btn-spinner"></span>
                        <LogInIcon v-else :size="18" />
                        <span>{{ isLoading ? "جارٍ التحقق..." : "تسجيل دخول كمسؤول" }}</span>
                    </button>

                    <div class="form-divider">
                        <span>أو</span>
                    </div>

                    <button
                        type="button"
                        class="btn-google-login"
                        :disabled="isGoogleLoading || isLoading"
                        @click="handleGoogleLogin"
                    >
                        <span v-if="isGoogleLoading" class="btn-spinner btn-spinner--dark"></span>
                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            style="display: block; flex-shrink: 0"
                        >
                            <path
                                fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            />
                            <path
                                fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            />
                            <path
                                fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"
                            />
                            <path
                                fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"
                            />
                        </svg>
                        <span>{{ isGoogleLoading ? "جارٍ التحقق..." : "تسجيل دخول الآدمن عبر جوجل" }}</span>
                    </button>

                    <!-- رابط العودة للموقع كمستخدم عادي -->
                    <div class="user-switch-footer">
                        <small class="switch-hint">لست مسؤولاً؟</small>
                        <br />
                        <a href="http://127.0.0.1:8000/login" class="switch-link">
                            <span>🏠</span>
                            الدخول كمستخدم عادي
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import apiClient from "../api/axios.js";
import {
    User as UserIcon,
    Lock as LockIcon,
    AlertTriangle as AlertTriangleIcon,
    ShieldCheck as ShieldCheckIcon,
    CheckCircle2 as CheckCircle2Icon,
    LogIn as LogInIcon,
    Eye as EyeIcon,
    EyeOff as EyeOffIcon,
} from "@lucide/vue";

// معرّف تطبيق Google من الـ environment
const GOOGLE_CLIENT_ID = import.meta.env.VITE_GOOGLE_CLIENT_ID || "";

export default {
    name: "AdminLogin",
    components: {
        UserIcon,
        LockIcon,
        AlertTriangleIcon,
        ShieldCheckIcon,
        CheckCircle2Icon,
        LogInIcon,
        EyeIcon,
        EyeOffIcon,
    },
    data() {
        return {
            username: "",
            password: "",
            showPassword: false,
            rememberMe: true,
            isLoading: false,       // حالة تحميل تسجيل الدخول العادي
            isGoogleLoading: false, // حالة تحميل تسجيل الدخول بـ Google
            errorMsg: "",           // رسالة الخطأ
        };
    },
    mounted() {
        // تحميل Google Identity Services SDK
        this.loadGoogleSDK();
    },
    methods: {
        /**
         * loadGoogleSDK - تحميل مكتبة Google Identity Services
         */
        loadGoogleSDK() {
            if (document.getElementById("google-gsi-script")) return;

            const script = document.createElement("script");
            script.id = "google-gsi-script";
            script.src = "https://accounts.google.com/gsi/client";
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        },

        /**
         * handleGoogleLogin - يفتح نافذة Google OAuth ويرسل الـ token للـ Backend
         */
        handleGoogleLogin() {
            this.errorMsg = "";

            if (!GOOGLE_CLIENT_ID) {
                this.errorMsg = "معرّف Google غير مضبوط. تحقق من إعدادات VITE_GOOGLE_CLIENT_ID.";
                return;
            }

            if (!window.google || !window.google.accounts) {
                this.errorMsg = "لم يتم تحميل مكتبة Google بعد. انتظر لحظة وأعد المحاولة.";
                return;
            }

            this.isGoogleLoading = true;

            try {
                // استخدام Google OAuth2 Implicit Flow للحصول على access_token
                const client = window.google.accounts.oauth2.initTokenClient({
                    client_id: GOOGLE_CLIENT_ID,
                    scope: "email profile openid",
                    callback: async (tokenResponse) => {
                        if (tokenResponse.error) {
                            this.isGoogleLoading = false;
                            this.errorMsg = "تم إلغاء تسجيل الدخول عبر Google.";
                            return;
                        }
                        await this.sendGoogleTokenToBackend(tokenResponse.access_token);
                    },
                });

                client.requestAccessToken({ prompt: "select_account" });
            } catch (err) {
                this.isGoogleLoading = false;
                this.errorMsg = "حدث خطأ أثناء فتح نافذة Google: " + err.message;
            }
        },

        /**
         * sendGoogleTokenToBackend - يرسل الـ access_token لـ API ويحفظ النتيجة
         */
        async sendGoogleTokenToBackend(accessToken) {
            try {
                const response = await apiClient.post("/auth/google", {
                    google_token: accessToken,
                });

                const { token, user } = response.data;

                // حفظ التوكن ومعلومات المستخدم
                localStorage.setItem("wafar_token", token);
                localStorage.setItem("wafar_user", JSON.stringify(user));

                // توجيه للوحة التحكم
                this.$router.push({ name: "AdminDashboard" });
            } catch (error) {
                if (error.response) {
                    const status = error.response.status;
                    const message = error.response.data?.message;

                    if (status === 403) {
                        this.errorMsg = message || "هذا الحساب ليس لديه صلاحيات المسؤول.";
                    } else if (status === 401) {
                        this.errorMsg = "رمز Google غير صالح أو منتهي الصلاحية. حاول مجدداً.";
                    } else {
                        this.errorMsg = message || "حدث خطأ في الخادم. حاول مجدداً.";
                    }
                } else {
                    this.errorMsg = "تعذّر الاتصال بالخادم. تحقق من اتصالك بالإنترنت.";
                }
            } finally {
                this.isGoogleLoading = false;
            }
        },

        /**
         * handleLogin - ترسل بيانات الدخول للـ Backend وتحفظ التوكن
         */
        async handleLogin() {
            this.errorMsg = "";
            this.isLoading = true;

            try {
                const response = await apiClient.post("/auth/login", {
                    username: this.username,
                    password: this.password,
                });

                const { token, user } = response.data;

                // التحقق من أن الحساب يملك دور مسؤول (Admin)
                if (user && user.role !== "admin") {
                    this.errorMsg = "عذراً، هذا الحساب عميل (Client) وليس لديه صلاحيات المسؤول (Admin) للوصول للوحة التحكم.";
                    return;
                }

                localStorage.setItem("wafar_token", token);
                localStorage.setItem("wafar_user", JSON.stringify(user));

                // توجيه المسؤول للوحة التحكم
                this.$router.push({ name: "AdminDashboard" });
            } catch (error) {
                if (error.response) {
                    const status = error.response.status;
                    if (status === 401 || status === 422) {
                        this.errorMsg = "اسم المستخدم أو كلمة المرور غير صحيحة.";
                    } else if (status === 403) {
                        this.errorMsg = "ليس لديك صلاحية الوصول لهذه اللوحة.";
                    } else {
                        this.errorMsg = "حدث خطأ في الخادم، يرجى المحاولة لاحقاً.";
                    }
                } else {
                    this.errorMsg = "تعذّر الاتصال بالخادم. تحقق من اتصالك بالإنترنت.";
                }
            } finally {
                this.isLoading = false;
            }
        },
    },
};
</script>

<style scoped>
/* الغلاف الكلي للصفحة */
.auth-page-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at 20% 20%, #102d1b 0%, #0a1f12 60%, #061309 100%);
    padding: 24px 16px;
    direction: rtl;
    font-family: 'Tajawal', sans-serif;
}

/* بطاقة تسجيل الدخول الرئيسية */
.auth-card-container {
    display: flex;
    width: 100%;
    max-width: 1000px;
    min-height: 580px;
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
    background: #ffffff;
}

/* ========================================================
   القسم الأيمن في RTL (لوحة الترحيب بالخلفية الخضراء الداكنة)
   ======================================================== */
.auth-panel-welcome {
    flex: 1;
    background: linear-gradient(160deg, #0d2a19 0%, #07190e 100%);
    color: #ffffff;
    padding: 48px 44px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: right;
    border-left: 1px solid rgba(36, 223, 100, 0.12);
}

.brand-header {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 12px;
    margin-bottom: 28px;
}

.brand-logo-img {
    width: 44px;
    height: 44px;
    object-fit: contain;
}

.brand-text {
    text-align: right;
    line-height: 1.15;
}

.brand-title-ar {
    font-size: 1.45rem;
    font-weight: 900;
}

.brand-green {
    color: #24df64;
}

.brand-white {
    color: #ffffff;
    margin-right: 2px;
}

.brand-sub-en {
    color: #24df64;
    font-size: 0.6rem;
    letter-spacing: 2px;
    font-weight: 800;
    display: block;
    margin-top: 2px;
}

.welcome-heading {
    color: #ffffff;
    font-weight: 900;
    font-size: 1.65rem;
    line-height: 1.45;
    margin: 0 0 14px 0;
}

.welcome-description {
    color: #d1e7dd;
    opacity: 0.85;
    font-size: 0.92rem;
    line-height: 1.7;
    margin: 0 0 24px 0;
}

.features-list {
    display: flex;
    flex-direction: column;
    margin-top: auto;
}

.feature-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 0;
    font-size: 0.92rem;
    font-weight: 600;
    color: #f1f5f9;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.feature-item:last-child {
    border-bottom: none;
}

.feature-check {
    color: #24df64;
    display: inline-flex;
    align-items: center;
}

/* ========================================================
   القسم الأيسر في RTL (نموذج تسجيل الدخول بالخلفية الفاتحة)
   ======================================================== */
.auth-panel-form {
    flex: 1.1;
    background: #f8fafc;
    padding: 48px 44px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.admin-tab-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #0d2116;
    color: #24df64;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 800;
    margin-bottom: 20px;
    align-self: flex-start;
    border: 1px solid rgba(36, 223, 100, 0.25);
}

.form-title {
    font-size: 1.75rem;
    font-weight: 900;
    color: #0f172a;
    margin: 0 0 6px 0;
}

.form-subtitle {
    color: #64748b;
    font-size: 0.9rem;
    margin: 0 0 24px 0;
}

.error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 18px;
}

.login-form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 16px;
}

.field-label {
    display: block;
    font-size: 0.82rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 7px;
}

.label-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 7px;
}

.forgot-link {
    color: #10b981;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.15s ease;
}

.forgot-link:hover {
    color: #059669;
    text-decoration: underline;
}

.input-wrapper {
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 0 14px;
    transition: all 0.2s ease;
}

.input-wrapper:focus-within {
    border-color: #10b981;
    box-shadow: 0 0 0 3.5px rgba(16, 185, 129, 0.12);
}

.input-icon {
    color: #94a3b8;
    display: flex;
    align-items: center;
    margin-left: 10px;
}

.input-wrapper input {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    padding: 12px 0;
    font-size: 0.92rem;
    color: #0f172a;
    font-family: inherit;
}

.input-wrapper input::placeholder {
    color: #94a3b8;
}

.password-toggle {
    background: transparent;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    transition: color 0.15s ease;
}

.password-toggle:hover {
    color: #475569;
}

.remember-row {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin: 4px 0 20px 0;
}

.remember-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.84rem;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    user-select: none;
}

.remember-checkbox {
    width: 16px;
    height: 16px;
    accent-color: #10b981;
    cursor: pointer;
}

/* زر تسجيل الدخول الأساسي */
.btn-submit {
    width: 100%;
    background: #24df64;
    color: #0d2116;
    border: none;
    border-radius: 28px;
    padding: 12px 20px;
    font-size: 1rem;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(36, 223, 100, 0.35);
    transition: all 0.2s ease;
    font-family: inherit;
}

.btn-submit:hover:not(:disabled) {
    background: #1fcf5b;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(36, 223, 100, 0.45);
}

.btn-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* الفاصل */
.form-divider {
    position: relative;
    text-align: center;
    margin: 20px 0;
}

.form-divider::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e2e8f0;
}

.form-divider span {
    position: relative;
    background: #f8fafc;
    padding: 0 12px;
    color: #94a3b8;
    font-size: 0.82rem;
    font-weight: 700;
}

/* زر جوجل */
.btn-google-login {
    width: 100%;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 11px 16px;
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
    transition: all 0.2s ease;
    font-family: inherit;
}

.btn-google-login:hover:not(:disabled) {
    background: #f8fafc;
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}

.btn-google-login:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* التذييل للتبديل للمستخدم العادي */
.user-switch-footer {
    text-align: center;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
}

.switch-hint {
    color: #94a3b8;
    font-size: 0.8rem;
}

.switch-link {
    color: #10b981;
    font-size: 0.85rem;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
    transition: color 0.15s ease;
}

.switch-link:hover {
    color: #059669;
    text-decoration: underline;
}

/* Spinner */
.btn-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2.5px solid rgba(13, 33, 22, 0.25);
    border-top-color: #0d2116;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

.btn-spinner--dark {
    border-color: rgba(60, 60, 60, 0.25);
    border-top-color: #3c3c3c;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* ========================================================
   التجاوب مع الشاشات الصغيرة (Responsive)
   ======================================================== */
@media (max-width: 820px) {
    .auth-card-container {
        flex-direction: column;
        border-radius: 20px;
    }

    .auth-panel-welcome {
        padding: 32px 24px;
        border-left: none;
        border-bottom: 1px solid rgba(36, 223, 100, 0.15);
    }

    .welcome-heading {
        font-size: 1.35rem;
    }

    .auth-panel-form {
        padding: 32px 24px;
    }
}
</style>

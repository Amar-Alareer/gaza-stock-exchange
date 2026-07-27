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
                        :src="
                            profile.profile_picture_url ||
                            'https://api.dicebear.com/7.x/initials/svg?seed=User&backgroundColor=22a83e'
                        "
                        alt="صورة الملف الشخصي"
                    />
                    <div class="profile-photo-card__info">
                        <h3>{{ profile.name || "اسم المستخدم" }}</h3>
                        <p>{{ profile.address || "العنوان غير محدد" }}</p>
                        <input
                            type="file"
                            ref="fileInput"
                            @change="onFileChange"
                            style="display: none"
                            accept="image/*"
                        />
                        <button
                            class="btn-outline"
                            @click="$refs.fileInput.click()"
                        >
                            تغيير الصورة
                        </button>
                    </div>
                </section>

                <!-- المعلومات الشخصية -->
                <section class="panel">
                    <h2 class="panel__title">المعلومات الشخصية</h2>

                    <div class="form-grid">
                        <div class="form-field">
                            <label>الاسم الكامل</label>
                            <input type="text" v-model="profile.name" />
                        </div>
                        <div class="form-field">
                            <label>اسم المستخدم (username)</label>
                            <input
                                type="text"
                                v-model="profile.username"
                                dir="ltr"
                            />
                        </div>
                        <div class="form-field">
                            <label>البريد الإلكتروني</label>
                            <input
                                type="email"
                                v-model="profile.email"
                                dir="ltr"
                            />
                        </div>
                        <div class="form-field">
                            <label>رقم الهاتف</label>
                            <input
                                type="tel"
                                v-model="profile.phone"
                                dir="ltr"
                            />
                        </div>
                        <div class="form-field" style="grid-column: 1 / -1">
                            <label>العنوان</label>
                            <input type="text" v-model="profile.address" />
                        </div>
                    </div>
                </section>

                <!-- تغيير كلمة المرور -->
                <section class="panel">
                    <h2 class="panel__title">تغيير كلمة المرور</h2>

                    <div class="form-grid">
                        <div class="form-field">
                            <label>كلمة المرور الحالية</label>
                            <input
                                type="password"
                                v-model="passwordForm.current"
                                placeholder="••••••••"
                            />
                        </div>
                        <div class="form-field">
                            <label>كلمة المرور الجديدة</label>
                            <input
                                type="password"
                                v-model="passwordForm.newPassword"
                                placeholder="••••••••"
                            />
                        </div>
                        <div class="form-field">
                            <label>تأكيد كلمة المرور الجديدة</label>
                            <input
                                type="password"
                                v-model="passwordForm.confirmPassword"
                                placeholder="••••••••"
                            />
                        </div>
                    </div>
                </section>

                <div
                    v-if="successMsg"
                    class="alert-success"
                    style="
                        padding: 15px;
                        background: #d4edda;
                        color: #155724;
                        border-radius: 8px;
                        margin-bottom: 20px;
                    "
                >
                    {{ successMsg }}
                </div>
                <div
                    v-if="errorMsg"
                    class="alert-danger"
                    style="
                        padding: 15px;
                        background: #f8d7da;
                        color: #721c24;
                        border-radius: 8px;
                        margin-bottom: 20px;
                    "
                >
                    {{ errorMsg }}
                </div>

                <button
                    class="btn-primary"
                    @click="saveChanges"
                    :disabled="isSaving"
                >
                    {{ isSaving ? "جارٍ الحفظ..." : "حفظ التغييرات" }}
                </button>

                <!-- رمز الأمان من الباك اند -->
                <section class="panel token-panel">
                    <h2 class="panel__title">رمز الأمان من الـ backend</h2>
                    <div class="form-field">
                        <label>رمز الأمان</label>
                        <input
                            type="text"
                            :value="backendToken"
                            readonly
                            class="token-input"
                        />
                    </div>
                    <p class="token-note">
                        هذا الرمز يُقدم من الـ backend لأغراض الأمان ولن يُعرض
                        للمستخدمين.
                    </p>
                </section>
            </div>
        </div>
    </div>
</template>

<script>
import AdminSidebar from "../components/AdminSidebar.vue";
import AdminHeader from "../components/AdminHeader.vue";
import apiClient from "../api/axios.js";
import { globalState } from "../state.js";

export default {
    name: "ProfileSettings",
    components: { AdminSidebar, AdminHeader },
    data() {
        return {
            profile: {
                name: "",
                username: "",
                email: "",
                phone: "",
                address: "",
                profile_picture_url: "",
            },
            passwordForm: {
                current: "",
                newPassword: "",
                confirmPassword: "",
            },
            imageFile: null,
            backendToken: localStorage.getItem("wafar_token") || "غير متاح",
            isSaving: false,
            successMsg: "",
            errorMsg: "",
        };
    },
    mounted() {
        this.fetchProfile();
    },
    methods: {
        async fetchProfile() {
            try {
                const res = await apiClient.get("/admin/profile");
                if (res.data.status === "success") {
                    this.profile = res.data.user;
                    // تحديث الحالة العامة للهيدر
                    globalState.currentUser = res.data.user;
                }
            } catch (err) {
                console.error("فشل في جلب البيانات", err);
            }
        },
        onFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.imageFile = file;
                // استعراض محلي
                this.profile.profile_picture_url = URL.createObjectURL(file);
            }
        },
        async saveChanges() {
            this.successMsg = "";
            this.errorMsg = "";

            if (this.passwordForm.newPassword) {
                if (
                    this.passwordForm.newPassword !==
                    this.passwordForm.confirmPassword
                ) {
                    this.errorMsg = "كلمة المرور الجديدة غير متطابقة.";
                    return;
                }
            }

            this.isSaving = true;
            try {
                const formData = new FormData();
                formData.append("name", this.profile.name);
                if (this.profile.username)
                    formData.append("username", this.profile.username);
                formData.append("email", this.profile.email);
                if (this.profile.phone)
                    formData.append("phone", this.profile.phone);
                if (this.profile.address)
                    formData.append("address", this.profile.address);

                if (this.imageFile) {
                    formData.append("profile_picture", this.imageFile);
                }

                if (this.passwordForm.newPassword) {
                    formData.append(
                        "current_password",
                        this.passwordForm.current,
                    );
                    formData.append(
                        "new_password",
                        this.passwordForm.newPassword,
                    );
                }

                const res = await apiClient.post("/admin/profile", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                if (res.data.status === "success") {
                    this.successMsg = res.data.message;
                    this.profile = res.data.user;
                    globalState.currentUser = res.data.user;

                    // تفريغ حقول كلمة المرور
                    this.passwordForm = {
                        current: "",
                        newPassword: "",
                        confirmPassword: "",
                    };
                    this.imageFile = null;
                }
            } catch (err) {
                if (
                    err.response &&
                    err.response.data &&
                    err.response.data.message
                ) {
                    this.errorMsg = err.response.data.message;
                } else {
                    this.errorMsg = "حدث خطأ أثناء الحفظ.";
                }
            } finally {
                this.isSaving = false;
            }
        },
        handleLogout() {
            this.$router.push("/login");
        },
    },
};
</script>

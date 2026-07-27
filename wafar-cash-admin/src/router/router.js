/**
 * router.js - إعدادات التنقل الكاملة لمنصة وفر كاش
 * ---------------------------------------------------
 * يشمل:
 *  - تعريف 4 مسارات: اللوجن، الداشبورد، المقالات، الإعدادات
 *  - Navigation Guard: يحمي الصفحات المُقيَّدة بالتوكن
 *  - إعادة التوجيه التلقائي للوجن إذا دخل المستخدم بدون توكن
 *  - منع الوصول لصفحة اللوجن إذا كان المستخدم مُسجّل دخول مسبقاً
 */

import { createRouter, createWebHistory } from "vue-router";
import AdminLogin from "../views/AdminLogin.vue";
import AdminDashboard from "../views/AdminDashboard.vue";
import AdminArticles from "../views/AdminArticles.vue";
import AdminStores from "../views/AdminStores.vue";
import ProfileSettings from "../views/ProfileSettings.vue";

const routes = [
    // ——— إعادة توجيه الصفحة الرئيسية ———
    {
        path: "/",
        redirect: "/login",
    },

    // ——— صفحة 1: تسجيل الدخول ———
    {
        path: "/login",
        name: "AdminLogin",
        component: AdminLogin,
        meta: {
            title: "تسجيل دخول المسؤول",
            requiresAuth: false, // لا تحتاج توكن
        },
    },

    // ——— صفحة 2: لوحة التحكم الرئيسية ———
    {
        path: "/dashboard",
        name: "AdminDashboard",
        component: AdminDashboard,
        meta: {
            title: "لوحة التحكم",
            requiresAuth: true, // 🔒 تحتاج توكن
        },
    },

    // ——— صفحة 3: إدارة المقالات (CRUD) ———
    {
        path: "/articles",
        name: "AdminArticles",
        component: AdminArticles,
        meta: {
            title: "إدارة المقالات",
            requiresAuth: true, // 🔒 تحتاج توكن
        },
    },

    // ——— صفحة 4: إدارة المتاجر ———
    {
        path: "/stores",
        name: "AdminStores",
        component: AdminStores,
        meta: {
            title: "إدارة المتاجر",
            requiresAuth: true, // 🔒 تحتاج توكن
        },
    },
    // ——— صفحة تفاصيل المتجر ———
    {
        path: "/stores/:id",
        name: "StoreDetails",
        component: () => import("../views/StoreDetails.vue"),
        meta: {
            title: "تفاصيل المتجر",
            requiresAuth: true,
        },
    },

    // ——— صفحة الإعدادات الشخصية ———
    {
        path: "/profile",
        name: "ProfileSettings",
        component: ProfileSettings,
        meta: {
            title: "إعدادات الملف الشخصي",
            requiresAuth: true, // 🔒 تحتاج توكن
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// ——— Navigation Guard: جدار الحماية ———
router.beforeEach((to, from, next) => {
    // تحديث عنوان الصفحة
    if (to.meta && to.meta.title) {
        document.title = `وفر كاش | ${to.meta.title}`;
    }

    const token = localStorage.getItem("wafar_token");

    if (to.meta.requiresAuth && !token) {
        // ❌ الصفحة مُقيَّدة والتوكن غير موجود → اللوجن
        next({ name: "AdminLogin" });
    } else if (to.name === "AdminLogin" && token) {
        // ✅ المستخدم مسجّل دخول ويحاول فتح اللوجن → الداشبورد
        next({ name: "AdminDashboard" });
    } else {
        // ✅ السماح بالمرور
        next();
    }
});

export default router;

<template>
    <header class="admin-header">
        <!-- أقصى اليمين: زر التبديل للموبايل + البحث الشامل -->
        <div class="admin-header__start">
            <button class="mobile-menu-toggle" @click="toggleSidebar">
                <MenuIcon :size="20" />
            </button>

            <!-- صندوق البحث -->
            <div
                class="search-container"
                style="position: relative; flex: 1; max-width: 480px"
            >
                <input
                    v-model="searchQuery"
                    @input="onSearchInput"
                    @focus="showDropdown = true"
                    type="text"
                    placeholder="ابحث باسم المنتج، المتجر، أو المقالات..."
                    class="search-input"
                    style="
                        width: 100%;
                        padding: 10px 42px 10px 14px;
                        border-radius: 10px;
                        border: 1px solid var(--wc-border);
                        outline: none;
                        background: var(--wc-gray-bg);
                        font-size: 13.5px;
                    "
                />
                <span
                    style="
                        position: absolute;
                        right: 14px;
                        top: 50%;
                        transform: translateY(-50%);
                        color: #888;
                        font-size: 14px;
                    "
                    >🔍</span
                >

                <div
                    v-if="searchQuery.length >= 2 && showDropdown"
                    class="search-results-dropdown"
                    style="
                        position: absolute;
                        top: 100%;
                        left: 0;
                        right: 0;
                        background: white;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                        z-index: 9999;
                        max-height: 350px;
                        overflow-y: auto;
                        margin-top: 5px;
                        text-align: right;
                    "
                >
                    <div
                        v-if="isSearching"
                        style="padding: 15px; text-align: center; color: #888"
                    >
                        جاري البحث عن "{{ searchQuery }}"...
                    </div>

                    <div
                        v-else-if="searchResults.length === 0"
                        style="padding: 15px; text-align: center; color: #888"
                    >
                        لا توجد نتائج مطابقة لـ "{{ searchQuery }}"
                    </div>

                    <div v-else>
                        <div
                            v-for="(result, index) in searchResults"
                            :key="index"
                            @click="goToResult(result.link)"
                            style="
                                padding: 12px 15px;
                                border-bottom: 1px solid #f0f0f0;
                                cursor: pointer;
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                            "
                            onmouseover="this.style.backgroundColor = '#f9f9f9'"
                            onmouseout="this.style.backgroundColor = 'white'"
                        >
                            <div>
                                <strong
                                    style="
                                        display: block;
                                        color: #333;
                                        font-size: 14px;
                                    "
                                    >{{ result.title }}</strong
                                >
                                <small style="color: #888; font-size: 11px">{{
                                    result.subtitle
                                }}</small>
                            </div>
                            <span
                                style="
                                    background: #e8f5ea;
                                    color: #10b981;
                                    padding: 3px 8px;
                                    border-radius: 4px;
                                    font-size: 11px;
                                    font-weight: bold;
                                "
                            >
                                {{ result.type }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- أقصى اليسار (شمال الصفحة): الإشعارات + صورة الحساب واسمه + تسجيل الخروج -->
        <div class="admin-header__left-controls" style="display: flex; align-items: center; gap: 14px; margin-right: auto;">
            <!-- زر الإشعارات والنافذة المنسدلة -->
            <div class="notifications-wrapper" style="position: relative;" ref="notifContainer">
                <button
                    class="header-notification-btn"
                    @click.stop="toggleNotifications"
                    title="الإشعارات والتحديثات"
                    :class="{ 'btn-active': showNotifications }"
                    style="position: relative; background: var(--wc-gray-bg); border: 1px solid var(--wc-border); width: 40px; height: 40px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--wc-text-dark); transition: all 0.2s ease;"
                >
                    <BellIcon :size="19" />
                    <!-- شارة عدد الإشعارات غير المقروءة -->
                    <span
                        v-if="unreadCount > 0"
                        class="notification-badge"
                        style="position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; font-size: 11px; font-weight: 800; min-width: 18px; height: 18px; border-radius: 9px; padding: 0 4px; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(239, 68, 68, 0.4); animation: pulse-badge 2s infinite;"
                    >
                        {{ unreadCount > 99 ? '99+' : unreadCount }}
                    </span>
                </button>

                <!-- النافذة المنسدلة للإشعارات -->
                <div
                    v-if="showNotifications"
                    class="notifications-dropdown"
                    style="position: absolute; top: 48px; left: 0; width: 360px; max-width: 90vw; background: #ffffff; border-radius: 14px; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.1); z-index: 99999; overflow: hidden; font-family: inherit; direction: rtl; text-align: right;"
                >
                    <!-- ترويسة قائمة الإشعارات -->
                    <div style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; background: #fafafa;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-weight: 800; font-size: 15px; color: #111827;">الإشعارات والتحديثات</span>
                            <span v-if="unreadCount > 0" style="background: #10b981; color: white; font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 10px;">
                                {{ unreadCount }} جديد
                            </span>
                        </div>
                        <button
                            v-if="unreadCount > 0"
                            @click="markAllAsRead"
                            style="background: transparent; border: none; color: #10b981; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 4px; padding: 4px 6px; border-radius: 6px;"
                            onmouseover="this.style.background='#ecfdf5'"
                            onmouseout="this.style.background='transparent'"
                        >
                            <CheckCheckIcon :size="14" />
                            <span>تعيين الكل كـ مقروء</span>
                        </button>
                    </div>

                    <!-- تبويبات الفلترة -->
                    <div style="display: flex; border-bottom: 1px solid #f3f4f6; padding: 6px 12px; background: #fff; gap: 6px;">
                        <button
                            @click="activeFilter = 'all'"
                            :style="{
                                padding: '5px 12px',
                                borderRadius: '8px',
                                border: 'none',
                                fontSize: '12px',
                                fontWeight: '700',
                                cursor: 'pointer',
                                background: activeFilter === 'all' ? '#10b981' : '#f3f4f6',
                                color: activeFilter === 'all' ? '#ffffff' : '#4b5563'
                            }"
                        >
                            الكل ({{ notificationsList.length }})
                        </button>
                        <button
                            @click="activeFilter = 'unread'"
                            :style="{
                                padding: '5px 12px',
                                borderRadius: '8px',
                                border: 'none',
                                fontSize: '12px',
                                fontWeight: '700',
                                cursor: 'pointer',
                                background: activeFilter === 'unread' ? '#10b981' : '#f3f4f6',
                                color: activeFilter === 'unread' ? '#ffffff' : '#4b5563'
                            }"
                        >
                            غير مقروء ({{ unreadCount }})
                        </button>
                    </div>

                    <!-- قائمة الإشعارات -->
                    <div style="max-height: 380px; overflow-y: auto;">
                        <div v-if="isLoadingNotifications && notificationsList.length === 0" style="padding: 30px 15px; text-align: center; color: #6b7280; font-size: 13px;">
                            <RotateCwIcon :size="20" class="spin-icon" style="margin-bottom: 8px; color: #10b981;" />
                            <div>جاري جلب الإشعارات...</div>
                        </div>

                        <div v-else-if="filteredNotifications.length === 0" style="padding: 35px 20px; text-align: center; color: #9ca3af;">
                            <BellIcon :size="32" style="margin-bottom: 8px; opacity: 0.4;" />
                            <div style="font-size: 13px; font-weight: 600; color: #4b5563;">لا توجد إشعارات حالياً</div>
                            <small style="font-size: 11px; color: #9ca3af;">جميع التحديثات تظهر هنا تلقائياً</small>
                        </div>

                        <div v-else>
                            <div
                                v-for="notif in filteredNotifications"
                                :key="notif.id"
                                @click="handleNotificationClick(notif)"
                                :style="{
                                    padding: '12px 14px',
                                    borderBottom: '1px solid #f3f4f6',
                                    cursor: 'pointer',
                                    display: 'flex',
                                    gap: '12px',
                                    alignItems: 'flex-start',
                                    background: notif.is_read ? '#ffffff' : '#f0fdf4',
                                    transition: 'background 0.15s ease'
                                }"
                                onmouseover="this.style.background = '#f9fafb'"
                                :onmouseout="`this.style.background = '${notif.is_read ? '#ffffff' : '#f0fdf4'}'`"
                            >
                                <!-- أيقونة الإشعار بحسب النوع -->
                                <div
                                    :style="{
                                        width: '36px',
                                        height: '36px',
                                        borderRadius: '10px',
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        flexShrink: '0',
                                        background: getNotifStyle(notif.type).bg,
                                        color: getNotifStyle(notif.type).color
                                    }"
                                >
                                    <component :is="getNotifStyle(notif.type).icon" :size="18" />
                                </div>

                                <!-- محتوى الإشعار -->
                                <div style="flex: 1; min-width: 0;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px;">
                                        <strong style="font-size: 13px; font-weight: 700; color: #1f2937;">{{ notif.title }}</strong>
                                        <span style="font-size: 10.5px; color: #9ca3af; white-space: nowrap;">{{ notif.time_ago }}</span>
                                    </div>
                                    <p style="font-size: 12px; color: #4b5563; margin: 0 0 4px 0; line-height: 1.4; word-break: break-word;">
                                        {{ notif.message }}
                                    </p>
                                </div>

                                <!-- نقطة الحالة غير المقروءة -->
                                <span v-if="!notif.is_read" style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; flex-shrink: 0; margin-top: 6px;"></span>
                            </div>
                        </div>
                    </div>

                    <!-- تذييل قائمة الإشعارات -->
                    <div style="padding: 10px 14px; background: #fafafa; border-top: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                        <button
                            @click="fetchNotifications"
                            style="background: transparent; border: none; color: #6b7280; font-size: 11.5px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 4px;"
                        >
                            <RotateCwIcon :size="12" :class="{ 'spin-icon': isLoadingNotifications }" />
                            <span>تحديث الآن</span>
                        </button>
                        <span style="font-size: 11px; color: #10b981; font-weight: 600;">تحديث تلقائي مفعّل 🟢</span>
                    </div>
                </div>
            </div>

            <!-- صورة الحساب واسم صاحب الحساب -->
            <router-link to="/profile" class="admin-header__profile" title="الملف الشخصي" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                <img
                    class="admin-header__avatar"
                    :src="
                        currentUser?.profile_picture_url ||
                        'https://api.dicebear.com/7.x/initials/svg?seed=User&backgroundColor=17692e'
                    "
                    alt="صورة المستخدم"
                    style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--wc-green-bright);"
                />
                <div class="admin-header__profile-info" style="display: flex; flex-direction: column;">
                    <span class="admin-header__profile-name" style="font-weight: 700; font-size: 14px; color: var(--wc-text-dark); white-space: nowrap;">
                        {{ currentUser?.name || "مستخدم" }}
                    </span>
                </div>
            </router-link>

            <!-- زر الرجوع للموقع الرئيسي -->
            <a
                href="http://127.0.0.1:8000"
                target="_blank"
                title="الموقع الرئيسي"
                style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border: 1px solid var(--wc-border); border-radius: 8px; background: var(--wc-gray-bg); color: #10b981; font-weight: 700; font-size: 13px; text-decoration: none; transition: all 0.2s;"
            >
                <HomeIcon :size="15" />
                <span>الموقع الرئيسي</span>
            </a>

            <!-- زر تسجيل الخروج -->
            <button
                class="admin-header__logout"
                @click="$emit('logout')"
                title="تسجيل الخروج"
                style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border: 1px solid var(--wc-border); border-radius: 8px; background: var(--wc-gray-bg); color: var(--wc-danger); font-weight: 700; font-size: 13px;"
            >
                <LogOutIcon :size="15" />
                <span>خروج</span>
            </button>
        </div>
    </header>
</template>

<script>
import apiClient from "../api/axios.js";
import {
    LogOut as LogOutIcon,
    Menu as MenuIcon,
    Bell as BellIcon,
    Store as StoreIcon,
    Package as ProductIcon,
    FileText as ArticleIcon,
    AlertCircle as ComplaintIcon,
    CheckCheck as CheckCheckIcon,
    RotateCw as RotateCwIcon,
    Home as HomeIcon,
} from "@lucide/vue";
import { globalState } from "../state.js";

export default {
    name: "AdminHeader",
    components: {
        LogOutIcon,
        MenuIcon,
        BellIcon,
        StoreIcon,
        ProductIcon,
        ArticleIcon,
        ComplaintIcon,
        CheckCheckIcon,
        RotateCwIcon,
        HomeIcon,
    },
    data() {
        return {
            searchQuery: "",
            searchResults: [],
            isSearching: false,
            showDropdown: false,
            searchTimeout: null,
            // بيانات الإشعارات
            showNotifications: false,
            activeFilter: "all",
            isLoadingNotifications: false,
            notificationsList: [],
            unreadCount: 0,
            pollingTimer: null,
            readIds: JSON.parse(localStorage.getItem("wafar_read_notifs") || "[]"),
        };
    },
    computed: {
        currentUser() {
            return globalState.currentUser;
        },
        filteredNotifications() {
            if (this.activeFilter === "unread") {
                return this.notificationsList.filter((n) => !n.is_read);
            }
            return this.notificationsList;
        },
        refreshCounter() {
            return globalState.notificationRefreshCounter;
        },
    },
    watch: {
        refreshCounter() {
            this.fetchNotifications();
        },
    },
    methods: {
        toggleSidebar() {
            globalState.isMobileSidebarOpen = !globalState.isMobileSidebarOpen;
        },
        toggleNotifications() {
            this.showNotifications = !this.showNotifications;
            if (this.showNotifications) {
                this.fetchNotifications();
            }
        },
        async fetchNotifications() {
            this.isLoadingNotifications = true;
            try {
                const response = await apiClient.get("/admin/notifications", {
                    params: { read_ids: this.readIds.join(",") },
                });
                if (response.data && response.data.status === "success") {
                    this.notificationsList = response.data.notifications;
                    this.unreadCount = response.data.unread_count;
                    globalState.notifications = this.notificationsList;
                    globalState.unreadCount = this.unreadCount;
                }
            } catch (error) {
                console.error("خطأ أثناء جلب الإشعارات:", error);
            } finally {
                this.isLoadingNotifications = false;
            }
        },
        markAllAsRead() {
            const allIds = this.notificationsList.map((n) => n.id);
            this.readIds = Array.from(new Set([...this.readIds, ...allIds]));
            localStorage.setItem("wafar_read_notifs", JSON.stringify(this.readIds));

            this.notificationsList = this.notificationsList.map((n) => ({
                ...n,
                is_read: true,
            }));
            this.unreadCount = 0;
            globalState.unreadCount = 0;
        },
        handleNotificationClick(notif) {
            if (!notif.is_read) {
                if (!this.readIds.includes(notif.id)) {
                    this.readIds.push(notif.id);
                    localStorage.setItem("wafar_read_notifs", JSON.stringify(this.readIds));
                }
                notif.is_read = true;
                if (this.unreadCount > 0) {
                    this.unreadCount--;
                    globalState.unreadCount = this.unreadCount;
                }
            }

            this.showNotifications = false;

            if (notif.link && this.$route.path !== notif.link) {
                this.$router.push(notif.link);
            }
        },
        getNotifStyle(type) {
            switch (type) {
                case "store":
                    return { icon: "StoreIcon", bg: "#e8f5ea", color: "#10b981" };
                case "product":
                    return { icon: "ProductIcon", bg: "#eff6ff", color: "#3b82f6" };
                case "article":
                    return { icon: "ArticleIcon", bg: "#f3e8ff", color: "#a855f7" };
                case "complaint":
                    return { icon: "ComplaintIcon", bg: "#fef2f2", color: "#ef4444" };
                default:
                    return { icon: "BellIcon", bg: "#f3f4f6", color: "#6b7280" };
            }
        },
        onSearchInput() {
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                this.showDropdown = false;
                return;
            }

            this.showDropdown = true;
            this.isSearching = true;

            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(async () => {
                try {
                    const response = await apiClient.get("/admin/search", {
                        params: { query: this.searchQuery },
                    });
                    if (response.data && response.data.status === "success") {
                        this.searchResults = response.data.results;
                    }
                } catch (error) {
                    console.error("حدث خطأ أثناء البحث الشامل:", error);
                } finally {
                    this.isSearching = false;
                }
            }, 300);
        },
        goToResult(link) {
            this.showDropdown = false;
            this.searchQuery = "";

            if (this.$route.path !== link) {
                this.$router.push(link);
            }
        },
    },
    mounted() {
        document.addEventListener("click", (e) => {
            if (this.$refs.notifContainer && !this.$refs.notifContainer.contains(e.target)) {
                this.showNotifications = false;
            }
            if (!this.$el.contains(e.target)) {
                this.showDropdown = false;
            }
        });

        // جلب الإشعارات فور تحميل الهيدر
        this.fetchNotifications();

        // التحديث التلقائي للإشعارات كل 10 ثوانٍ (تحديث سريع وتفاعلي)
        this.pollingTimer = setInterval(() => {
            this.fetchNotifications();
        }, 10000);

        // جلب بيانات المستخدم الأولية إذا لم تكن موجودة
        if (!globalState.currentUser) {
            apiClient
                .get("/admin/profile")
                .then((res) => {
                    if (res.data.status === "success") {
                        globalState.currentUser = res.data.user;
                    }
                })
                .catch(() => {});
        }
    },
    beforeUnmount() {
        if (this.pollingTimer) {
            clearInterval(this.pollingTimer);
        }
    },
};
</script>

<style scoped>
@keyframes pulse-badge {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.15);
    }
    100% {
        transform: scale(1);
    }
}

.spin-icon {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.header-notification-btn:hover,
.header-notification-btn.btn-active {
    background: #e8f5ea !important;
    border-color: #10b981 !important;
    color: #10b981 !important;
}
</style>

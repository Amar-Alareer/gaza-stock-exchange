<template>
    <!-- الشريط الجانبي للتنقل داخل لوحة تحكم الإدارة -->
    <div
        class="sidebar-overlay"
        v-if="isMobileSidebarOpen"
        @click="closeSidebar"
    ></div>
    <aside
        class="sidebar"
        :class="{ 'sidebar--mobile-open': isMobileSidebarOpen }"
    >
        <div class="sidebar__logo">
            <WafarLogo :dark="true" />
        </div>

        <nav class="sidebar__nav">
            <router-link
                to="/dashboard"
                class="sidebar__item"
                active-class="sidebar__item--active"
            >
                <span class="sidebar__icon">
                    <HomeIcon :size="18" />
                </span>
                <span>الرئيسية</span>
            </router-link>

            <router-link
                to="/items"
                class="sidebar__item"
                active-class="sidebar__item--active"
            >
                <span class="sidebar__icon">
                    <PackageIcon :size="18" />
                </span>
                <span>المنتجات</span>
            </router-link>

            <router-link
                to="/stores"
                class="sidebar__item"
                active-class="sidebar__item--active"
            >
                <span class="sidebar__icon">
                    <StoreIcon :size="18" />
                </span>
                <span>المتاجر</span>
            </router-link>

            <router-link
                to="/categories"
                class="sidebar__item"
                active-class="sidebar__item--active"
            >
                <span class="sidebar__icon">
                    <GridIcon :size="18" />
                </span>
                <span>التصنيفات</span>
            </router-link>

            <router-link
                to="/comparisons"
                class="sidebar__item"
                active-class="sidebar__item--active"
            >
                <span class="sidebar__icon">
                    <BarChart2Icon :size="18" />
                </span>
                <span>المقارنات</span>
            </router-link>

            <router-link
                to="/customers"
                class="sidebar__item"
                active-class="sidebar__item--active"
            >
                <span class="sidebar__icon">
                    <UsersIcon :size="18" />
                </span>
                <span>العملاء</span>
            </router-link>

            <router-link
                to="/profile"
                class="sidebar__item"
                active-class="sidebar__item--active"
            >
                <span class="sidebar__icon">
                    <SettingsIcon :size="18" />
                </span>
                <span>الإعدادات</span>
            </router-link>

            <router-link
                to="/articles"
                class="sidebar__item"
                active-class="sidebar__item--active"
            >
                <span class="sidebar__icon">
                    <NewspaperIcon :size="18" />
                </span>
                <span>المقالات</span>
            </router-link>
        </nav>
    </aside>
</template>

<script>
import WafarLogo from "./WafarLogo.vue";
import { globalState } from "../state.js";
import {
    Home as HomeIcon,
    Package as PackageIcon,
    Store as StoreIcon,
    LayoutGrid as GridIcon,
    BarChart2 as BarChart2Icon,
    FileText as FileTextIcon,
    Users as UsersIcon,
    Settings as SettingsIcon,
    Newspaper as NewspaperIcon,
} from "@lucide/vue";

export default {
    name: "AdminSidebar",
    components: {
        WafarLogo,
        HomeIcon,
        PackageIcon,
        StoreIcon,
        GridIcon,
        BarChart2Icon,
        FileTextIcon,
        UsersIcon,
        SettingsIcon,
        NewspaperIcon,
    },
    computed: {
        isMobileSidebarOpen() {
            return globalState.isMobileSidebarOpen;
        },
    },
    methods: {
        closeSidebar() {
            globalState.isMobileSidebarOpen = false;
        },
    },
};
</script>

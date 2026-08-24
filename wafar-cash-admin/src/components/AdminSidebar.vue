<template>
  <!-- الشريط الجانبي للتنقل داخل لوحة تحكم الإدارة -->
  <aside class="sidebar">
    <div class="sidebar__logo">
      <WafarLogo :dark="true" />
    </div>

    <nav class="sidebar__nav">
      <router-link to="/dashboard" class="sidebar__item" active-class="sidebar__item--active">
        <span class="sidebar__icon">
          <HomeIcon :size="18" />
        </span>
        <span>الرئيسية</span>
      </router-link>

      <a href="#" class="sidebar__item">
        <span class="sidebar__icon">
          <PackageIcon :size="18" />
        </span>
        <span>المنتجات</span>
      </a>

      <a href="#" class="sidebar__item">
        <span class="sidebar__icon">
          <StoreIcon :size="18" />
        </span>
        <span>المتاجر</span>
      </a>

      <a href="#" class="sidebar__item">
        <span class="sidebar__icon">
          <BarChart2Icon :size="18" />
        </span>
        <span>المقارنات</span>
      </a>

      <a href="#" class="sidebar__item">
        <span class="sidebar__icon">
          <FileTextIcon :size="18" />
        </span>
        <span>الطلبات</span>
      </a>

      <a href="#" class="sidebar__item">
        <span class="sidebar__icon">
          <UsersIcon :size="18" />
        </span>
        <span>العملاء</span>
      </a>

      <router-link to="/profile" class="sidebar__item" active-class="sidebar__item--active">
        <span class="sidebar__icon">
          <SettingsIcon :size="18" />
        </span>
        <span>الإعدادات</span>
      </router-link>

      <router-link to="/articles" class="sidebar__item" active-class="sidebar__item--active">
        <span class="sidebar__icon">
          <NewspaperIcon :size="18" />
        </span>
        <span>المقالات</span>
      </router-link>
    </nav>
  </aside>
</template>

<script>
import WafarLogo from './WafarLogo.vue'
import {
  Home as HomeIcon,
  Package as PackageIcon,
  Store as StoreIcon,
  BarChart2 as BarChart2Icon,
  FileText as FileTextIcon,
  Users as UsersIcon,
  Settings as SettingsIcon,
  Newspaper as NewspaperIcon
} from '@lucide/vue'

export default {
  name: 'AdminSidebar',
  components: {
    WafarLogo,
    HomeIcon,
    PackageIcon,
    StoreIcon,
    BarChart2Icon,
    FileTextIcon,
    UsersIcon,
    SettingsIcon,
    NewspaperIcon
  }
}
</script>



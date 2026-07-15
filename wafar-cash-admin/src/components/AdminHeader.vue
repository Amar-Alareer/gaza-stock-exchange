<template>
  <header class="admin-header">
    <button class="admin-header__logout" @click="$emit('logout')"
      style="display: inline-flex; align-items: center; gap: 6px;">
      <span>تسجيل الخروج</span>
      <LogOutIcon :size="15" />
    </button>

    <div class="search-container" style="position: relative; flex: 1; max-width: 500px;">
      <input v-model="searchQuery" @input="onSearchInput" @focus="showDropdown = true" type="text" placeholder="ابحث..."
        class="search-input"
        style="width:800px; padding: 10px 70px 10px 50px; border-radius: 8px; border: 1px solid #ddd; outline: none;" />
      <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #888;">🔍</span>

      <div v-if="searchQuery.length >= 2 && showDropdown" class="search-results-dropdown"
        style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; max-height: 350px; overflow-y: auto; margin-top: 5px; text-align: right;">

        <div v-if="isSearching" style="padding: 15px; text-align: center; color: #888;">
          جاري البحث عن "{{ searchQuery }}"...
        </div>

        <div v-else-if="searchResults.length === 0" style="padding: 15px; text-align: center; color: #888;">
          لا توجد نتائج مطابقة لـ "{{ searchQuery }}"
        </div>

        <div v-else>
          <div v-for="(result, index) in searchResults" :key="index" @click="goToResult(result.link)"
            style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; display: flex; justify-content: space-between; align-items: center;"
            onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='white'">
            <div>
              <strong style="display: block; color: #333; font-size: 14px;">{{ result.title }}</strong>
              <small style="color: #888; font-size: 11px;">{{ result.subtitle }}</small>
            </div>
            <span
              style="background: #e8f5ea; color: #10b981; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
              {{ result.type }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <router-link to="/profile" class="admin-header__profile">
      <div class="admin-header__profile-info">
        <span class="admin-header__profile-name">محمد الحويطي</span>
      </div>
      <img class="admin-header__avatar"
        src="https://api.dicebear.com/7.x/initials/svg?seed=Mohamed&backgroundColor=17692e" alt="صورة المستخدم" />
    </router-link>
  </header>
</template>

<script>
import apiClient from '../api/axios.js'; 

export default {
  name: 'AdminHeader', // أو اسم المكون الخاص بك
  data() {
    return {
      searchQuery: '',
      searchResults: [],
      isSearching: false,
      showDropdown: false,
      searchTimeout: null
    };
  },
  methods: {
    onSearchInput() {
      // إذا قام المستخدم بمسح النص أو كَتب أقل من حرفين، لا نرسل طلباً للسيرفر
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
          const response = await apiClient.get('/admin/search', {
            params: { query: this.searchQuery }
          });
          if (response.data && response.data.status === 'success') {
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
      this.searchQuery = '';

      if (this.$route.path !== link) {
        this.$router.push(link);
      }
    }
  },
  mounted() {
    document.addEventListener('click', (e) => {
      if (!this.$el.contains(e.target)) {
        this.showDropdown = false;
      }
    });
  }
};
</script>

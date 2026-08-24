<template>
    <div class="dashboard-page">
        <AdminSidebar />

        <div class="dashboard-main">
            <AdminHeader @logout="handleLogout" />

            <div class="dashboard-content">

                <!-- ترويسة الصفحة -->
                <div class="items-page-header">
                    <div class="items-page-header__text">
                        <h1 class="items-page-title">
                            <PackageIcon :size="24" style="color: var(--wc-green-bright)" />
                            إدارة المنتجات
                        </h1>
                        <p class="items-page-subtitle">
                            عرض وإدارة جميع الأصناف المسجلة والتحكم ببياناتها
                        </p>
                    </div>
                    <button class="btn-add" @click="openModal('create')" id="btn-add-item">
                        <PlusIcon :size="16" />
                        إضافة صنف جديد
                    </button>
                </div>

                <!-- شريط الفلاتر والبحث الموحد (Flex space-between) -->
                <div class="items-filter-bar">
                    <!-- جهة اليسار: شريط البحث -->
                    <div class="items-search-wrap">
                        <SearchIcon :size="16" class="items-search-icon" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="ابحث باسم الصنف أو الفئة..."
                            class="items-search-input"
                            id="items-search-input"
                        />
                        <button v-if="searchQuery" class="search-clear" @click="searchQuery = ''" title="مسح البحث">
                            <XIcon :size="14" />
                        </button>
                    </div>

                    <!-- جهة اليمين: الفلاتر والترتيب ومفتاح العرض -->
                    <div class="filter-controls-group">
                        <div class="items-count-badge" v-if="!isLoading">
                            <span>{{ filteredItems.length }}</span> صنف
                        </div>

                        <select v-model="selectedCategory" class="items-filter-select" id="items-category-filter">
                            <option value="">كل الفئات</option>
                            <option v-for="cat in categoryOptions" :key="cat.id || cat.name" :value="cat.name">{{ cat.name }}</option>
                        </select>

                        <select v-model="sortBy" class="items-filter-select" id="items-sort-select">
                            <option value="latest">الأحدث إضافة</option>
                            <option value="oldest">الأقدم إضافة</option>
                            <option value="name_asc">الاسم (أ - ي)</option>
                            <option value="name_desc">الاسم (ي - أ)</option>
                        </select>

                        <select v-model="perPage" class="items-filter-select items-filter-select--sm" id="items-per-page">
                            <option :value="10">10 / صفحة</option>
                            <option :value="25">25 / صفحة</option>
                            <option :value="50">50 / صفحة</option>
                        </select>

                        <!-- فاصل رأسي -->
                        <div class="toolbar-divider"></div>

                        <!-- زر التحويل بين الجدول والبطاقات -->
                        <div class="view-mode-toggle">
                            <button
                                class="view-toggle-btn"
                                :class="{ 'view-toggle-btn--active': viewMode === 'table' }"
                                @click="setViewMode('table')"
                                title="عرض جدول"
                            >
                                <ListIcon :size="15" />
                            </button>
                            <button
                                class="view-toggle-btn"
                                :class="{ 'view-toggle-btn--active': viewMode === 'grid' }"
                                @click="setViewMode('grid')"
                                title="عرض بطاقات"
                            >
                                <LayoutGridIcon :size="15" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- حالة التحميل -->
                <div v-if="isLoading" class="loading-state">
                    <div class="loading-spinner"></div>
                    <span>جارٍ تحميل المنتجات...</span>
                </div>

                <!-- حالة الخطأ -->
                <div v-else-if="fetchError" class="error-banner">
                    <AlertCircleIcon :size="20" />
                    <span>{{ fetchError }}</span>
                    <button class="retry-btn" @click="fetchItems">إعادة المحاولة</button>
                </div>

                <!-- عرض العناصر (جدول أو بطاقات) -->
                <template v-else>
                    <!-- فارغ -->
                    <div v-if="filteredItems.length === 0" class="empty-state">
                        <div class="empty-icon">📦</div>
                        <p>لا توجد أصناف مطابقة للبحث</p>
                        <button class="btn-add" @click="openModal('create')">
                            <PlusIcon :size="16" /> أضف أول صنف
                        </button>
                    </div>

                    <!-- 1. وضع الجدول (Table View) -->
                    <div v-else-if="viewMode === 'table'" class="items-table-wrap">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th class="th-checkbox">
                                        <input
                                            type="checkbox"
                                            class="wc-checkbox"
                                            :checked="isAllPaginatedSelected"
                                            @change="toggleSelectAllPaginated"
                                            title="تحديد جميع الأصناف في هذه الصفحة"
                                        />
                                    </th>
                                    <th>#</th>
                                    <th>اسم الصنف</th>
                                    <th>الفئة</th>
                                    <th>السعر الإرشادي</th>
                                    <th>المتاجر المتوفرة</th>
                                    <th>تاريخ الإضافة</th>
                                    <th class="th-actions">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in paginatedItems"
                                    :key="item.id"
                                    :class="{ 'tr-selected': selectedItemIds.includes(item.id) }"
                                >
                                    <!-- Checkbox -->
                                    <td class="td-checkbox">
                                        <input
                                            type="checkbox"
                                            class="wc-checkbox"
                                            :value="item.id"
                                            v-model="selectedItemIds"
                                        />
                                    </td>

                                    <!-- الرقم المسلسل -->
                                    <td class="td-num">{{ (currentPage - 1) * perPage + index + 1 }}</td>

                                    <!-- اسم الصنف مع Thumbnail صورة مصغرة 36x36px -->
                                    <td>
                                        <div class="table-item-cell">
                                            <div class="table-item-thumb-wrap">
                                                <img
                                                    :src="getItemImage(item)"
                                                    :alt="item.name"
                                                    class="table-item-thumb"
                                                    @error="handleImageError($event, item)"
                                                />
                                            </div>
                                            <div>
                                                <span class="table-item-name">{{ item.name }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- الفئة -->
                                    <td>
                                        <span
                                            v-if="item.category_name || item.category"
                                            class="category-badge"
                                            :style="getCategoryBadgeStyle(item.category_name || item.category)"
                                        >{{ item.category_name || item.category }}</span>
                                        <span v-else class="category-badge category-badge--empty">غير محدد</span>
                                    </td>

                                    <!-- السعر الإرشادي -->
                                    <td>
                                        <span v-if="item.min_price" class="price-cell">
                                            {{ item.min_price }}
                                            <span class="price-unit">شيكل</span>
                                        </span>
                                        <span v-else class="text-muted">—</span>
                                    </td>

                                    <!-- عدد المتاجر (شارة تفاعلية Interactive Badge) -->
                                    <td>
                                        <button
                                            class="stores-badge-btn"
                                            :class="{ 'stores-badge-btn--active': item.stores_count > 0 }"
                                            @click="openStoresModal(item)"
                                            :title="item.stores_count > 0 ? 'عرض تفاصيل المتاجر والأسعار' : 'لا تتوفر متاجر حالياً'"
                                        >
                                            <StoreIcon :size="13" />
                                            <span>{{ item.stores_count }} {{ item.stores_count === 1 ? 'متجر' : 'متاجر' }}</span>
                                            <ChevronDownIcon v-if="item.stores_count > 0" :size="12" class="badge-arrow" />
                                        </button>
                                    </td>

                                    <!-- التاريخ -->
                                    <td class="td-date">{{ formatDate(item.created_at) }}</td>

                                    <!-- الإجراءات -->
                                    <td>
                                        <div class="table-actions">
                                            <button
                                                class="icon-btn icon-btn--view"
                                                @click="openStoresModal(item)"
                                                :id="`btn-view-item-${item.id}`"
                                                title="معاينة أسعار المتاجر"
                                            >
                                                <EyeIcon :size="15" />
                                            </button>
                                            <button
                                                class="icon-btn icon-btn--edit"
                                                @click="openModal('edit', item)"
                                                :id="`btn-edit-item-${item.id}`"
                                                title="تعديل الصنف"
                                            >
                                                <EditIcon :size="15" />
                                            </button>
                                            <button
                                                class="icon-btn icon-btn--delete"
                                                @click="confirmDelete(item)"
                                                :id="`btn-delete-item-${item.id}`"
                                                title="حذف الصنف"
                                            >
                                                <Trash2Icon :size="15" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div v-if="totalPages > 1" class="pagination-bar">
                            <button class="page-btn" :disabled="currentPage === 1" @click="currentPage = 1" title="الأولى">
                                <ChevronsRightIcon :size="15" />
                            </button>
                            <button class="page-btn" :disabled="currentPage === 1" @click="currentPage--" title="السابقة">
                                <ChevronRightIcon :size="15" />
                            </button>

                            <button
                                v-for="p in visiblePages"
                                :key="p"
                                class="page-btn"
                                :class="{ 'page-btn--active': p === currentPage }"
                                @click="currentPage = p"
                            >{{ p }}</button>

                            <button class="page-btn" :disabled="currentPage === totalPages" @click="currentPage++" title="التالية">
                                <ChevronLeftIcon :size="15" />
                            </button>
                            <button class="page-btn" :disabled="currentPage === totalPages" @click="currentPage = totalPages" title="الأخيرة">
                                <ChevronsLeftIcon :size="15" />
                            </button>

                            <span class="pagination-info">
                                عرض {{ (currentPage - 1) * perPage + 1 }}–{{ Math.min(currentPage * perPage, filteredItems.length) }}
                                من {{ filteredItems.length }}
                            </span>
                        </div>
                    </div>

                    <!-- 2. وضع البطاقات (Cards / Grid View) -->
                    <div v-else class="items-grid-wrap">
                        <div class="items-grid">
                            <div
                                v-for="item in paginatedItems"
                                :key="item.id"
                                class="item-card"
                                :class="{ 'item-card--selected': selectedItemIds.includes(item.id) }"
                            >
                                <div class="item-card__header">
                                    <input
                                        type="checkbox"
                                        class="wc-checkbox"
                                        :value="item.id"
                                        v-model="selectedItemIds"
                                    />
                                    <span
                                        v-if="item.category_name || item.category"
                                        class="category-badge"
                                        :style="getCategoryBadgeStyle(item.category_name || item.category)"
                                    >{{ item.category_name || item.category }}</span>
                                    <span v-else class="category-badge category-badge--empty">غير محدد</span>
                                </div>

                                <div class="item-card__body">
                                    <div class="item-card__image-wrap">
                                        <img
                                            :src="getItemImage(item)"
                                            :alt="item.name"
                                            class="item-card__image"
                                            @error="handleImageError($event, item)"
                                        />
                                    </div>
                                    <h3 class="item-card__title">{{ item.name }}</h3>

                                    <div class="item-card__meta">
                                        <div class="item-card__price">
                                            <span v-if="item.min_price" class="price-cell">
                                                {{ item.min_price }} <span class="price-unit">شيكل</span>
                                            </span>
                                            <span v-else class="text-muted">—</span>
                                        </div>

                                        <button
                                            class="stores-badge-btn"
                                            :class="{ 'stores-badge-btn--active': item.stores_count > 0 }"
                                            @click="openStoresModal(item)"
                                            :title="item.stores_count > 0 ? 'عرض تفاصيل المتاجر والأسعار' : 'لا تتوفر متاجر حالياً'"
                                        >
                                            <StoreIcon :size="13" />
                                            <span>{{ item.stores_count }} {{ item.stores_count === 1 ? 'متجر' : 'متاجر' }}</span>
                                            <ChevronDownIcon v-if="item.stores_count > 0" :size="12" class="badge-arrow" />
                                        </button>
                                    </div>
                                </div>

                                <div class="item-card__footer">
                                    <span class="item-card__date">{{ formatDate(item.created_at) }}</span>
                                    <div class="table-actions">
                                        <button
                                            class="icon-btn icon-btn--view"
                                            @click="openStoresModal(item)"
                                            title="معاينة أسعار المتاجر"
                                        >
                                            <EyeIcon :size="15" />
                                        </button>
                                        <button
                                            class="icon-btn icon-btn--edit"
                                            @click="openModal('edit', item)"
                                            title="تعديل الصنف"
                                        >
                                            <EditIcon :size="15" />
                                        </button>
                                        <button
                                            class="icon-btn icon-btn--delete"
                                            @click="confirmDelete(item)"
                                            title="حذف الصنف"
                                        >
                                            <Trash2Icon :size="15" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div v-if="totalPages > 1" class="pagination-bar">
                            <button class="page-btn" :disabled="currentPage === 1" @click="currentPage = 1" title="الأولى">
                                <ChevronsRightIcon :size="15" />
                            </button>
                            <button class="page-btn" :disabled="currentPage === 1" @click="currentPage--" title="السابقة">
                                <ChevronRightIcon :size="15" />
                            </button>

                            <button
                                v-for="p in visiblePages"
                                :key="p"
                                class="page-btn"
                                :class="{ 'page-btn--active': p === currentPage }"
                                @click="currentPage = p"
                            >{{ p }}</button>

                            <button class="page-btn" :disabled="currentPage === totalPages" @click="currentPage++" title="التالية">
                                <ChevronLeftIcon :size="15" />
                            </button>
                            <button class="page-btn" :disabled="currentPage === totalPages" @click="currentPage = totalPages" title="الأخيرة">
                                <ChevronsLeftIcon :size="15" />
                            </button>

                            <span class="pagination-info">
                                عرض {{ (currentPage - 1) * perPage + 1 }}–{{ Math.min(currentPage * perPage, filteredItems.length) }}
                                من {{ filteredItems.length }}
                            </span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- ======= شريط الإجراءات الجماعية (Bulk Actions Floating Bar) ======= -->
        <transition name="slide-up">
            <div v-if="selectedItemIds.length > 0" class="bulk-actions-bar">
                <div class="bulk-actions-content">
                    <div class="bulk-info">
                        <span class="bulk-count-badge">{{ selectedItemIds.length }}</span>
                        <span>صنف محدد</span>
                    </div>
                    <div class="bulk-btns">
                        <button class="btn-bulk-delete" @click="showBulkDeleteConfirm = true">
                            <Trash2Icon :size="15" />
                            حذف الأصناف المحددة
                        </button>
                        <button class="btn-bulk-clear" @click="selectedItemIds = []">
                            إلغاء التحديد
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- ======= Quick View Modal — المتاجر والأسعار التفصيلي ======= -->
        <div v-if="showStoresModal" class="modal-overlay" @click.self="showStoresModal = false">
            <div class="modal modal--quick-view" role="dialog" aria-modal="true">

                <!-- هيدر المودال -->
                <div class="qv-modal-header">
                    <h2 class="qv-modal-title">
                        <EyeIcon :size="18" style="color: var(--wc-green-bright)" />
                        معاينة تفصيلية للصنف
                    </h2>
                    <button class="modal__close" @click="showStoresModal = false" title="إغلاق (Esc)">&times;</button>
                </div>

                <!-- القسم العلوي: صورة + معلومات المنتج الأساسية -->
                <div class="qv-hero">
                    <!-- الصورة المكبرة -->
                    <div class="qv-image-wrapper">
                        <img
                            :src="getItemImage(activeStoresItem)"
                            :alt="activeStoresItem?.name"
                            class="qv-hero-image"
                            @error="(e) => handleImageError(e, activeStoresItem)"
                        />
                        <span
                            class="qv-status-chip"
                            :class="activeStoresItem?.is_active === false ? 'qv-status-chip--inactive' : 'qv-status-chip--active'"
                        >
                            {{ activeStoresItem?.is_active === false ? 'غير نشط' : 'نشط' }}
                        </span>
                    </div>

                    <!-- بيانات المنتج -->
                    <div class="qv-product-info">
                        <h3 class="qv-product-name">{{ activeStoresItem?.name || '—' }}</h3>

                        <div class="qv-meta-grid">
                            <!-- التصنيف -->
                            <div class="qv-meta-item">
                                <span class="qv-meta-label">التصنيف</span>
                                <span class="category-badge" :style="getCategoryBadgeStyle(activeStoresItem?.category_name || activeStoresItem?.category)">
                                    {{ activeStoresItem?.category_name || activeStoresItem?.category || '—' }}
                                </span>
                            </div>

                            <!-- السعر الإرشادي -->
                            <div class="qv-meta-item">
                                <span class="qv-meta-label">السعر الإرشادي</span>
                                <span class="qv-guide-price">
                                    <span v-if="activeStoresItem?.min_price || activeStoresItem?.prices_min">
                                        {{ activeStoresItem?.min_price || activeStoresItem?.prices_min }}
                                        <small>شيكل</small>
                                    </span>
                                    <span v-else class="qv-no-data">—</span>
                                </span>
                            </div>

                            <!-- تاريخ الإضافة -->
                            <div class="qv-meta-item">
                                <span class="qv-meta-label">تاريخ الإضافة</span>
                                <span class="qv-meta-value">{{ formatDate(activeStoresItem?.created_at) }}</span>
                            </div>

                            <!-- آخر تحديث -->
                            <div class="qv-meta-item">
                                <span class="qv-meta-label">آخر تحديث</span>
                                <span class="qv-meta-value">{{ formatDate(activeStoresItem?.updated_at) }}</span>
                            </div>
                        </div>

                        <!-- عدد المتاجر الرقم السريع -->
                        <div class="qv-stores-count-badge">
                            <StoreIcon :size="14" />
                            <span>متوفر في
                                <strong>{{ activeStoresItem?.stores?.length || activeStoresItem?.prices_count || 0 }}</strong>
                                متجر
                            </span>
                        </div>
                    </div>
                </div>

                <!-- فاصل -->
                <div class="qv-divider"></div>

                <!-- جدول المتاجر -->
                <div class="qv-stores-section">
                    <div class="qv-stores-section-header">
                        <h4 class="qv-stores-title">
                            <StoreIcon :size="15" />
                            المتاجر التي توفر هذا الصنف
                        </h4>
                        <span class="qv-stores-count">{{ (activeStoresItem?.stores || activeStoresItem?.prices || []).length }} نتيجة</span>
                    </div>

                    <!-- حالة فارغة -->
                    <div v-if="!getItemStores(activeStoresItem).length" class="qv-empty-stores">
                        <StoreIcon :size="36" style="color: #cbd5e1" />
                        <p>لا تتوفر أسعار مسجلة لهذا الصنف حالياً في أي متجر.</p>
                    </div>

                    <!-- جدول المتاجر -->
                    <div v-else class="qv-stores-table-wrapper">
                        <table class="qv-stores-table">
                            <thead>
                                <tr>
                                    <th>المتجر والمنطقة</th>
                                    <th>السعر</th>
                                    <th>التوفر</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="st in getItemStores(activeStoresItem)"
                                    :key="st.id || st.store_id"
                                    class="qv-store-row"
                                >
                                    <!-- اسم المتجر + المنطقة -->
                                    <td class="qv-store-name-cell">
                                        <div class="qv-store-name-wrap">
                                            <span class="qv-store-dot"></span>
                                            <div>
                                                <router-link
                                                    :to="{ name: 'StoreDetails', params: { id: st.store_id || st.id }, query: { item: activeStoresItem?.id } }"
                                                    class="qv-store-link"
                                                    @click="showStoresModal = false"
                                                >
                                                    {{ st.store_name || st.store?.name || '—' }}
                                                </router-link>
                                                <span class="qv-store-region" v-if="st.store?.city || st.store?.region || st.region">
                                                    {{ st.store?.city || st.store?.region || st.region }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- السعر -->
                                    <td class="qv-price-cell">
                                        <span class="qv-price-value" v-if="st.price">
                                            {{ st.price }}
                                            <small>₪</small>
                                        </span>
                                        <span v-else class="qv-no-data">—</span>
                                    </td>

                                    <!-- حالة التوفر -->
                                    <td>
                                        <span
                                            class="qv-availability-badge"
                                            :class="st.is_available === false ? 'qv-availability-badge--out' : 'qv-availability-badge--in'"
                                        >
                                            {{ st.is_available === false ? 'غير متوفر' : 'متوفر' }}
                                        </span>
                                    </td>

                                    <!-- رابط الانتقال -->
                                    <td class="qv-action-cell">
                                        <router-link
                                            :to="{ name: 'StoreDetails', params: { id: st.store_id || st.id }, query: { item: activeStoresItem?.id } }"
                                            class="qv-goto-btn"
                                            @click="showStoresModal = false"
                                            title="الانتقال لصفحة المتجر"
                                        >
                                            <ChevronLeftIcon :size="14" />
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- فوتر المودال -->
                <div class="modal__footer">
                    <button class="btn-cancel btn-cancel--full" @click="showStoresModal = false">إغلاق</button>
                </div>
            </div>
        </div>

        <!-- ======= مودال الإضافة / التعديل الاحترافي المحسن ======= -->
        <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
            <div class="modal modal--edit-item" role="dialog" aria-modal="true">
                <div class="modal__header">
                    <h2 class="modal__title">
                        <PackageIcon :size="18" style="color: var(--wc-green-bright)" />
                        {{ modalMode === 'create' ? 'إضافة صنف جديد' : 'تعديل بيانات الصنف' }}
                    </h2>
                    <button class="modal__close" @click="closeModal" id="btn-close-modal" title="إغلاق (Esc)">&times;</button>
                </div>
                <div class="modal__body">
                    <div v-if="saveError" class="error-alert">
                        <AlertCircleIcon :size="15" />
                        <span>{{ saveError }}</span>
                    </div>

                    <!-- 1. رفع ومعاينة صورة المنتج -->
                    <div class="form-group">
                        <label class="form-label">
                            صورة المنتج
                            <span class="form-hint">اختياري</span>
                        </label>

                        <div class="image-upload-wrapper">
                            <!-- معاينة الصورة الحالية -->
                            <div v-if="form.image_url" class="image-preview-card">
                                <img :src="form.image_url" alt="معاينة المنتج" class="image-preview-img" />
                                <div class="image-preview-actions">
                                    <button type="button" class="btn-img-action btn-img-change" @click="triggerFileInput" title="تغيير الصورة">
                                        <UploadIcon :size="14" /> تغيير
                                    </button>
                                    <button type="button" class="btn-img-action btn-img-remove" @click="removeFormImage" title="إزالة الصورة">
                                        <Trash2Icon :size="14" /> إزالة
                                    </button>
                                </div>
                            </div>

                            <!-- منطقة الرفع / السحب والإسقاط -->
                            <div
                                v-else
                                class="image-dropzone"
                                @click="triggerFileInput"
                                @dragover.prevent
                                @drop.prevent="handleFileDrop"
                            >
                                <UploadIcon :size="24" class="dropzone-icon" />
                                <span class="dropzone-text">اختر صورة أو اسحبها هنا</span>
                                <span class="dropzone-subtext">JPG, PNG, WEBP (بحد أقصى 5 ميجابايت)</span>
                            </div>

                            <input
                                type="file"
                                ref="fileInput"
                                class="hidden-file-input"
                                accept="image/*"
                                @change="handleFileUpload"
                            />
                        </div>
                    </div>

                    <!-- 2. اسم الصنف -->
                    <div class="form-group">
                        <label class="form-label" for="item-name">
                            اسم الصنف <span class="required-star">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            id="item-name"
                            class="form-input"
                            :class="{ 'form-input--error': formErrors.name }"
                            placeholder="مثال: طماطم طازجة، أرز بسمتي، دجاج..."
                            @keyup.enter="saveItem"
                        />
                        <span v-if="formErrors.name" class="field-error">{{ formErrors.name }}</span>
                    </div>

                    <!-- 3. قائمة الفئات الإجبارية (Select Dropdown) -->
                    <div class="form-group">
                        <label class="form-label" for="item-category">
                            فئة الصنف <span class="required-star">*</span>
                        </label>
                        <select
                            v-model="form.category_id"
                            id="item-category"
                            class="form-input form-select"
                            :class="{ 'form-input--error': formErrors.category_id }"
                        >
                            <option :value="null" disabled>اختر فئة المنتج...</option>
                            <option v-for="cat in categoryOptions" :key="cat.id || cat.name" :value="cat.id">{{ cat.name }}</option>
                        </select>
                        <span v-if="formErrors.category_id" class="field-error">{{ formErrors.category_id }}</span>
                    </div>

                    <!-- 4. السعر الإرشادي بالشيكل -->
                    <div class="form-group">
                        <label class="form-label" for="item-price">
                            السعر الإرشادي
                            <span class="form-hint">اختياري</span>
                        </label>
                        <div class="price-input-wrap">
                            <input
                                v-model="form.min_price"
                                type="number"
                                step="0.5"
                                min="0"
                                id="item-price"
                                class="form-input price-input"
                                placeholder="مثال: 12.5"
                                @keyup.enter="saveItem"
                            />
                            <span class="price-unit-addon">شيكل</span>
                        </div>
                    </div>

                </div>
                <!-- أزرار الإجراءات المتناسقة بعرض متوازن -->
                <div class="modal__footer modal__footer--balanced">
                    <button
                        class="btn-save btn-save--full"
                        @click="saveItem"
                        :disabled="isSaving"
                        id="btn-save-item"
                    >
                        <span v-if="isSaving" class="btn-spinner"></span>
                        <span>{{ isSaving ? (modalMode === 'create' ? 'جارٍ إضافة الصنف...' : 'جارٍ حفظ التعديلات...') : (modalMode === 'create' ? 'إضافة الصنف' : 'حفظ التعديلات') }}</span>
                    </button>
                    <button class="btn-cancel btn-cancel--full" @click="closeModal" id="btn-cancel-modal">إلغاء</button>
                </div>
            </div>
        </div>

        <!-- ======= مودال تأكيد الحذف للفرد ======= -->
        <div v-if="showDeleteConfirm" class="modal-overlay" @click.self="showDeleteConfirm = false">
            <div class="modal modal--danger" role="dialog" aria-modal="true">
                <div class="modal__header">
                    <h2 class="modal__title">
                        <Trash2Icon :size="18" />
                        تأكيد الحذف
                    </h2>
                    <button class="modal__close" @click="showDeleteConfirm = false" title="إغلاق">&times;</button>
                </div>
                <div class="modal__body">
                    <p class="delete-confirm-text">
                        هل أنت متأكد من حذف الصنف
                        <strong>« {{ itemToDelete?.name }} »</strong>؟
                    </p>
                    <p class="delete-warning-row">
                        <AlertCircleIcon :size="14" />
                        <span>هذا الإجراء لا يمكن التراجع عنه.</span>
                    </p>
                    <div v-if="deleteError" class="error-alert">
                        <AlertCircleIcon :size="15" />
                        <span>{{ deleteError }}</span>
                    </div>
                </div>
                <div class="modal__footer modal__footer--balanced">
                    <button class="btn-delete btn-save--full" @click="deleteItem" :disabled="isDeleting" id="btn-confirm-delete">
                        <span v-if="isDeleting" class="btn-spinner btn-spinner--white"></span>
                        {{ isDeleting ? 'جارٍ الحذف...' : 'نعم، احذف الصنف' }}
                    </button>
                    <button class="btn-cancel btn-cancel--full" @click="showDeleteConfirm = false" id="btn-cancel-delete">إلغاء</button>
                </div>
            </div>
        </div>

        <!-- ======= مودال تأكيد الحذف الجماعي ======= -->
        <div v-if="showBulkDeleteConfirm" class="modal-overlay" @click.self="showBulkDeleteConfirm = false">
            <div class="modal modal--danger" role="dialog" aria-modal="true">
                <div class="modal__header">
                    <h2 class="modal__title">
                        <Trash2Icon :size="18" />
                        تأكيد الحذف الجماعي
                    </h2>
                    <button class="modal__close" @click="showBulkDeleteConfirm = false" title="إغلاق">&times;</button>
                </div>
                <div class="modal__body">
                    <p class="delete-confirm-text">
                        هل أنت متأكد من حذف <strong>« {{ selectedItemIds.length }} »</strong> أصناف مُمحددة؟
                    </p>
                    <p class="delete-warning-row">
                        <AlertCircleIcon :size="14" />
                        <span>سيتم حذف هذه الأصناف بشكل دائم من النظام.</span>
                    </p>
                    <div v-if="bulkDeleteError" class="error-alert">
                        <AlertCircleIcon :size="15" />
                        <span>{{ bulkDeleteError }}</span>
                    </div>
                </div>
                <div class="modal__footer modal__footer--balanced">
                    <button class="btn-delete btn-save--full" @click="bulkDeleteItems" :disabled="isBulkDeleting">
                        <span v-if="isBulkDeleting" class="btn-spinner btn-spinner--white"></span>
                        {{ isBulkDeleting ? 'جارٍ الحذف...' : 'نعم، احذف المحدد' }}
                    </button>
                    <button class="btn-cancel btn-cancel--full" @click="showBulkDeleteConfirm = false">إلغاء</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import AdminSidebar from "../components/AdminSidebar.vue";
import AdminHeader from "../components/AdminHeader.vue";
import { globalState } from "../state.js";
import apiClient from "../api/axios.js";
import {
    Plus as PlusIcon,
    Package as PackageIcon,
    AlertCircle as AlertCircleIcon,
    Search as SearchIcon,
    Edit as EditIcon,
    Trash2 as Trash2Icon,
    X as XIcon,
    ChevronLeft as ChevronLeftIcon,
    ChevronRight as ChevronRightIcon,
    ChevronsLeft as ChevronsLeftIcon,
    ChevronsRight as ChevronsRightIcon,
    Store as StoreIcon,
    Eye as EyeIcon,
    ChevronDown as ChevronDownIcon,
    LayoutGrid as LayoutGridIcon,
    List as ListIcon,
    Upload as UploadIcon,
} from "@lucide/vue";

// ——— قائمة الفئات المعرفة في النظام (احتياطية) ———
const DEFAULT_CATEGORIES = [
    "خضروات",
    "فواكه",
    "لحوم ودواجن",
    "مواد تموينية",
    "زيوت وبقوليات",
    "ألبان وأجبان",
    "مشروبات وحلويات",
    "أخرى",
];

// ——— خريطة ألوان الفئات ———
const CATEGORY_PALETTES = [
    { bg: "#e8f5ea", color: "#15803d", border: "#bbf7d0" }, // أخضر
    { bg: "#fff7ed", color: "#c2410c", border: "#fed7aa" }, // برتقالي
    { bg: "#fef9c3", color: "#a16207", border: "#fde68a" }, // أصفر
    { bg: "#eff6ff", color: "#1d4ed8", border: "#bfdbfe" }, // أزرق
    { bg: "#f5f3ff", color: "#6d28d9", border: "#ddd6fe" }, // بنفسجي
    { bg: "#fce7f3", color: "#be185d", border: "#fbcfe8" }, // وردي
    { bg: "#ecfeff", color: "#0e7490", border: "#a5f3fc" }, // سماوي
    { bg: "#f0fdf4", color: "#166534", border: "#86efac" }, // زيتي
];

const _catColorCache = {};
let _catColorCounter = 0;

function getCategoryPalette(category) {
    if (!category) return { bg: "#f1f5f9", color: "#64748b", border: "#e2e8f0" };
    if (!_catColorCache[category]) {
        _catColorCache[category] = CATEGORY_PALETTES[_catColorCounter % CATEGORY_PALETTES.length];
        _catColorCounter++;
    }
    return _catColorCache[category];
}

const PRODUCT_THUMBNAILS = {
    "جرادة": "https://images.unsplash.com/photo-1540420773420-3366772f4999?w=100&auto=format&fit=crop&q=80",
    "بقدونس": "https://images.unsplash.com/photo-1592417817098-8f3d6eb231fc?w=100&auto=format&fit=crop&q=80",
    "ورق عنب": "https://images.unsplash.com/photo-1509358271058-acd05cc93280?w=100&auto=format&fit=crop&q=80",
    "طماطم": "https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=100&auto=format&fit=crop&q=80",
    "خيار": "https://images.unsplash.com/photo-1604977042946-1eecc30f269e?w=100&auto=format&fit=crop&q=80",
    "بطاطس": "https://images.unsplash.com/photo-1518977676601-b53f82aba655?w=100&auto=format&fit=crop&q=80",
    "بصل": "https://images.unsplash.com/photo-1618512496248-a07fe83aa8cf?w=100&auto=format&fit=crop&q=80",
    "ليمون": "https://images.unsplash.com/photo-1534531141161-e4e6e48a36e3?w=100&auto=format&fit=crop&q=80",
    "أرز": "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=100&auto=format&fit=crop&q=80",
    "زيت": "https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=100&auto=format&fit=crop&q=80",
    "دجاج": "https://images.unsplash.com/photo-1587593810167-a84920ea0781?w=100&auto=format&fit=crop&q=80",
    "لحم": "https://images.unsplash.com/photo-1603048588665-791ca8aea617?w=100&auto=format&fit=crop&q=80",
};

function createSvgPlaceholder(name, category) {
    const char = (name || "ص").charAt(0);
    const palette = getCategoryPalette(category);
    const bg = palette.bg;
    const color = palette.color;
    const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><rect width="64" height="64" rx="12" fill="${bg}"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-weight="bold" font-size="28" fill="${color}">${char}</text></svg>`;
    return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
}

export default {
    name: "AdminItems",
    components: {
        AdminSidebar,
        AdminHeader,
        PlusIcon,
        PackageIcon,
        AlertCircleIcon,
        SearchIcon,
        EditIcon,
        Trash2Icon,
        XIcon,
        ChevronLeftIcon,
        ChevronRightIcon,
        ChevronsLeftIcon,
        ChevronsRightIcon,
        StoreIcon,
        EyeIcon,
        ChevronDownIcon,
        LayoutGridIcon,
        ListIcon,
        UploadIcon,
    },

    data() {
        return {
            items: [],
            categories: [],           // التصنيفات من الـ API
            searchQuery: "",
            selectedCategory: "",
            sortBy: "latest",
            perPage: 10,
            currentPage: 1,

            viewMode: localStorage.getItem("wafar_items_view_mode") || "table",

            selectedItemIds: [],
            showBulkDeleteConfirm: false,
            isBulkDeleting: false,
            bulkDeleteError: "",

            showStoresModal: false,
            activeStoresItem: null,

            isLoading: false,
            isSaving: false,
            isDeleting: false,

            fetchError: "",
            saveError: "",
            deleteError: "",

            showModal: false,
            modalMode: "create",
            currentItemId: null,
            form: {
                name: "",
                category_id: null,
                min_price: "",
                image_url: "",
            },
            formErrors: {
                name: "",
                category_id: "",
            },

            showDeleteConfirm: false,
            itemToDelete: null,
        };
    },

    computed: {
        // التصنيفات للفلتر والفورم (من API أو احتياطية)
        categoryOptions() {
            if (this.categories.length > 0) return this.categories;
            return DEFAULT_CATEGORIES.map((name, i) => ({ id: null, name }));
        },

        filteredItems() {
            let list = [...this.items];

            if (this.selectedCategory) {
                list = list.filter((i) => {
                    const catName = i.category_name || i.category_relation?.name || i.category;
                    return catName === this.selectedCategory;
                });
            }

            if (this.searchQuery.trim()) {
                const q = this.searchQuery.toLowerCase();
                list = list.filter(
                    (i) =>
                        i.name?.toLowerCase().includes(q) ||
                        (i.category_name || i.category)?.toLowerCase().includes(q),
                );
            }

            if (this.sortBy === "latest")    list.sort((a, b) => b.id - a.id);
            else if (this.sortBy === "oldest") list.sort((a, b) => a.id - b.id);
            else if (this.sortBy === "name_asc") list.sort((a, b) => (a.name || "").localeCompare(b.name || "", "ar"));
            else if (this.sortBy === "name_desc") list.sort((a, b) => (b.name || "").localeCompare(a.name || "", "ar"));

            return list;
        },

        totalPages() {
            return Math.max(1, Math.ceil(this.filteredItems.length / this.perPage));
        },

        paginatedItems() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredItems.slice(start, start + this.perPage);
        },

        visiblePages() {
            const pages = [];
            const delta = 2;
            const left = Math.max(1, this.currentPage - delta);
            const right = Math.min(this.totalPages, this.currentPage + delta);
            for (let i = left; i <= right; i++) pages.push(i);
            return pages;
        },

        isAllPaginatedSelected() {
            if (this.paginatedItems.length === 0) return false;
            return this.paginatedItems.every((item) => this.selectedItemIds.includes(item.id));
        },
    },

    watch: {
        searchQuery()       { this.currentPage = 1; },
        selectedCategory()  { this.currentPage = 1; },
        perPage()           { this.currentPage = 1; },
        sortBy()            { this.currentPage = 1; },
    },

    mounted() {
        this.fetchItems();
        this.fetchCategories();
        window.addEventListener("keydown", this.handleKeyDown);
    },

    unmounted() {
        window.removeEventListener("keydown", this.handleKeyDown);
    },

    methods: {
        // الاستجابة لمفتاح Esc لإغلاق النوافذ المنبثقة
        handleKeyDown(e) {
            if (e.key === "Escape") {
                if (this.showModal) this.closeModal();
                if (this.showStoresModal) this.showStoresModal = false;
                if (this.showDeleteConfirm) this.showDeleteConfirm = false;
                if (this.showBulkDeleteConfirm) this.showBulkDeleteConfirm = false;
            }
        },

        setViewMode(mode) {
            this.viewMode = mode;
            localStorage.setItem("wafar_items_view_mode", mode);
        },

        async fetchItems() {
            this.isLoading = true;
            this.fetchError = "";
            try {
                const response = await apiClient.get("/admin/items");
                this.items = Array.isArray(response.data)
                    ? response.data
                    : response.data.data || [];
            } catch (error) {
                this.fetchError = "تعذّر الاتصال بالخادم. يرجى التحقق من تشغيل Laravel.";
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },

        async fetchCategories() {
            try {
                const res = await apiClient.get("/categories");
                const raw = Array.isArray(res.data) ? res.data : res.data.data || [];
                this.categories = raw.filter(c => c.is_active !== false);
            } catch (e) {
                // استخدام القائمة الاحتياطية عند فشل الجلب
                console.warn("لم يتم جلب التصنيفات من الـ API", e);
            }
        },

        async saveItem() {
            if (!this.validateForm()) return;
            this.isSaving = true;
            this.saveError = "";
            try {
                // إيجاد اسم التصنيف من الـ ID المختار
                const selectedCat = this.categories.find(c => c.id === this.form.category_id);
                const payload = {
                    name:        this.form.name.trim(),
                    category_id: this.form.category_id || null,
                    category:    selectedCat?.name || null,
                    min_price:   this.form.min_price ? parseFloat(this.form.min_price) : null,
                    image_url:   this.form.image_url ? this.form.image_url.trim() : null,
                };
                if (this.modalMode === "edit") {
                    await apiClient.put(`/admin/items/${this.currentItemId}`, payload);
                } else {
                    await apiClient.post("/admin/items", payload);
                }
                this.closeModal();
                await this.fetchItems();
                await this.fetchCategories(); // تحديث عدد المنتجات في التصنيفات
                globalState.triggerNotificationRefresh();
            } catch (error) {
                this.saveError = error.response?.data?.message || "فشل حفظ البيانات. تحقق من الاتصال.";
            } finally {
                this.isSaving = false;
            }
        },

        async deleteItem() {
            if (!this.itemToDelete) return;
            this.isDeleting = true;
            this.deleteError = "";
            try {
                await apiClient.delete(`/admin/items/${this.itemToDelete.id}`);
                this.showDeleteConfirm = false;
                this.itemToDelete = null;
                this.selectedItemIds = this.selectedItemIds.filter(id => id !== this.itemToDelete?.id);
                await this.fetchItems();
                globalState.triggerNotificationRefresh(); // تحديث شارة الإشعارات
            } catch (error) {
                this.deleteError = "تعذر حذف الصنف. يرجى المحاولة مجدداً.";
            } finally {
                this.isDeleting = false;
            }
        },

        async bulkDeleteItems() {
            if (this.selectedItemIds.length === 0) return;
            this.isBulkDeleting = true;
            this.bulkDeleteError = "";
            try {
                await apiClient.post("/admin/items/bulk-delete", { ids: this.selectedItemIds });
                this.showBulkDeleteConfirm = false;
                this.selectedItemIds = [];
                await this.fetchItems();
                globalState.triggerNotificationRefresh(); // تحديث شارة الإشعارات
            } catch (error) {
                this.bulkDeleteError = error.response?.data?.message || "تعذر حذف الأصناف المحددة.";
            } finally {
                this.isBulkDeleting = false;
            }
        },

        toggleSelectAllPaginated() {
            const currentIds = this.paginatedItems.map(i => i.id);
            if (this.isAllPaginatedSelected) {
                this.selectedItemIds = this.selectedItemIds.filter(id => !currentIds.includes(id));
            } else {
                const newIdsSet = new Set([...this.selectedItemIds, ...currentIds]);
                this.selectedItemIds = Array.from(newIdsSet);
            }
        },

        openStoresModal(item) {
            this.activeStoresItem = item;
            this.showStoresModal = true;
        },

        getItemStores(item) {
            if (!item) return [];
            if (item.stores && item.stores.length) return item.stores;
            if (item.prices && item.prices.length) return item.prices;
            return [];
        },

        openModal(mode, item = null) {
            this.modalMode = mode;
            this.saveError = "";
            this.formErrors = { name: "", category_id: "" };

            if (mode === "create") {
                this.form = {
                    name: "",
                    category_id: this.categories[0]?.id || null,
                    min_price: "",
                    image_url: "",
                };
                this.currentItemId = null;
            } else {
                this.form = {
                    name: item.name || "",
                    category_id: item.category_id || (this.categories.find(c => c.name === item.category)?.id) || null,
                    min_price: item.raw_min_price || item.min_price || "",
                    image_url: item.image_url || item.image || "",
                };
                this.currentItemId = item.id;
            }
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.currentItemId = null;
            this.saveError = "";
            this.formErrors = { name: "", category_id: "" };
        },

        confirmDelete(item) {
            this.itemToDelete = item;
            this.deleteError = "";
            this.showDeleteConfirm = true;
        },

        validateForm() {
            this.formErrors = { name: "", category_id: "" };
            let valid = true;

            if (!this.form.name.trim()) {
                this.formErrors.name = "اسم الصنف مطلوب.";
                valid = false;
            } else if (this.form.name.trim().length < 2) {
                this.formErrors.name = "الاسم يجب أن يكون حرفين على الأقل.";
                valid = false;
            }

            if (!this.form.category_id) {
                this.formErrors.category_id = "يرجى اختيار فئة المنتج.";
                valid = false;
            }

            return valid;
        },

        triggerFileInput() {
            this.$refs.fileInput?.click();
        },

        handleFileUpload(event) {
            const file = event.target.files?.[0];
            if (!file) return;
            this.processFile(file);
        },

        handleFileDrop(event) {
            const file = event.dataTransfer?.files?.[0];
            if (!file) return;
            this.processFile(file);
        },

        processFile(file) {
            if (file.size > 10 * 1024 * 1024) {
                this.saveError = "حجم الصورة كبير جداً (الأقصى 10 ميجابايت).";
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement("canvas");
                    const maxDim = 600;
                    let width = img.width;
                    let height = img.height;
                    if (width > height) {
                        if (width > maxDim) {
                            height = Math.round((height * maxDim) / width);
                            width = maxDim;
                        }
                    } else {
                        if (height > maxDim) {
                            width = Math.round((width * maxDim) / height);
                            height = maxDim;
                        }
                    }
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0, width, height);
                    this.form.image_url = canvas.toDataURL("image/webp", 0.85);
                };
                img.onerror = () => {
                    this.form.image_url = e.target.result;
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeFormImage() {
            this.form.image_url = "";
            if (this.$refs.fileInput) this.$refs.fileInput.value = "";
        },

        getItemImage(item) {
            if (!item) return createSvgPlaceholder("", "");
            if (item.image_url) return item.image_url;
            if (item.image) return item.image;

            const name = item.name || "";
            for (const [key, url] of Object.entries(PRODUCT_THUMBNAILS)) {
                if (name.includes(key)) return url;
            }
            return createSvgPlaceholder(name, item.category);
        },

        handleImageError(event, item) {
            event.target.src = createSvgPlaceholder(item?.name, item?.category);
        },

        getCategoryBadgeStyle(category) {
            const p = getCategoryPalette(category);
            return { background: p.bg, color: p.color, border: `1px solid ${p.border}` };
        },

        formatDate(dateStr) {
            if (!dateStr) return "—";
            try {
                return new Date(dateStr).toLocaleDateString("ar-EG", {
                    year: "numeric", month: "short", day: "numeric",
                });
            } catch { return dateStr; }
        },

        handleLogout() {
            localStorage.removeItem("wafar_token");
            localStorage.removeItem("wafar_user");
            this.$router.push({ name: "AdminLogin" });
        },
    },
};
</script>

<style scoped>
/* ══════════════════════════════════
   ترويسة صفحة المنتجات
══════════════════════════════════ */
.items-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
}

.items-page-title {
    font-size: 22px;
    font-weight: 900;
    margin: 0 0 4px 0;
    color: var(--wc-text-dark);
    display: flex;
    align-items: center;
    gap: 10px;
}

.items-page-subtitle {
    font-size: 13px;
    color: var(--wc-text-muted);
    margin: 0;
}

/* ══════════════════════════════════
   شريط التحكم العلوي والفلاتر ومفتاح العرض
══════════════════════════════════ */
.items-filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    background: var(--wc-white, #ffffff);
    padding: 12px 18px;
    border-radius: 14px;
    border: 1px solid var(--wc-border, #e2e8f0);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    margin-bottom: 20px;
    min-height: 60px;
}

.filter-controls-group {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.toolbar-divider {
    width: 1px;
    height: 28px;
    background: #e2e8f0;
    margin: 0 2px;
    flex-shrink: 0;
}

.view-mode-toggle {
    display: inline-flex;
    align-items: center;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    gap: 2px;
}

.view-toggle-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 10px;
    border-radius: 7px;
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 13px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.15s ease;
}

.view-toggle-btn:hover {
    color: #0f172a;
}

.view-toggle-btn--active {
    background: #ffffff;
    color: var(--wc-green-bright, #15803d);
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
}

.items-search-wrap {
    position: relative;
    width: 300px;
    flex-shrink: 1;
    min-width: 180px;
}

.items-search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--wc-text-muted, #94a3b8);
    pointer-events: none;
}

.items-search-input {
    width: 100%;
    padding: 9px 36px 9px 32px;
    border: 1.5px solid var(--wc-border, #cbd5e1);
    border-radius: 10px;
    background: #f8fafc;
    font-size: 13.5px;
    font-family: inherit;
    color: var(--wc-text-dark, #0f172a);
    outline: none;
    transition: all 0.15s ease;
    box-sizing: border-box;
    direction: rtl;
}

.items-search-input:focus {
    border-color: var(--wc-green-bright, #22c55e);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.10);
}

.search-clear {
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--wc-text-muted);
    cursor: pointer;
    padding: 3px;
    display: flex;
    align-items: center;
    transition: color 0.15s;
}

.search-clear:hover {
    color: #ef4444;
}

.items-filter-select {
    padding: 8px 11px;
    border: 1.5px solid var(--wc-border, #cbd5e1);
    border-radius: 10px;
    background: #f8fafc;
    font-size: 13px;
    font-family: inherit;
    color: var(--wc-text-dark, #0f172a);
    outline: none;
    cursor: pointer;
    min-width: 120px;
    transition: border-color 0.15s, background 0.15s;
    direction: rtl;
    white-space: nowrap;
}

.items-filter-select:focus,
.items-filter-select:hover {
    border-color: var(--wc-green-bright, #22c55e);
    background: #ffffff;
}

.items-filter-select--sm {
    min-width: 100px;
    font-size: 12.5px;
}

.items-count-badge {
    background: #e8f5ea;
    color: #15803d;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 700;
    white-space: nowrap;
    border: 1px solid #bbf7d0;
    flex-shrink: 0;
}

.items-count-badge span {
    font-size: 14px;
    font-weight: 900;
}

/* ══════════════════════════════════
   الجدول والتحسينات البصرية (Table View)
══════════════════════════════════ */
.items-table-wrap {
    background: #ffffff;
    border: 1px solid var(--wc-border, #e2e8f0);
    border-radius: 14px;
    overflow-x: auto;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
}

.items-table {
    width: 100%;
    border-collapse: collapse;
    text-align: right;
    font-size: 14px;
}

.items-table th {
    background: #f8fafc;
    color: #475569;
    padding: 12px 14px;
    font-weight: 700;
    border-bottom: 1px solid var(--wc-border, #e2e8f0);
    white-space: nowrap;
}

.items-table td {
    padding: 12px 14px;
    border-bottom: 1px solid var(--wc-border, #e2e8f0);
    color: var(--wc-text-dark, #0f172a);
    vertical-align: middle;
    transition: background 0.15s ease;
}

.items-table tr:last-child td {
    border-bottom: none;
}

.items-table tr:hover td {
    background: #f8fafc;
}

.tr-selected td {
    background: #f0fdf4 !important;
}

.th-checkbox, .td-checkbox {
    width: 36px;
    text-align: center;
}

.wc-checkbox {
    width: 17px;
    height: 17px;
    accent-color: var(--wc-green-bright, #22c55e);
    cursor: pointer;
    border-radius: 4px;
}

.td-num {
    color: #94a3b8;
    font-size: 13px;
    width: 44px;
}

.td-date {
    color: var(--wc-text-muted, #64748b);
    font-size: 13px;
    white-space: nowrap;
}

.text-muted {
    color: var(--wc-text-muted, #64748b);
}

/* ——— خلية اسم الصنف مع صورة Thumbnail ——— */
.table-item-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.table-item-thumb-wrap {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    border-radius: 8px;
    overflow: hidden;
    background: #f1f5f9;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
}

.table-item-thumb {
    width: 36px;
    height: 36px;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.2s ease;
}

.items-table tr:hover .table-item-thumb {
    transform: scale(1.08);
}

.table-item-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--wc-text-dark, #0f172a);
}

/* ══════════════════════════════════
   عرض البطاقات (Grid / Cards View)
══════════════════════════════════ */
.items-grid-wrap {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 16px;
}

.item-card {
    background: #ffffff;
    border: 1px solid var(--wc-border, #e2e8f0);
    border-radius: 14px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 14px;
    transition: all 0.2s ease;
    position: relative;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}

.item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
    border-color: #cbd5e1;
}

.item-card--selected {
    border-color: var(--wc-green-bright, #22c55e) !important;
    background: #f0fdf4 !important;
    box-shadow: 0 4px 14px rgba(34, 197, 94, 0.12) !important;
}

.item-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}

.item-card__body {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 10px;
}

.item-card__image-wrap {
    width: 64px;
    height: 64px;
    border-radius: 14px;
    overflow: hidden;
    background: #f8fafc;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    display: flex;
    align-items: center;
    justify-content: center;
}

.item-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s ease;
}

.item-card:hover .item-card__image {
    transform: scale(1.08);
}

.item-card__title {
    font-size: 15px;
    font-weight: 800;
    color: var(--wc-text-dark, #0f172a);
    margin: 0;
}

.item-card__meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    margin-top: 4px;
    padding-top: 10px;
    border-top: 1px dashed #e2e8f0;
}

.item-card__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 10px;
    border-top: 1px solid #f1f5f9;
}

.item-card__date {
    font-size: 12px;
    color: #94a3b8;
}

/* ——— شارة الفئة ——— */
.category-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.category-badge--empty {
    background: #f1f5f9;
    color: #94a3b8;
    border: 1px solid #e2e8f0;
}

/* ——— السعر ——— */
.price-cell {
    font-weight: 700;
    font-size: 14px;
    color: #15803d;
    display: inline-flex;
    align-items: baseline;
    gap: 3px;
}

.price-unit {
    font-size: 11px;
    font-weight: 500;
    color: var(--wc-text-muted);
}

/* ——— شارة المتاجر المتوفرة ——— */
.stores-badge-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 11px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.18s ease;
}

.stores-badge-btn--active {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #bfdbfe;
}

.stores-badge-btn--active:hover {
    background: #dbeafe;
    border-color: #93c5fd;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(29, 78, 216, 0.12);
}

.badge-arrow {
    transition: transform 0.15s;
}

.stores-badge-btn:hover .badge-arrow {
    transform: translateY(1px);
}

/* ——— أزرار الإجراءات ——— */
.table-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.icon-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid transparent;
    background: transparent;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.18s ease;
}

.items-table tr:hover .icon-btn--view,
.item-card:hover .icon-btn--view {
    background: #f0fdf4;
    color: #166534;
    border-color: #bbf7d0;
}

.items-table tr:hover .icon-btn--edit,
.item-card:hover .icon-btn--edit {
    background: #eff6ff;
    color: #2563eb;
    border-color: #bfdbfe;
}

.items-table tr:hover .icon-btn--delete,
.item-card:hover .icon-btn--delete {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
}

.icon-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
}

/* ══════════════════════════════════
   شريط الإجراءات الجماعية
══════════════════════════════════ */
.bulk-actions-bar {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 900;
    background: #0f172a;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    display: flex;
    align-items: center;
    gap: 20px;
    direction: rtl;
}

.bulk-actions-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.bulk-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 700;
}

.bulk-count-badge {
    background: var(--wc-green-bright, #22c55e);
    color: #ffffff;
    padding: 2px 9px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 900;
}

.bulk-btns {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-bulk-delete {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 15px;
    background: #ef4444;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: background 0.15s;
}

.btn-bulk-delete:hover {
    background: #dc2626;
}

.btn-bulk-clear {
    background: transparent;
    color: #94a3b8;
    border: 1px solid #334155;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-bulk-clear:hover {
    background: #1e293b;
    color: #ffffff;
}

.slide-up-enter-active, .slide-up-leave-active {
    transition: all 0.25s ease;
}
.slide-up-enter-from, .slide-up-leave-to {
    opacity: 0;
    transform: translate(-50%, 20px);
}

/* ══════════════════════════════════
   مودال تفاصيل المتاجر
══════════════════════════════════ */
.modal--stores {
    max-width: 520px;
}

.stores-modal-item-card {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 12px 16px;
    border-radius: 12px;
}

.stores-modal-thumb {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    object-fit: cover;
}

.stores-modal-item-title {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 800;
    color: var(--wc-text-dark, #0f172a);
}

.empty-stores-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 16px;
    text-align: center;
    gap: 10px;
    color: #64748b;
    background: #f8fafc;
    border-radius: 12px;
}

.stores-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-height: 280px;
    overflow-y: auto;
}

.store-price-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    transition: border-color 0.15s;
}

.store-price-row:hover {
    border-color: var(--wc-green-bright, #22c55e);
}

.store-meta {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.store-name-text {
    font-weight: 700;
    font-size: 14px;
    color: var(--wc-text-dark, #0f172a);
}

.store-time-text {
    font-size: 11.5px;
    color: #94a3b8;
}

.store-price-badge {
    background: #e8f5ea;
    color: #15803d;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 800;
    font-size: 14px;
    display: flex;
    align-items: baseline;
    gap: 4px;
}

.store-price-unit {
    font-size: 11px;
    font-weight: 600;
}

/* ══════════════════════════════════
   مودال التعديل والإضافة المحسّن بالكامل
══════════════════════════════════ */
.modal--edit-item {
    max-width: 500px;
}

.image-upload-wrapper {
    width: 100%;
}

.hidden-file-input {
    display: none;
}

.image-dropzone {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 22px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    background: #f8fafc;
    transition: all 0.2s ease;
}

.image-dropzone:hover {
    border-color: var(--wc-green-bright, #22c55e);
    background: #f0fdf4;
}

.dropzone-icon {
    color: #64748b;
}

.image-dropzone:hover .dropzone-icon {
    color: var(--wc-green-bright, #22c55e);
}

.dropzone-text {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--wc-text-dark, #0f172a);
}

.dropzone-subtext {
    font-size: 11px;
    color: #94a3b8;
}

.image-preview-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 10px 14px;
    border-radius: 12px;
}

.image-preview-img {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    object-fit: cover;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.image-preview-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-img-action {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-img-change {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.btn-img-change:hover {
    background: #dbeafe;
}

.btn-img-remove {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.btn-img-remove:hover {
    background: #fee2e2;
}

.form-select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2064748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
    background-repeat: no-repeat;
    background-position: left 12px center;
    padding-left: 36px;
}

.price-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.price-input {
    padding-left: 60px !important;
}

.price-unit-addon {
    position: absolute;
    left: 12px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    pointer-events: none;
}

/* ——— التنسيق المتوازن لأزرار المودال ——— */
.modal__footer--balanced {
    display: flex;
    flex-direction: row-reverse;
    gap: 12px;
    padding: 16px 22px;
    border-top: 1px solid var(--wc-border, #e2e8f0);
    background: #f8fafc;
}

.btn-save--full, .btn-cancel--full {
    flex: 1;
    justify-content: center;
    text-align: center;
}

/* ══════════════════════════════════
   Pagination
══════════════════════════════════ */
.pagination-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 14px 20px;
    border-top: 1px solid var(--wc-border, #e2e8f0);
    background: #f8fafc;
    flex-wrap: wrap;
    border-radius: 0 0 14px 14px;
}

.page-btn {
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    background: #ffffff;
    border: 1.5px solid var(--wc-border, #cbd5e1);
    border-radius: 8px;
    color: var(--wc-text-dark, #0f172a);
    font-size: 13px;
    font-family: inherit;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
}

.page-btn:hover:not(:disabled) {
    border-color: var(--wc-green-bright, #22c55e);
    color: var(--wc-green-bright, #22c55e);
}

.page-btn--active {
    background: var(--wc-green-bright, #22c55e) !important;
    border-color: var(--wc-green-bright, #22c55e) !important;
    color: #ffffff !important;
    font-weight: 700;
}

.page-btn:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}

.pagination-info {
    font-size: 13px;
    color: var(--wc-text-muted, #64748b);
    white-space: nowrap;
    margin-right: 8px;
}

/* ══════════════════════════════════
   Loading / Error / Empty
══════════════════════════════════ */
.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    padding: 80px;
    color: var(--wc-text-muted);
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e8f5ea;
    border-top-color: var(--wc-green-bright, #22c55e);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

.error-banner {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
    padding: 14px 18px;
    border-radius: 10px;
    font-size: 14px;
}

.retry-btn {
    margin-right: auto;
    padding: 5px 14px;
    background: #ffffff;
    border: 1.5px solid #fca5a5;
    color: #b91c1c;
    border-radius: 7px;
    cursor: pointer;
    font-size: 13px;
    font-family: inherit;
    transition: background 0.15s;
}

.retry-btn:hover { background: #fee2e2; }

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 24px;
    text-align: center;
    gap: 12px;
    color: var(--wc-text-muted);
}

.empty-icon {
    font-size: 52px;
    margin-bottom: 8px;
    opacity: 0.6;
}

/* ══════════════════════════════════
   زر الإضافة
══════════════════════════════════ */
.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    background: var(--wc-green-bright, #22c55e);
    color: #ffffff;
    font-weight: 700;
    font-size: 14px;
    font-family: inherit;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
}

.btn-add:hover {
    background: #16a34a;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(34, 197, 94, 0.35);
}

/* ══════════════════════════════════
   Modal Base
══════════════════════════════════ */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
    backdrop-filter: blur(3px);
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

.modal {
    background: #ffffff;
    border: 1px solid var(--wc-border, #e2e8f0);
    border-radius: 16px;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.18);
    animation: slideUp 0.22s ease;
    overflow: hidden;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0);    }
}

.modal__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1px solid var(--wc-border, #e2e8f0);
    background: #f8fafc;
}

.modal__title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 800;
    color: var(--wc-text-dark, #0f172a);
    margin: 0;
}

.modal__close {
    width: 32px;
    height: 32px;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    color: #64748b;
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.18s ease;
    line-height: 1;
}

.modal__close:hover {
    background: #e2e8f0;
    color: #0f172a;
    border-color: #94a3b8;
}

.modal__body {
    padding: 22px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.modal__footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 22px;
    border-top: 1px solid var(--wc-border, #e2e8f0);
    background: #f8fafc;
}

/* ——— Form Inputs ——— */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-label {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--wc-text-dark, #0f172a);
    display: flex;
    align-items: center;
    gap: 6px;
}

.required-star { color: #ef4444; }

.form-hint {
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
    background: #f1f5f9;
    padding: 2px 7px;
    border-radius: 20px;
}

.form-input {
    padding: 10px 14px;
    background: #ffffff;
    border: 1.5px solid var(--wc-border, #cbd5e1);
    border-radius: 9px;
    color: var(--wc-text-dark, #0f172a);
    font-size: 14px;
    font-family: inherit;
    direction: rtl;
    transition: border-color 0.2s, box-shadow 0.2s;
    width: 100%;
    box-sizing: border-box;
    outline: none;
}

.form-input:focus {
    border-color: var(--wc-green-bright, #22c55e);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.12);
}
.form-input--error { border-color: #ef4444 !important; }

.field-error {
    color: #dc2626;
    font-size: 12px;
}

.error-alert {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 13px;
}

/* ——— Modal Buttons ——— */
.btn-cancel {
    padding: 10px 18px;
    background: #ffffff;
    border: 1.5px solid var(--wc-border, #cbd5e1);
    color: var(--wc-text-dark, #0f172a);
    border-radius: 9px;
    font-size: 14px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-cancel:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
}

.btn-save {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    background: var(--wc-green-bright, #22c55e);
    color: #ffffff;
    font-weight: 700;
    font-size: 14px;
    font-family: inherit;
    border: none;
    border-radius: 9px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-save:hover:not(:disabled) { background: #16a34a; }
.btn-save:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    background: #dc2626;
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    font-family: inherit;
    border: none;
    border-radius: 9px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-delete:hover:not(:disabled) { background: #b91c1c; }
.btn-delete:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

.btn-spinner--white { border-color: rgba(255,255,255,0.3); border-top-color: #fff; }

.delete-confirm-text {
    color: var(--wc-text-dark, #0f172a);
    font-size: 15px;
    margin: 0;
    line-height: 1.6;
}

.delete-warning-row {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #92400e;
    font-size: 13px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    padding: 10px 14px;
    border-radius: 8px;
    margin: 0;
}

/* ══════════════════════════════════
   Quick View Modal Styles
══════════════════════════════════ */
.modal--quick-view {
    max-width: 640px;
    max-height: 88vh;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.qv-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid var(--wc-border, #e2e8f0);
    background: #f8fafc;
    position: sticky;
    top: 0;
    z-index: 2;
}

.qv-modal-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 800;
    color: var(--wc-text-dark, #0f172a);
    margin: 0;
}

/* الهيرو: صورة + بيانات المنتج */
.qv-hero {
    display: flex;
    gap: 20px;
    padding: 20px 22px;
    background: #ffffff;
}

.qv-image-wrapper {
    position: relative;
    flex-shrink: 0;
    width: 160px;
    height: 160px;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(0,0,0,0.07);
}

.qv-hero-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.qv-image-wrapper:hover .qv-hero-image {
    transform: scale(1.05);
}

.qv-status-chip {
    position: absolute;
    top: 8px;
    right: 8px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
}

.qv-status-chip--active {
    background: #dcfce7;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.qv-status-chip--inactive {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.qv-product-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.qv-product-name {
    font-size: 18px;
    font-weight: 900;
    color: #0f172a;
    margin: 0;
    line-height: 1.3;
}

.qv-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.qv-meta-item {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.qv-meta-label {
    font-size: 11.5px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.qv-meta-value {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
}

.qv-guide-price {
    font-size: 16px;
    font-weight: 900;
    color: var(--wc-green-bright, #16a34a);
}

.qv-guide-price small {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    margin-right: 2px;
}

.qv-no-data {
    font-size: 13px;
    color: #cbd5e1;
}

.qv-stores-count-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e8f5ea;
    color: #15803d;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    border: 1px solid #bbf7d0;
    align-self: flex-start;
}

.qv-divider {
    height: 1px;
    background: #f1f5f9;
    margin: 0 22px;
}

/* قسم جدول المتاجر */
.qv-stores-section {
    padding: 16px 22px 8px;
}

.qv-stores-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.qv-stores-title {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}

.qv-stores-count {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    background: #f1f5f9;
    padding: 2px 10px;
    border-radius: 20px;
}

.qv-empty-stores {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 32px;
    color: #94a3b8;
    text-align: center;
}

.qv-empty-stores p {
    margin: 0;
    font-size: 14px;
}

.qv-stores-table-wrapper {
    overflow-x: auto;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.qv-stores-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
    text-align: right;
}

.qv-stores-table thead th {
    padding: 10px 14px;
    background: #f8fafc;
    font-weight: 800;
    color: #475569;
    font-size: 12.5px;
    border-bottom: 1px solid #e2e8f0;
}

.qv-store-row {
    border-bottom: 1px solid #f8fafc;
    transition: background 0.15s ease;
}

.qv-store-row:last-child { border-bottom: none; }

.qv-store-row:hover {
    background: #f8fffe;
}

.qv-store-row td {
    padding: 10px 14px;
    vertical-align: middle;
}

.qv-store-name-cell { min-width: 180px; }

.qv-store-name-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qv-store-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--wc-green-bright, #22c55e);
    flex-shrink: 0;
}

.qv-store-link {
    display: block;
    font-weight: 800;
    color: #0f172a;
    text-decoration: none;
    transition: color 0.15s;
    font-size: 13.5px;
}

.qv-store-link:hover {
    color: var(--wc-green-bright, #16a34a);
    text-decoration: underline;
}

.qv-store-region {
    display: block;
    font-size: 11.5px;
    color: #94a3b8;
    font-weight: 600;
    margin-top: 1px;
}

.qv-price-cell { white-space: nowrap; }

.qv-price-value {
    font-size: 15px;
    font-weight: 900;
    color: var(--wc-green-bright, #16a34a);
}

.qv-price-value small {
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    margin-right: 2px;
}

.qv-availability-badge {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 800;
    white-space: nowrap;
}

.qv-availability-badge--in {
    background: #dcfce7;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.qv-availability-badge--out {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.qv-action-cell { width: 36px; text-align: center; }

.qv-goto-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 7px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    text-decoration: none;
    transition: all 0.15s ease;
}

.qv-goto-btn:hover {
    background: #e8f5ea;
    border-color: #86efac;
    color: #15803d;
}

/* ══════════════════════════════════
   Responsive
══════════════════════════════════ */
@media (max-width: 768px) {
    .items-page-header   { flex-direction: column; }
    .items-filter-bar    { flex-direction: column; align-items: stretch; }
    .items-search-wrap   { width: 100%; }
    .filter-controls-group { justify-content: space-between; }
    .items-table th:nth-child(6),
    .items-table td:nth-child(6),
    .items-table th:nth-child(7),
    .items-table td:nth-child(7) { display: none; }
    .qv-hero { flex-direction: column; }
    .qv-image-wrapper { width: 100%; height: 200px; }
    .qv-meta-grid { grid-template-columns: 1fr; }
}
</style>

<template>
    <div class="dashboard-page">
        <!-- الشريط الجانبي -->
        <AdminSidebar />

        <div class="dashboard-main">
            <!-- الهيدر العلوي -->
            <AdminHeader @logout="handleLogout" />

            <div class="dashboard-content">

                <!-- تنبيه التنبيهات المؤقتة (Toast Notification) -->
                <transition name="fade-slide">
                    <div v-if="toastMessage" class="toast-notification" :class="`toast-notification--${toastType}`">
                        <CheckCircleIcon v-if="toastType === 'success'" :size="18" />
                        <AlertCircleIcon v-else :size="18" />
                        <span>{{ toastMessage }}</span>
                        <button class="toast-close" @click="toastMessage = ''">
                            <XIcon :size="14" />
                        </button>
                    </div>
                </transition>

                <!-- 1. Header Section (ترويسة الصفحة) -->
                <div class="categories-page-header">
                    <div class="categories-page-header__text">
                        <h1 class="categories-page-title">
                            <GridIcon :size="24" style="color: var(--wc-green-bright)" />
                            إدارة التصنيفات
                        </h1>
                        <p class="categories-page-subtitle">
                            عرض وإدارة جميع أصناف المنتجات المسجلة والتحكم بسلسلتها وحالتها
                        </p>
                    </div>
                    <button class="btn-add" @click="openModal('create')" id="btn-add-category">
                        <PlusIcon :size="18" />
                        إضافة تصنيف جديد
                    </button>
                </div>

                <!-- 2. Toolbar Section (شريط البحث والفلترة) -->
                <div class="categories-filter-bar">
                    <!-- البحث الفوري -->
                    <div class="categories-search-wrap">
                        <SearchIcon :size="16" class="categories-search-icon" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="ابحث باسم الصنف أو الفئة..."
                            class="categories-search-input"
                            id="categories-search-input"
                        />
                        <button v-if="searchQuery" class="search-clear" @click="searchQuery = ''" title="مسح البحث">
                            <XIcon :size="14" />
                        </button>
                    </div>

                    <!-- الفلاتر والترتيب والتحكم بالعرض -->
                    <div class="filter-controls-group">
                        <!-- عداد التصنيفات -->
                        <div class="categories-count-badge">
                            <span>{{ filteredCategories.length }}</span> تصنيفات
                        </div>

                        <!-- فلتر الحالة -->
                        <select v-model="statusFilter" class="categories-filter-select" id="categories-status-filter">
                            <option value="all">كل الفئات</option>
                            <option value="active">نشط فقط</option>
                            <option value="inactive">غير نشط فقط</option>
                        </select>

                        <!-- الترتيب -->
                        <select v-model="sortBy" class="categories-filter-select" id="categories-sort-select">
                            <option value="latest">الأحدث</option>
                            <option value="oldest">الأقدم</option>
                            <option value="products_desc">أعلى منتجات</option>
                            <option value="products_asc">أقل منتجات</option>
                            <option value="name_asc">الاسم (أ - ي)</option>
                            <option value="name_desc">الاسم (ي - أ)</option>
                        </select>

                        <div class="toolbar-divider"></div>

                        <!-- أزرار تبديل وضع العرض (جدول / بطاقات) -->
                        <div class="view-mode-toggle">
                            <button
                                class="view-toggle-btn"
                                :class="{ 'view-toggle-btn--active': viewMode === 'grid' }"
                                @click="setViewMode('grid')"
                                title="عرض بطاقات"
                                id="btn-view-grid"
                            >
                                <LayoutGridIcon :size="15" />
                            </button>
                            <button
                                class="view-toggle-btn"
                                :class="{ 'view-toggle-btn--active': viewMode === 'table' }"
                                @click="setViewMode('table')"
                                title="عرض جدول"
                                id="btn-view-table"
                            >
                                <ListIcon :size="15" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 3. Content Section (منطقة العرض الرئيسية) -->

                <!-- حالة عدم وجود نتائج -->
                <div v-if="filteredCategories.length === 0" class="empty-state">
                    <div class="empty-icon">
                        <FolderIcon :size="48" style="color: var(--wc-text-muted)" />
                    </div>
                    <h3>لا توجد تصنيفات مطابقة!</h3>
                    <p>لم يتم العثور على أي تصنيف يطابق خيارات البحث والفلترة الحالية.</p>
                    <button class="btn-secondary" @click="resetFilters">إعادة ضبط الفلاتر</button>
                </div>

                <!-- (أ) Grid View - عرض البطاقات -->
                <div v-else-if="viewMode === 'grid'" class="categories-grid">
                    <div
                        v-for="cat in paginatedCategories"
                        :key="cat.id"
                        class="category-card"
                    >
                        <!-- الجزء العلوي: الوسام والحالة -->
                        <div class="category-card__header">
                            <span
                                class="status-badge"
                                :class="cat.status === 'active' ? 'status-badge--active' : 'status-badge--inactive'"
                            >
                                {{ cat.status === 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>

                        <!-- منتصف البطاقة: الأيقونة والاسم والأعداد -->
                        <div class="category-card__body">
                            <div class="category-card__icon-wrapper">
                                <span v-if="isEmoji(cat.icon)" class="category-emoji">{{ cat.icon }}</span>
                                <img v-else-if="cat.icon" :src="cat.icon" :alt="cat.name" class="category-img" @error="handleImgError" />
                                <span v-else class="category-emoji">📦</span>
                            </div>

                            <h3 class="category-card__title">{{ cat.name }}</h3>

                            <div class="category-card__meta">
                                <span class="category-card__count">{{ cat.count }} صنف</span>
                            </div>
                        </div>

                        <!-- الجزء السفلي: أزرار الإجراءات (معاينة، تعديل، حذف) -->
                        <div class="category-card__actions">
                            <button
                                class="card-act-btn card-act-btn--delete"
                                @click="confirmDelete(cat)"
                                title="حذف التصنيف"
                            >
                                <Trash2Icon :size="15" />
                            </button>
                            <button
                                class="card-act-btn card-act-btn--edit"
                                @click="openModal('edit', cat)"
                                title="تعديل التصنيف"
                            >
                                <Edit3Icon :size="15" />
                            </button>
                            <button
                                class="card-act-btn card-act-btn--view"
                                @click="openPreviewModal(cat)"
                                title="معاينة التفاصيل"
                            >
                                <EyeIcon :size="15" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- (ب) Table View - عرض الجدول -->
                <div v-else-if="viewMode === 'table'" class="categories-table-container">
                    <table class="categories-table">
                        <thead>
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>التصنيف</th>
                                <th>الوصف</th>
                                <th>عدد المنتجات</th>
                                <th>الحالة</th>
                                <th style="width: 140px; text-align: center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(cat, index) in paginatedCategories" :key="cat.id">
                                <td class="td-id">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                <td class="td-category">
                                    <div class="table-cat-info">
                                        <div class="table-cat-icon">
                                            <span v-if="isEmoji(cat.icon)">{{ cat.icon }}</span>
                                            <img v-else-if="cat.icon" :src="cat.icon" :alt="cat.name" @error="handleImgError" />
                                            <span v-else>📦</span>
                                        </div>
                                        <span class="table-cat-name">{{ cat.name }}</span>
                                    </div>
                                </td>
                                <td class="td-desc">
                                    {{ cat.description || 'لا يوجد وصف مضاف' }}
                                </td>
                                <td class="td-count">
                                    <span class="count-pill">{{ cat.count }} صنف</span>
                                </td>
                                <td class="td-status">
                                    <span
                                        class="status-badge"
                                        :class="cat.status === 'active' ? 'status-badge--active' : 'status-badge--inactive'"
                                    >
                                        {{ cat.status === 'active' ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td class="td-actions">
                                    <div class="table-actions-group">
                                        <button
                                            class="table-act-btn table-act-btn--view"
                                            @click="openPreviewModal(cat)"
                                            title="معاينة"
                                        >
                                            <EyeIcon :size="15" />
                                        </button>
                                        <button
                                            class="table-act-btn table-act-btn--edit"
                                            @click="openModal('edit', cat)"
                                            title="تعديل"
                                        >
                                            <Edit3Icon :size="15" />
                                        </button>
                                        <button
                                            class="table-act-btn table-act-btn--delete"
                                            @click="confirmDelete(cat)"
                                            title="حذف"
                                        >
                                            <Trash2Icon :size="15" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم والـ Pagination Footer -->
                <div class="categories-pagination-bar" v-if="filteredCategories.length > 0">
                    <div class="pagination-info">
                        عرض {{ paginationStart }} - {{ paginationEnd }} من {{ filteredCategories.length }}
                    </div>
                    <div class="pagination-controls" v-if="totalPages > 1">
                        <button
                            class="page-btn"
                            :disabled="currentPage === 1"
                            @click="currentPage--"
                        >
                            &gt;
                        </button>
                        <button
                            v-for="p in totalPages"
                            :key="p"
                            class="page-btn"
                            :class="{ 'page-btn--active': p === currentPage }"
                            @click="currentPage = p"
                        >
                            {{ p }}
                        </button>
                        <button
                            class="page-btn"
                            :disabled="currentPage === totalPages"
                            @click="currentPage++"
                        >
                            &lt;
                        </button>
                    </div>
                </div>

                <!-- 4. Modal Section (نافذة الإضافة والتعديل CRUD Modal) -->
                <transition name="modal">
                    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
                        <div class="modal-card">
                            <div class="modal-header">
                                <h3 class="modal-title">
                                    <FolderPlusIcon v-if="modalMode === 'create'" :size="20" style="color: var(--wc-green-bright)" />
                                    <Edit3Icon v-else :size="20" style="color: var(--wc-green-bright)" />
                                    {{ modalMode === 'create' ? 'إضافة تصنيف جديد' : 'تعديل بيانات التصنيف' }}
                                </h3>
                                <button class="modal-close-btn" @click="closeModal">
                                    <XIcon :size="18" />
                                </button>
                            </div>

                            <form @submit.prevent="saveCategory" class="modal-form">
                                <!-- اسم التصنيف -->
                                <div class="form-group">
                                    <label class="form-label">
                                        اسم التصنيف <span class="required-star">*</span>
                                    </label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="form-input"
                                        :class="{ 'form-input--error': formErrors.name }"
                                        placeholder="مثال: خضراوات، زيوت ودهون..."
                                    />
                                    <span v-if="formErrors.name" class="form-error-msg">{{ formErrors.name }}</span>
                                </div>

                                <!-- أيقونة أو صورة التصنيف -->
                                <div class="form-group">
                                    <label class="form-label">
                                        صورة أو أيقونة التصنيف
                                        <span class="form-hint">اختياري</span>
                                    </label>

                                    <!-- منطقة رفع ومعاينة الصورة -->
                                    <div class="cat-image-upload-wrapper">
                                        <!-- معاينة الصورة المحددة / المرفوعة -->
                                        <div v-if="form.icon && !isEmoji(form.icon)" class="cat-image-preview-card">
                                            <img :src="form.icon" alt="معاينة التصنيف" class="cat-image-preview-img" @error="handleImgError" />
                                            <div class="cat-image-preview-actions">
                                                <button type="button" class="btn-img-action btn-img-change" @click="triggerFileInput" title="تغيير الصورة">
                                                    <UploadIcon :size="14" /> تغيير الصورة
                                                </button>
                                                <button type="button" class="btn-img-action btn-img-remove" @click="removeFormImage" title="إزالة الصورة">
                                                    <Trash2Icon :size="14" /> إزالة
                                                </button>
                                            </div>
                                        </div>

                                        <!-- منطقة الرفع / السحب والإسقاط -->
                                        <div
                                            v-else
                                            class="cat-image-dropzone"
                                            @click="triggerFileInput"
                                            @dragover.prevent
                                            @drop.prevent="handleFileDrop"
                                        >
                                            <UploadIcon :size="24" class="dropzone-icon" />
                                            <span class="dropzone-text">اختر صورة من جهازك أو اسحبها هنا</span>
                                            <span class="dropzone-subtext">PNG, JPG, WEBP (بحد أقصى 5 ميجابايت)</span>
                                        </div>

                                        <input
                                            type="file"
                                            ref="fileInputRef"
                                            class="hidden-file-input"
                                            accept="image/*"
                                            @change="handleFileUpload"
                                            style="display: none"
                                        />
                                    </div>

                                    <!-- إدخال رابط أو إيموجي يدوي -->
                                    <div class="icon-picker-input-wrap" style="margin-top: 10px;">
                                        <input
                                            v-model="form.icon"
                                            type="text"
                                            class="form-input"
                                            placeholder="أو أدخل رابط صورة مباشر أو إيموجي (مثل 🥦)..."
                                        />
                                        <div class="icon-preview-badge">
                                            <span v-if="isEmoji(form.icon)">{{ form.icon }}</span>
                                            <img v-else-if="form.icon" :src="form.icon" alt="معاينة" @error="handleImgError" />
                                            <span v-else>📦</span>
                                        </div>
                                    </div>

                                    <!-- قائمة صور مقترحة سريعة -->
                                    <div class="quick-icons-palette">
                                        <span class="palette-title">صور جاهزة:</span>
                                        <button
                                            v-for="imgObj in presetImages"
                                            :key="imgObj.label"
                                            type="button"
                                            class="palette-btn"
                                            :class="{ 'palette-btn--selected': form.icon === imgObj.url }"
                                            @click="form.icon = imgObj.url"
                                            :title="imgObj.label"
                                        >
                                            <img :src="imgObj.url" :alt="imgObj.label" class="palette-thumb" />
                                        </button>
                                    </div>
                                </div>

                                <!-- عدد المنتجات والحالة -->
                                <div class="form-row">
                                    <div class="form-group flex-1">
                                        <label class="form-label">عدد المنتجات المرتبطة</label>
                                        <input
                                            v-model.number="form.count"
                                            type="number"
                                            min="0"
                                            class="form-input"
                                            placeholder="0"
                                        />
                                    </div>

                                    <div class="form-group flex-1">
                                        <label class="form-label">حالة التصنيف</label>
                                        <select v-model="form.status" class="form-select">
                                            <option value="active">نشط</option>
                                            <option value="inactive">غير نشط</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- الوصف -->
                                <div class="form-group">
                                    <label class="form-label">وصف مختصر (اختياري)</label>
                                    <textarea
                                        v-model="form.description"
                                        rows="3"
                                        class="form-textarea"
                                        placeholder="اكتب وصفاً قصيراً لأصناف هذا التصنيف..."
                                    ></textarea>
                                </div>

                                <!-- أزرار الحفظ والإلغاء -->
                                <div class="modal-footer">
                                    <button type="button" class="btn-secondary" @click="closeModal">إلغاء</button>
                                    <button type="submit" class="btn-primary">
                                        <CheckIcon :size="16" />
                                        {{ modalMode === 'create' ? 'إضافة التصنيف' : 'حفظ التعديلات' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </transition>

                <!-- 5. Delete Confirm Modal (نافذة تأكيد الحذف) -->
                <transition name="modal">
                    <div v-if="showDeleteConfirm" class="modal-overlay" @click.self="showDeleteConfirm = false">
                        <div class="modal-card modal-card--sm">
                            <div class="delete-modal-content">
                                <div class="delete-icon-circle">
                                    <Trash2Icon :size="28" style="color: var(--wc-danger)" />
                                </div>
                                <h3>تأكيد حذف التصنيف</h3>
                                <p>
                                    هل أنت أصلًا متأكد من حذف التصنيف
                                    <strong>"{{ categoryToDelete?.name }}"</strong>؟
                                </p>
                                <span class="delete-warning-subtext">سيتم إزالة هذا التصنيف من القائمة المحلية فوراً.</span>

                                <div class="modal-footer justify-center">
                                    <button class="btn-secondary" @click="showDeleteConfirm = false">إلغاء</button>
                                    <button class="btn-danger" @click="deleteCategory">
                                        تأكيد الحذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>

                <!-- 6. Preview Modal (نافذة المعاينة والتفاصيل) -->
                <transition name="modal">
                    <div v-if="showPreviewModal" class="modal-overlay" @click.self="showPreviewModal = false">
                        <div class="modal-card">
                            <div class="modal-header">
                                <h3 class="modal-title">
                                    <EyeIcon :size="20" style="color: var(--wc-green-bright)" />
                                    تفاصيل التصنيف: {{ previewCategory?.name }}
                                </h3>
                                <button class="modal-close-btn" @click="showPreviewModal = false">
                                    <XIcon :size="18" />
                                </button>
                            </div>

                            <div class="preview-modal-body">
                                <div class="preview-hero">
                                    <div class="preview-icon-box">
                                        <span v-if="isEmoji(previewCategory?.icon)" class="preview-emoji">{{ previewCategory?.icon }}</span>
                                        <img v-else-if="previewCategory?.icon" :src="previewCategory?.icon" :alt="previewCategory?.name" @error="handleImgError" />
                                        <span v-else class="preview-emoji">📦</span>
                                    </div>
                                    <div class="preview-hero-info">
                                        <h2>{{ previewCategory?.name }}</h2>
                                        <div class="preview-badges">
                                            <span
                                                class="status-badge"
                                                :class="previewCategory?.status === 'active' ? 'status-badge--active' : 'status-badge--inactive'"
                                            >
                                                {{ previewCategory?.status === 'active' ? 'نشط' : 'غير نشط' }}
                                            </span>
                                            <span class="count-pill">{{ previewCategory?.count }} صنف مسجل</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="preview-desc-box">
                                    <h4>الوصف:</h4>
                                    <p>{{ previewCategory?.description || 'لا يوجد وصف متاح لهذا التصنيف حالياً.' }}</p>
                                </div>

                                <!-- عينة المنتجات التابعة لهذا التصنيف -->
                                <div class="preview-products-section">
                                    <h4>عينة من المنتجات في هذا التصنيف:</h4>
                                    <div class="mock-products-list" v-if="previewCategory?.items && previewCategory.items.length > 0">
                                        <div v-for="item in previewCategory.items.slice(0, 6)" :key="item.id || item.name" class="mock-product-item">
                                            <span class="mock-dot"></span>
                                            <span>{{ item.name }}</span>
                                        </div>
                                    </div>
                                    <div v-else-if="previewCategory?.count > 0" class="mock-products-list">
                                        <div v-for="n in Math.min(previewCategory.count, 4)" :key="n" class="mock-product-item">
                                            <span class="mock-dot"></span>
                                            <span>صنف مسجل رقم {{ n }}</span>
                                        </div>
                                    </div>
                                    <p v-else style="font-size: 0.85rem; color: #94a3b8; padding: 6px 0;">لا توجد منتجات مسجلة في هذا التصنيف حالياً.</p>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn-secondary" @click="showPreviewModal = false">إغلاق</button>
                                <button class="btn-primary" @click="openModal('edit', previewCategory); showPreviewModal = false;">
                                    <Edit3Icon :size="16" />
                                    تعديل هذا التصنيف
                                </button>
                            </div>
                        </div>
                    </div>
                </transition>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from "vue";
import { useRouter } from "vue-router";
import apiClient from "../api/axios.js";
import AdminSidebar from "../components/AdminSidebar.vue";
import AdminHeader from "../components/AdminHeader.vue";

import {
    LayoutGrid as GridIcon,
    LayoutGrid as LayoutGridIcon,
    List as ListIcon,
    Plus as PlusIcon,
    Search as SearchIcon,
    X as XIcon,
    Folder as FolderIcon,
    FolderPlus as FolderPlusIcon,
    Trash2 as Trash2Icon,
    Edit3 as Edit3Icon,
    Eye as EyeIcon,
    CheckCircle as CheckCircleIcon,
    AlertCircle as AlertCircleIcon,
    Check as CheckIcon,
    Upload as UploadIcon,
} from "@lucide/vue";

const router = useRouter();

// ==========================================
// 1. البيانات القادمة من قاعدة البيانات (Backend Data)
// ==========================================
const categories = ref([]);
const isLoading = ref(false);
const isSubmitting = ref(false);
const isDeleting = ref(false);

const mapCategory = (cat) => ({
    id: cat.id,
    name: cat.name,
    icon: cat.image || "📦",
    count: cat.items_count !== undefined ? cat.items_count : (cat.items ? cat.items.length : 0),
    status: (cat.is_active === 1 || cat.is_active === true || cat.is_active === "1") ? "active" : "inactive",
    description: cat.description || "",
    is_active: cat.is_active,
    slug: cat.slug,
    items: cat.items || []
});

const fetchCategories = async () => {
    isLoading.value = true;
    try {
        const response = await apiClient.get("/categories");
        const raw = Array.isArray(response.data) ? response.data : (response.data.categories || []);
        categories.value = raw.map(mapCategory);
    } catch (err) {
        console.error("Failed to fetch categories:", err);
        showToast("فشل في جلب التصنيفات من الخادم", "danger");
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchCategories();
});

// قائمة الصور السريعة للاختيار
const presetImages = [
    { label: "زيوت", url: "https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=300&auto=format&fit=crop&q=80" },
    { label: "حبوب", url: "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&auto=format&fit=crop&q=80" },
    { label: "فواكه", url: "https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=300&auto=format&fit=crop&q=80" },
    { label: "خضراوات", url: "https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&auto=format&fit=crop&q=80" },
    { label: "مشروبات", url: "https://images.unsplash.com/photo-1527661591475-527312dd65f5?w=300&auto=format&fit=crop&q=80" },
    { label: "ألبان", url: "https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=300&auto=format&fit=crop&q=80" },
    { label: "لحوم", url: "https://images.unsplash.com/photo-1588168333986-5078d3ae3976?w=500&auto=format&fit=crop&q=80" },
    { label: "مخبوزات", url: "https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300&auto=format&fit=crop&q=80" },
];

// ==========================================
// 2. حالات التحكم والفلترة
// ==========================================
const searchQuery = ref("");
const statusFilter = ref("all");
const sortBy = ref("latest");
const viewMode = ref(localStorage.getItem("wafar_categories_view_mode") || "grid");

// الترقيم
const currentPage = ref(1);
const perPage = ref(8);

// التنبيهات
const toastMessage = ref("");
const toastType = ref("success");

const showToast = (msg, type = "success") => {
    toastMessage.value = msg;
    toastType.value = type;
    setTimeout(() => {
        if (toastMessage.value === msg) toastMessage.value = "";
    }, 3500);
};

// ==========================================
// 3. البحث والفلترة والترتيب (Computed)
// ==========================================
const filteredCategories = computed(() => {
    let result = [...categories.value];

    // بحث باسم التصنيف أو الوصف
    if (searchQuery.value.trim()) {
        const q = searchQuery.value.trim().toLowerCase();
        result = result.filter(
            (c) => c.name.toLowerCase().includes(q) || (c.description && c.description.toLowerCase().includes(q))
        );
    }

    // فلترة بالحالة
    if (statusFilter.value !== "all") {
        result = result.filter((c) => c.status === statusFilter.value);
    }

    // الترتيب
    if (sortBy.value === "latest") {
        result.sort((a, b) => b.id - a.id);
    } else if (sortBy.value === "oldest") {
        result.sort((a, b) => a.id - b.id);
    } else if (sortBy.value === "products_desc") {
        result.sort((a, b) => b.count - a.count);
    } else if (sortBy.value === "products_asc") {
        result.sort((a, b) => a.count - b.count);
    } else if (sortBy.value === "name_asc") {
        result.sort((a, b) => a.name.localeCompare(b.name, "ar"));
    } else if (sortBy.value === "name_desc") {
        result.sort((a, b) => b.name.localeCompare(a.name, "ar"));
    }

    return result;
});

// الترقيم المحسوب
const totalPages = computed(() => Math.ceil(filteredCategories.value.length / perPage.value) || 1);

const paginatedCategories = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    return filteredCategories.value.slice(start, start + perPage.value);
});

const paginationStart = computed(() => (filteredCategories.value.length === 0 ? 0 : (currentPage.value - 1) * perPage.value + 1));
const paginationEnd = computed(() => Math.min(currentPage.value * perPage.value, filteredCategories.value.length));

// إعادة تعيين الصفحة عند الفلترة
watch([searchQuery, statusFilter, sortBy], () => {
    currentPage.value = 1;
});

const setViewMode = (mode) => {
    viewMode.value = mode;
    localStorage.setItem("wafar_categories_view_mode", mode);
};

const resetFilters = () => {
    searchQuery.value = "";
    statusFilter.value = "all";
    sortBy.value = "latest";
};

// ==========================================
// 4. إدارة النافذة المنبثقة (Modal CRUD)
// ==========================================
const showModal = ref(false);
const modalMode = ref("create"); // 'create' | 'edit'

const form = reactive({
    id: null,
    name: "",
    icon: "📦",
    count: 0,
    status: "active",
    description: "",
});

const formErrors = reactive({
    name: "",
});

const openModal = (mode, category = null) => {
    modalMode.value = mode;
    formErrors.name = "";

    if (mode === "create") {
        form.id = null;
        form.name = "";
        form.icon = "📦";
        form.count = 0;
        form.status = "active";
        form.description = "";
    } else if (category) {
        form.id = category.id;
        form.name = category.name;
        form.icon = category.icon || "📦";
        form.count = category.count || 0;
        form.status = category.status || "active";
        form.description = category.description || "";
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    formErrors.name = "";
};

const saveCategory = async () => {
    // التحقق من الحقول
    formErrors.name = "";
    if (!form.name.trim()) {
        formErrors.name = "اسم التصنيف مطلوب ضروري.";
        return;
    }

    isSubmitting.value = true;
    const payload = {
        name: form.name.trim(),
        image: form.icon ? form.icon.trim() : null,
        description: form.description ? form.description.trim() : null,
        is_active: form.status === "active" ? 1 : 0,
    };

    try {
        if (modalMode.value === "create") {
            const response = await apiClient.post("/categories", payload);
            const createdCat = response.data.category || response.data;
            categories.value.unshift(mapCategory(createdCat));
            showToast("تمت إضافة التصنيف الجديد بنجاح! ✨", "success");
        } else {
            const response = await apiClient.put(`/categories/${form.id}`, payload);
            const updatedCat = response.data.category || response.data;
            const idx = categories.value.findIndex((c) => c.id === form.id);
            if (idx !== -1) {
                categories.value[idx] = mapCategory(updatedCat);
            }
            showToast("تم تحديث بيانات التصنيف بنجاح! ✏️", "success");
        }
        closeModal();
    } catch (err) {
        console.error("Failed to save category:", err);
        if (err.response?.data?.errors?.name) {
            formErrors.name = err.response.data.errors.name[0];
        }
        const errorMsg = err.response?.data?.message || "حدث خطأ أثناء حفظ التصنيف";
        showToast(errorMsg, "danger");
    } finally {
        isSubmitting.value = false;
    }
};

// ==========================================
// 5. الحذف والمعاينة
// ==========================================
const showDeleteConfirm = ref(false);
const categoryToDelete = ref(null);

const confirmDelete = (cat) => {
    categoryToDelete.value = cat;
    showDeleteConfirm.value = true;
};

const deleteCategory = async () => {
    if (!categoryToDelete.value) return;
    isDeleting.value = true;
    try {
        await apiClient.delete(`/categories/${categoryToDelete.value.id}`);
        categories.value = categories.value.filter((c) => c.id !== categoryToDelete.value.id);
        showToast(`تم حذف تصنيف "${categoryToDelete.value.name}" بنجاح.`, "danger");
        showDeleteConfirm.value = false;
        categoryToDelete.value = null;
    } catch (err) {
        console.error("Failed to delete category:", err);
        const errorMsg = err.response?.data?.message || "حدث خطأ أثناء حذف التصنيف";
        showToast(errorMsg, "danger");
    } finally {
        isDeleting.value = false;
    }
};

// المعاينة
const showPreviewModal = ref(false);
const previewCategory = ref(null);

const openPreviewModal = (cat) => {
    previewCategory.value = cat;
    showPreviewModal.value = true;
};

// فحص الإيموجي مقابل الصورة
const isEmoji = (str) => {
    if (!str) return false;
    return !str.startsWith("http://") && !str.startsWith("https://") && !str.startsWith("data:");
};

// إدارة رفع صورة التصنيف من الجهاز
const fileInputRef = ref(null);

const triggerFileInput = () => {
    fileInputRef.value?.click();
};

const handleFileUpload = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) {
        showToast("حجم الصورة يجب أن لا يتجاوز 5 ميجابايت", "danger");
        return;
    }
    const reader = new FileReader();
    reader.onload = (event) => {
        form.icon = event.target.result;
    };
    reader.readAsDataURL(file);
};

const handleFileDrop = (e) => {
    const file = e.dataTransfer.files?.[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) {
        showToast("حجم الصورة يجب أن لا يتجاوز 5 ميجابايت", "danger");
        return;
    }
    const reader = new FileReader();
    reader.onload = (event) => {
        form.icon = event.target.result;
    };
    reader.readAsDataURL(file);
};

const removeFormImage = () => {
    form.icon = "📦";
};

const handleImgError = (e) => {
    e.target.style.display = "none";
};

const handleLogout = () => {
    localStorage.removeItem("wafar_token");
    localStorage.removeItem("wafar_user");
    router.push({ name: "AdminLogin" });
};
</script>

<style scoped>
/* ══════════════════════════════════
   أنماط صفحة التصنيفات (Wafar Cash UI System)
══════════════════════════════════ */

/* 1. الترويسة */
.categories-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
}

.categories-page-title {
    font-size: 22px;
    font-weight: 900;
    margin: 0 0 4px 0;
    color: var(--wc-text-dark, #0f172a);
    display: flex;
    align-items: center;
    gap: 10px;
}

.categories-page-subtitle {
    font-size: 13px;
    color: var(--wc-text-muted, #64748b);
    margin: 0;
}

.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--wc-green-bright, #22c55e);
    color: #ffffff;
    font-weight: 800;
    font-size: 14px;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
    transition: all 0.2s ease;
}

.btn-add:hover {
    background: #16a34a;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(34, 197, 94, 0.35);
}

/* 2. شريط الفلاتر والبحث */
.categories-filter-bar {
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

.categories-search-wrap {
    position: relative;
    width: 320px;
    flex-shrink: 1;
    min-width: 180px;
}

.categories-search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--wc-text-muted, #94a3b8);
    pointer-events: none;
}

.categories-search-input {
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
    direction: rtl;
}

.categories-search-input:focus {
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
}

.filter-controls-group {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.categories-count-badge {
    background: #e8f5ea;
    color: #15803d;
    font-weight: 800;
    font-size: 13px;
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid #c6f6d5;
}

.categories-filter-select {
    padding: 8px 12px;
    border-radius: 9px;
    border: 1.5px solid #cbd5e1;
    background: #f8fafc;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    outline: none;
    cursor: pointer;
    transition: all 0.15s ease;
}

.categories-filter-select:focus {
    border-color: var(--wc-green-bright);
    background: #ffffff;
}

.toolbar-divider {
    width: 1px;
    height: 28px;
    background: #e2e8f0;
    margin: 0 2px;
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
    cursor: pointer;
    transition: all 0.15s ease;
}

.view-toggle-btn--active {
    background: #ffffff;
    color: var(--wc-green-bright, #15803d);
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
}

/* 3. Grid View (شبكة بطاقات التصنيفات المطابقة لليواكس) */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 24px;
}

@media (max-width: 1200px) {
    .categories-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 850px) {
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 550px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
}

.category-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(15, 61, 31, 0.08);
    border-color: #cbd5e1;
}

.category-card__header {
    width: 100%;
    display: flex;
    justify-content: flex-start;
    margin-bottom: 12px;
}

.status-badge {
    font-size: 11.5px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-block;
}

.status-badge--active {
    background: #e8f5ea;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.status-badge--inactive {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.category-card__body {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 16px;
    width: 100%;
}

.category-card__icon-wrapper {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    border: 2px solid #ffffff;
    box-shadow: 0 4px 12px rgba(15, 61, 31, 0.1);
    overflow: hidden;
}

.category-emoji {
    font-size: 38px;
    line-height: 1;
}

.category-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-img {
    transform: scale(1.08);
}

.category-card__title {
    font-size: 17px;
    font-weight: 800;
    color: var(--wc-text-dark, #0f172a);
    margin: 0 0 6px 0;
}

.category-card__meta {
    font-size: 13.5px;
    color: var(--wc-text-muted, #64748b);
    font-weight: 700;
}

.category-card__actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
}

.card-act-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
}

.card-act-btn--view:hover {
    color: #2563eb;
    border-color: #93c5fd;
    background: #eff6ff;
}

.card-act-btn--edit:hover {
    color: var(--wc-green-bright, #16a34a);
    border-color: #86efac;
    background: #f0fdf4;
}

.card-act-btn--delete:hover {
    color: #dc2626;
    border-color: #fca5a5;
    background: #fef2f2;
}

/* 4. Table View (عرض الجدول) */
.categories-table-container {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
    overflow-x: auto;
    margin-bottom: 24px;
}

.categories-table {
    width: 100%;
    border-collapse: collapse;
    text-align: right;
    font-size: 13.5px;
}

.categories-table th {
    background: #f8fafc;
    padding: 14px 18px;
    font-weight: 800;
    color: #475569;
    border-bottom: 1.5px solid #e2e8f0;
}

.categories-table td {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    vertical-align: middle;
}

.table-cat-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.table-cat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    overflow: hidden;
}

.table-cat-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.table-cat-name {
    font-weight: 800;
    color: #0f172a;
    font-size: 14.5px;
}

.count-pill {
    background: #f1f5f9;
    padding: 4px 10px;
    border-radius: 8px;
    font-weight: 700;
    color: #475569;
    font-size: 12.5px;
}

.table-actions-group {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.table-act-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
}

.table-act-btn--view:hover { color: #2563eb; background: #eff6ff; border-color: #bfdbfe; }
.table-act-btn--edit:hover { color: #16a34a; background: #f0fdf4; border-color: #bbf7d0; }
.table-act-btn--delete:hover { color: #dc2626; background: #fef2f2; border-color: #fecaca; }

/* 5. Pagination Bar */
.categories-pagination-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 18px;
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    font-size: 13px;
    color: #64748b;
    font-weight: 600;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 6px;
}

.page-btn {
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s ease;
}

.page-btn--active {
    background: var(--wc-green-bright, #22c55e);
    color: #ffffff;
    border-color: var(--wc-green-bright, #22c55e);
}

.page-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* 6. Empty State */
.empty-state {
    background: #ffffff;
    border-radius: 16px;
    border: 1px dashed #cbd5e1;
    padding: 48px 24px;
    text-align: center;
    color: #64748b;
    margin-bottom: 24px;
}

.empty-state h3 {
    color: #0f172a;
    font-size: 18px;
    margin: 12px 0 6px 0;
}

.btn-secondary {
    padding: 8px 16px;
    border-radius: 9px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    font-weight: 700;
    cursor: pointer;
    margin-top: 12px;
}

/* 7. Modal Styling */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
    padding: 20px;
}

.modal-card {
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 520px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    animation: modalPop 0.25s ease-out;
}

.modal-card--sm {
    max-width: 400px;
}

@keyframes modalPop {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
}

.modal-title {
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-close-btn {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
}

.modal-close-btn:hover { color: #0f172a; background: #e2e8f0; }

.modal-form {
    padding: 22px;
}

.form-group {
    margin-bottom: 16px;
}

.form-row {
    display: flex;
    gap: 14px;
}

.flex-1 { flex: 1; }

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 800;
    color: #334155;
    margin-bottom: 6px;
}

.required-star { color: #dc2626; }

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    font-size: 13.5px;
    font-family: inherit;
    color: #0f172a;
    outline: none;
    box-sizing: border-box;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    border-color: var(--wc-green-bright);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.12);
}

.form-input--error {
    border-color: #ef4444;
}

.form-error-msg {
    font-size: 12px;
    color: #ef4444;
    font-weight: 700;
    margin-top: 4px;
    display: block;
}

.icon-picker-input-wrap {
    display: flex;
    gap: 10px;
}

.icon-preview-badge {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    overflow: hidden;
}

.icon-preview-badge img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.quick-icons-palette {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 10px;
    background: #f8fafc;
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid #f1f5f9;
}

.palette-title {
    font-size: 12px;
    font-weight: 800;
    color: #64748b;
    margin-left: 6px;
}

.palette-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    padding: 2px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    transition: all 0.12s ease;
}

.palette-thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
}

.palette-btn--selected,
.palette-btn:hover {
    border-color: var(--wc-green-bright);
    background: #e8f5ea;
    transform: scale(1.1);
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
}

.justify-center { justify-content: center; }

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--wc-green-bright, #22c55e);
    color: #ffffff;
    font-weight: 800;
    font-size: 13.5px;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
}

.btn-danger {
    background: #dc2626;
    color: #ffffff;
    font-weight: 800;
    font-size: 13.5px;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
}

/* 8. Delete Confirm Content */
.delete-modal-content {
    padding: 24px;
    text-align: center;
}

.delete-icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #fef2f2;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px auto;
}

.delete-modal-content h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
    color: #0f172a;
}

.delete-modal-content p {
    font-size: 14px;
    color: #334155;
    margin: 0 0 6px 0;
}

.delete-warning-subtext {
    font-size: 12px;
    color: #94a3b8;
    display: block;
    margin-bottom: 20px;
}

/* 9. Preview Modal Content */
.preview-modal-body {
    padding: 22px;
}

.preview-hero {
    display: flex;
    align-items: center;
    gap: 16px;
    padding-bottom: 18px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 16px;
}

.preview-icon-box {
    width: 64px;
    height: 64px;
    border-radius: 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    flex-shrink: 0;
    overflow: hidden;
}

.preview-icon-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-hero-info h2 {
    margin: 0 0 6px 0;
    font-size: 20px;
    color: #0f172a;
}

.preview-badges {
    display: flex;
    align-items: center;
    gap: 10px;
}

.preview-desc-box {
    background: #f8fafc;
    padding: 14px;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
    margin-bottom: 18px;
}

.preview-desc-box h4,
.preview-products-section h4 {
    margin: 0 0 6px 0;
    font-size: 13px;
    color: #64748b;
}

.preview-desc-box p {
    margin: 0;
    font-size: 13.5px;
    color: #1e293b;
    line-height: 1.5;
}

.mock-products-list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    margin-top: 10px;
}

.mock-product-item {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
}

.mock-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--wc-green-bright, #22c55e);
}

/* 10. Toast Notification */
.toast-notification {
    position: fixed;
    top: 24px;
    left: 24px;
    z-index: 1000;
    padding: 12px 18px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    font-size: 14px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.toast-notification--success {
    background: #0f3d1f;
    color: #ffffff;
    border: 1px solid #22a83e;
}

.toast-notification--danger {
    background: #991b1b;
    color: #ffffff;
    border: 1px solid #ef4444;
}

.toast-close {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    cursor: pointer;
    padding: 2px;
    display: flex;
}

.toast-close:hover { color: #ffffff; }

/* 11. Category Image Upload & Preview Styles */
.form-hint {
    font-size: 11px;
    color: #94a3b8;
    margin-right: 6px;
    font-weight: normal;
}

.cat-image-upload-wrapper {
    margin-bottom: 8px;
}

.cat-image-preview-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
}

.cat-image-preview-img {
    width: 64px;
    height: 64px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #cbd5e1;
}

.cat-image-preview-actions {
    display: flex;
    gap: 8px;
}

.btn-img-action {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 7px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
}

.btn-img-change {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.btn-img-change:hover {
    background: #dcfce7;
}

.btn-img-remove {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.btn-img-remove:hover {
    background: #fee2e2;
}

.cat-image-dropzone {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 16px;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}

.cat-image-dropzone:hover {
    border-color: var(--wc-green-bright, #22c55e);
    background: #f0fdf4;
}

.dropzone-icon {
    color: var(--wc-green-bright, #22c55e);
    margin-bottom: 8px;
}

.dropzone-text {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 2px;
}

.dropzone-subtext {
    font-size: 11px;
    color: #94a3b8;
}

/* Transitions */
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s ease;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>

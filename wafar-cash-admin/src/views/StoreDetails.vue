<template>
  <div class="dashboard-page">
    <AdminSidebar />

    <div class="dashboard-main">
      <AdminHeader @logout="handleLogout" />

      <div class="dashboard-content">
        <!-- مسار التصفح (Breadcrumb) -->
        <div class="breadcrumb-bar" v-if="store">
          <router-link to="/stores" class="breadcrumb-link">
            <StoreIcon :size="15" />
            <span>المتاجر</span>
          </router-link>
          <ChevronLeftIcon :size="14" class="breadcrumb-sep" />
          <span class="breadcrumb-current">{{ store.name }}</span>
        </div>

        <!-- حالة التحميل -->
        <div v-if="isLoading" class="loading-state">
          <div class="loading-spinner"></div>
          <span>جارٍ تحميل تفاصيل المتجر...</span>
        </div>

        <!-- حالة الخطأ -->
        <div v-else-if="errorMsg" class="error-banner">
          <AlertCircleIcon :size="20" />
          <span>{{ errorMsg }}</span>
          <button class="retry-btn" @click="fetchStoreDetails">إعادة المحاولة</button>
        </div>

        <template v-else-if="store">
          <!-- 1. ترويسة المتجر والكارت الأبيض العائم -->
          <div class="store-hero">
            <div class="store-hero-bg">
              <img
                v-if="store.cover_image_url"
                :src="store.cover_image_url"
                :alt="store.name"
                class="store-hero-bg-img"
              />
              <img
                v-else
                src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=1600&h=400"
                alt="Cover"
                class="store-hero-bg-img"
              />
            </div>

            <!-- الكارت الأبيض العائم بوسط الغلاف (Logo Card) -->
            <div class="store-hero-content">
              <div class="store-hero-card">
                <div class="store-hero-logo-wrap">
                  <img
                    v-if="store.image_url"
                    :src="store.image_url"
                    :alt="store.name"
                    class="store-hero-logo"
                  />
                  <StoreIcon v-else :size="48" class="store-hero-logo-placeholder" />
                </div>

                <h1 class="store-hero-title">{{ store.name }}</h1>
              </div>
            </div>
          </div>

          <!-- 2. تخطيط المحتوى الرئيسي (سلسلة العمودين) -->
          <div class="store-main-layout">
            <!-- كارت معلومات المتجر الجانبي (Sidebar Card) -->
            <div class="store-sidebar-col">
              <div class="sidebar-info-card">
                <h3 class="card-section-title">معلومات المتجر</h3>

                <div class="info-list">
                  <!-- المحافظة والمنطقة -->
                  <div class="info-item" v-if="store.region || store.governorate">
                    <div class="info-icon-wrap">
                      <MapPinIcon :size="16" />
                    </div>
                    <div class="info-text">
                      <span class="info-label">المحافظة والمنطقة</span>
                      <strong class="info-val">{{ store.region || store.governorate }}</strong>
                    </div>
                  </div>

                  <!-- العنوان التفصيلي / الشارع -->
                  <div class="info-item" v-if="store.address">
                    <div class="info-icon-wrap">
                      <MapPinIcon :size="16" />
                    </div>
                    <div class="info-text">
                      <span class="info-label">العنوان التفصيلي</span>
                      <strong class="info-val">{{ store.address }}</strong>
                    </div>
                  </div>

                  <!-- ساعات العمل -->
                  <div class="info-item">
                    <div class="info-icon-wrap">
                      <ClockIcon :size="16" />
                    </div>
                    <div class="info-text">
                      <span class="info-label">ساعات العمل</span>
                      <strong class="info-val">{{ store.working_hours || "8:00 صباحاً - 10:00 مساءً" }}</strong>
                    </div>
                  </div>

                  <!-- رقم الهاتف -->
                  <div class="info-item" v-if="store.phone">
                    <div class="info-icon-wrap">
                      <PhoneIcon :size="16" />
                    </div>
                    <div class="info-text">
                      <span class="info-label">رقم الهاتف</span>
                      <strong class="info-val" dir="ltr">{{ store.phone }}</strong>
                    </div>
                  </div>
                </div>

                <!-- شبكات التواصل الاجتماعي -->
                <div
                  class="sidebar-socials-wrap"
                  v-if="store.facebook_url || store.instagram_url || store.telegram_url"
                >
                  <span class="info-label">حسابات التواصل</span>
                  <div class="socials-badges-row">
                    <a
                      v-if="store.facebook_url"
                      :href="store.facebook_url"
                      target="_blank"
                      class="social-btn social-btn--fb"
                      title="فيسبوك"
                    >
                      <span>فيسبوك</span>
                    </a>
                    <a
                      v-if="store.instagram_url"
                      :href="store.instagram_url"
                      target="_blank"
                      class="social-btn social-btn--ig"
                      title="إنستغرام"
                    >
                      <span>إنستغرام</span>
                    </a>
                    <a
                      v-if="store.telegram_url"
                      :href="store.telegram_url"
                      target="_blank"
                      class="social-btn social-btn--tg"
                      title="تيليغرام"
                    >
                      <span>تيليغرام</span>
                    </a>
                  </div>
                </div>

                <!-- زر الموقع على الخريطة -->
                <a
                  v-if="store.latitude && store.longitude"
                  :href="`https://www.google.com/maps?q=${store.latitude},${store.longitude}`"
                  target="_blank"
                  class="btn-map-link"
                >
                  <MapIcon :size="16" />
                  <span>عرض الموقع على الخريطة</span>
                  <ExternalLinkIcon :size="14" />
                </a>

                <button class="btn-edit-full" @click="openEditModal">
                  <EditIcon :size="16" />
                  <span>تعديل البيانات</span>
                </button>
              </div>
            </div>

            <!-- منطقة المنتجات والفلترة (Products Area) -->
            <div class="store-products-col">
              <!-- شريط البحث والتصنيفات بعرض 100% -->
              <div class="products-toolbar">
                <div class="toolbar-top-row">
                  <div class="products-search-wrap">
                    <SearchIcon :size="16" class="search-icon" />
                    <input
                      v-model="searchQuery"
                      type="text"
                      placeholder="ابحث عن سلعة أو منتج..."
                      class="products-search-input"
                    />
                  </div>

                  <select v-model="selectedCategory" class="products-category-select">
                    <option value="">كل التصنيفات ({{ products.length }})</option>
                    <option v-for="cat in uniqueCategories" :key="cat" :value="cat">
                      {{ cat }}
                    </option>
                  </select>

                  <button
                    v-if="searchQuery || selectedCategory"
                    class="btn-reset-filter"
                    @click="resetFilters"
                    title="إعادة ضبط الفلتر"
                  >
                    <FilterXIcon :size="15" />
                    <span>إلغاء الفلترة</span>
                  </button>

                  <button class="btn-add-product" @click="openAddProductModal">
                    <PlusIcon :size="16" />
                    <span>إضافة منتج</span>
                  </button>
                </div>

                <!-- تبويبات التصنيفات السريعة (Quick Category Tabs) -->
                <div class="category-tabs" v-if="uniqueCategories.length > 0">
                  <button
                    class="tab-btn"
                    :class="{ 'tab-btn--active': selectedCategory === '' }"
                    @click="selectedCategory = ''"
                  >
                    الكل ({{ products.length }})
                  </button>
                  <button
                    v-for="cat in uniqueCategories"
                    :key="cat"
                    class="tab-btn"
                    :class="{ 'tab-btn--active': selectedCategory === cat }"
                    @click="selectedCategory = cat"
                  >
                    {{ cat }}
                  </button>
                </div>
              </div>

              <!-- مكون الحالة الفارغة الجذاب (Empty State Component) -->
              <div v-if="filteredProducts.length === 0" class="empty-state-card">
                <div class="empty-state-icon-wrap">
                  <PackageOpenIcon :size="46" />
                </div>
                <h3 class="empty-state-title">
                  {{ searchQuery || selectedCategory ? "لا توجد منتجات مطابقة للبحث" : "لا توجد منتجات مضافة في هذا المتجر بعد" }}
                </h3>
                <p class="empty-state-subtitle">
                  {{ searchQuery || selectedCategory ? "جرّب تغيير كلمات البحث أو إلغاء تحديد التصنيف لعرض جميع السلع." : "لم يتم إضافة أي سلع أو منتجات لهذا المتجر بعد. يمكنك البدء بإضافة أول منتج الآن." }}
                </p>
                <button v-if="searchQuery || selectedCategory" class="btn-empty-action" @click="resetFilters">
                  <FilterXIcon :size="16" />
                  <span>عرض كل المنتجات</span>
                </button>
                <button v-else class="btn-empty-action btn-empty-action--primary" @click="openAddProductModal">
                  <PlusIcon :size="16" />
                  <span>+ إضافة أول منتج لهذا المتجر</span>
                </button>
              </div>

              <!-- شبكة المنتجات -->
              <div v-else class="products-grid">
                <div class="product-card" v-for="product in filteredProducts" :key="product.id">
                  <button class="product-edit-btn" @click="editProduct(product)" title="تعديل المنتج">
                    <EditIcon :size="14" />
                  </button>
                  <div class="product-image-wrap">
                    <img
                      :src="getItemImage(product)"
                      :alt="product.name"
                      class="product-image"
                      @error="handleImageError($event, product)"
                    />
                  </div>
                  <div class="product-details">
                    <h3 class="product-name">{{ product.name }}</h3>
                    <span class="product-category">{{ product.category }}</span>
                    <div class="product-price-row">
                      <span class="product-price">{{ product.price }} شيكل</span>
                    </div>
                    <button class="btn-compare">قارن مع باقي المتاجر</button>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </template>
      </div>
    </div>

    <!-- نافذة التعديل الجديدة المماثلة لـ AdminStores (Modal) -->
    <div v-if="showEditModal" class="modal-overlay" @click.self="closeEditModal">
      <div class="modal modal--lg" role="dialog" aria-modal="true">
        <div class="modal__header">
          <h3 class="modal__title" style="display: flex; align-items: center; gap: 8px;">
            <EditIcon :size="18" style="color: var(--wc-green-bright);" />
            تعديل بيانات المتجر
          </h3>
          <button class="modal__close" @click="closeEditModal">✕</button>
        </div>

        <div class="modal__body">
          <!-- القسم الأول: البيانات الأساسية -->
          <div class="form-section">
            <h4 class="form-section-title">
              <StoreIcon :size="16" /> البيانات الأساسية للمتجر
            </h4>
            <div class="form-grid">
              <!-- اسم المتجر -->
              <div class="form-group">
                <label class="form-label">اسم المتجر <span class="required">*</span></label>
                <input v-model="editForm.name" type="text" class="form-input" placeholder="مثال: سوبر ماركت الأمل" />
              </div>

              <!-- رقم الهاتف -->
              <div class="form-group">
                <label class="form-label">رقم الهاتف <span class="required">*</span></label>
                <input v-model="editForm.phone" type="tel" class="form-input" placeholder="059xxxxxxx" dir="ltr" @input="formatPhone" />
              </div>

              <!-- المحافظة -->
              <div class="form-group">
                <label class="form-label">المحافظة <span class="required">*</span></label>
                <select v-model="editForm.governorate" class="form-input">
                  <option value="">اختر المحافظة...</option>
                  <option v-for="gov in GAZA_GOVERNORATES" :key="gov.value" :value="gov.value">
                    {{ gov.label }}
                  </option>
                </select>
              </div>

              <!-- الحي / المنطقة الفرعية -->
              <div class="form-group">
                <label class="form-label">الحي / المنطقة الفرعية <span class="form-hint">اختياري</span></label>
                <input v-model="editForm.sub_area" type="text" class="form-input" :placeholder="subAreaPlaceholder" />
              </div>

              <!-- العنوان التفصيلي / الشارع -->
              <div class="form-group form-group--full">
                <label class="form-label">العنوان التفصيلي / الشارع <span class="form-hint">اختياري</span></label>
                <input v-model="editForm.address" type="text" class="form-input" placeholder="مثال: شارع عمر المختار - مقابل المخبز" />
              </div>

              <!-- ساعات العمل -->
              <div class="form-group form-group--full">
                <label class="form-label">ساعات العمل</label>
                <div class="working-hours-wrap">
                  <input v-model="editForm.working_hours" type="text" class="form-input" placeholder="مثال: من 8:00 صباحاً إلى 10:00 مساءً" />
                  <div class="quick-presets">
                    <button type="button" class="preset-btn" @click="editForm.working_hours = 'مفتوح 24 ساعة'">
                      مفتوح 24 ساعة
                    </button>
                    <button type="button" class="preset-btn" @click="editForm.working_hours = 'من 8:00 صباحاً - 10:00 مساءً'">
                      8:00 ص - 10:00 م
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- القسم الثاني: موقع المتجر على الخريطة -->
          <div class="form-section" style="margin-top: 14px;">
            <h4 class="form-section-title">
              <MapPinIcon :size="16" /> موقع المتجر (GPS / خرائط جوجل)
            </h4>
            <div class="form-grid">
              <div class="form-group form-group--full">
                <label class="form-label">رابط موقع المتجر على الخريطة أو الإحداثيات</label>
                <input v-model="editForm.map_location" type="text" class="form-input" placeholder="أدخل رابط خرائط جوجل أو الإحداثيات (مثال: 31.5, 34.45)" dir="ltr" @change="parseMapLocation" />
              </div>
            </div>
          </div>

          <!-- القسم الثالث: حسابات التواصل الاجتماعي -->
          <div class="form-section" style="margin-top: 14px;">
            <h4 class="form-section-title">
              <GlobeIcon :size="16" /> حسابات التواصل الاجتماعي (اختياري)
            </h4>
            <div class="form-grid">
              <!-- رابط فيسبوك -->
              <div class="form-group">
                <label class="form-label">حساب فيسبوك</label>
                <div class="input-prefix-wrap" dir="ltr">
                  <span class="input-prefix">facebook.com/</span>
                  <input v-model="editForm.facebook_url" type="text" class="form-input input-with-prefix" placeholder="username" />
                </div>
              </div>

              <!-- رابط إنستغرام -->
              <div class="form-group">
                <label class="form-label">حساب إنستغرام</label>
                <div class="input-prefix-wrap" dir="ltr">
                  <span class="input-prefix">instagram.com/</span>
                  <input v-model="editForm.instagram_url" type="text" class="form-input input-with-prefix" placeholder="username" />
                </div>
              </div>

              <!-- رابط تيليغرام -->
              <div class="form-group">
                <label class="form-label">قناة / حساب تيليغرام</label>
                <div class="input-prefix-wrap" dir="ltr">
                  <span class="input-prefix">t.me/</span>
                  <input v-model="editForm.telegram_url" type="text" class="form-input input-with-prefix" placeholder="username" />
                </div>
              </div>
            </div>
          </div>

          <!-- القسم الرابع: صور المتجر (الشعار والغلاف) -->
          <div class="form-section" style="margin-top: 14px;">
            <h4 class="form-section-title">
              <ImageIcon :size="16" /> صور المتجر
            </h4>
            <div class="image-uploads-grid">
              <!-- رفع الشعار -->
              <div class="form-group">
                <label class="form-label">شعار المتجر (Logo)</label>
                <div class="image-upload-area" @click="triggerFileInput" @dragover.prevent @drop.prevent="handleDrop">
                  <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;" @change="handleFileSelect" />
                  <div v-if="imagePreview" class="image-preview-wrap">
                    <img :src="imagePreview" alt="معاينة الشعار" class="image-preview" />
                    <button type="button" class="image-preview-remove" @click.stop="removeImage" title="إزالة الصورة">✕</button>
                  </div>
                  <div v-else class="image-upload-placeholder">
                    <UploadCloudIcon :size="30" style="color: var(--wc-green-bright); opacity: 0.7;" />
                    <p>رفع <strong>الشعار (Logo)</strong></p>
                    <span>PNG, JPG, WEBP — حتى 3MB</span>
                  </div>
                </div>
              </div>

              <!-- رفع الغلاف -->
              <div class="form-group">
                <label class="form-label">صورة غلاف المتجر (Banner)</label>
                <div class="image-upload-area" @click="triggerCoverFileInput" @dragover.prevent @drop.prevent="handleCoverDrop">
                  <input ref="coverFileInput" type="file" accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;" @change="handleCoverFileSelect" />
                  <div v-if="coverImagePreview" class="image-preview-wrap">
                    <img :src="coverImagePreview" alt="معاينة الغلاف" class="image-preview" />
                    <button type="button" class="image-preview-remove" @click.stop="removeCoverImage" title="إزالة الصورة">✕</button>
                  </div>
                  <div v-else class="image-upload-placeholder">
                    <UploadCloudIcon :size="30" style="color: var(--wc-green-bright); opacity: 0.7;" />
                    <p>رفع <strong>صورة الغلاف (Banner)</strong></p>
                    <span>PNG, JPG, WEBP — حتى 5MB</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-if="formError" class="error-alert">
            <AlertCircleIcon :size="16" />
            <span>{{ formError }}</span>
          </div>
        </div>

        <div class="modal__footer">
          <button class="btn-cancel" @click="closeEditModal">إلغاء</button>
          <button class="btn-save" @click="submitEdit" :disabled="isSaving">
            <span v-if="isSaving" class="btn-spinner"></span>
            {{ isSaving ? "جارٍ الحفظ..." : "تحديث بيانات المتجر" }}
          </button>
        </div>
      </div>
    </div>

    <!-- ======= مودال إضافة / تعديل منتج للمتجر (مطابق لمودال إدارة المنتجات) ======= -->
    <div v-if="showProductModal" class="modal-overlay" @click.self="closeProductModal">
      <div class="modal modal--edit-item" role="dialog" aria-modal="true">
        <div class="modal__header">
          <h2 class="modal__title">
            <PackageIcon :size="18" style="color: var(--wc-green-bright)" />
            {{ productModalMode === 'create' ? 'إضافة صنف جديد للمتجر' : 'تعديل بيانات الصنف' }}
          </h2>
          <button class="modal__close" @click="closeProductModal" title="إغلاق (Esc)">&times;</button>
        </div>
        <div class="modal__body">
          <div v-if="productSaveError" class="error-alert">
            <AlertCircleIcon :size="15" />
            <span>{{ productSaveError }}</span>
          </div>

          <!-- 1. رفع ومعاينة صورة المنتج -->
          <div class="form-group">
            <label class="form-label">
              صورة المنتج
              <span class="form-hint">اختياري</span>
            </label>

            <div class="image-upload-wrapper">
              <!-- معاينة الصورة الحالية -->
              <div v-if="productForm.image_url" class="image-preview-card">
                <img :src="productForm.image_url" alt="معاينة المنتج" class="image-preview-img" />
                <div class="image-preview-actions">
                  <button type="button" class="btn-img-action btn-img-change" @click="triggerProductFileInput" title="تغيير الصورة">
                    <UploadIcon :size="14" /> تغيير
                  </button>
                  <button type="button" class="btn-img-action btn-img-remove" @click="removeProductFormImage" title="إزالة الصورة">
                    <Trash2Icon :size="14" /> إزالة
                  </button>
                </div>
              </div>

              <!-- منطقة الرفع / السحب والإسقاط -->
              <div
                v-else
                class="image-dropzone"
                @click="triggerProductFileInput"
                @dragover.prevent
                @drop.prevent="handleProductFileDrop"
              >
                <UploadIcon :size="24" class="dropzone-icon" />
                <span class="dropzone-text">اختر صورة أو اسحبها هنا</span>
                <span class="dropzone-subtext">JPG, PNG, WEBP (بحد أقصى 5 ميجابايت)</span>
              </div>

              <input
                type="file"
                ref="productFileInput"
                class="hidden-file-input"
                accept="image/*"
                @change="handleProductFileUpload"
              />
            </div>
          </div>

          <!-- 2. اسم الصنف -->
          <div class="form-group">
            <label class="form-label" for="store-item-name">
              اسم الصنف <span class="required-star">*</span>
            </label>
            <input
              v-model="productForm.name"
              type="text"
              id="store-item-name"
              class="form-input"
              :class="{ 'form-input--error': productFormErrors.name }"
              placeholder="مثال: طماطم طازجة، أرز بسمتي، دجاج..."
              @keyup.enter="saveProduct"
            />
            <span v-if="productFormErrors.name" class="field-error">{{ productFormErrors.name }}</span>
          </div>

          <!-- 3. قائمة الفئات الإجبارية (Select Dropdown) -->
          <div class="form-group">
            <label class="form-label" for="store-item-category">
              فئة الصنف <span class="required-star">*</span>
            </label>
            <select
              v-model="productForm.category"
              id="store-item-category"
              class="form-input form-select"
              :class="{ 'form-input--error': productFormErrors.category }"
            >
              <option value="" disabled>اختر فئة المنتج...</option>
              <option v-for="cat in DEFAULT_CATEGORIES" :key="cat" :value="cat">{{ cat }}</option>
            </select>
            <span v-if="productFormErrors.category" class="field-error">{{ productFormErrors.category }}</span>
          </div>

          <!-- 4. سعر المنتج للمتجر بالشيكل -->
          <div class="form-group">
            <label class="form-label" for="store-item-price">
              سعر الصنف في هذا المتجر
              <span class="form-hint">اختياري</span>
            </label>
            <div class="price-input-wrap">
              <input
                v-model="productForm.price"
                type="number"
                step="0.5"
                min="0"
                id="store-item-price"
                class="form-input price-input"
                placeholder="مثال: 12.5"
                @keyup.enter="saveProduct"
              />
              <span class="price-unit-addon">شيكل</span>
            </div>
          </div>

        </div>
        <!-- أزرار الإجراءات المتناسقة -->
        <div class="modal__footer modal__footer--balanced">
          <button
            class="btn-save btn-save--full"
            @click="saveProduct"
            :disabled="isSavingProduct"
          >
            <span v-if="isSavingProduct" class="btn-spinner"></span>
            <span>{{ isSavingProduct ? (productModalMode === 'create' ? 'جارٍ إضافة الصنف...' : 'جارٍ حفظ التعديلات...') : (productModalMode === 'create' ? 'إضافة الصنف' : 'حفظ التعديلات') }}</span>
          </button>
          <button class="btn-cancel btn-cancel--full" @click="closeProductModal">إلغاء</button>
        </div>
      </div>
    </div>

  </div>
</template>


<script>
import AdminSidebar from '../components/AdminSidebar.vue'
import AdminHeader from '../components/AdminHeader.vue'
import apiClient from '../api/axios.js'
import { globalState } from '../state.js'

// المحافظات الخمس لقطاع غزة (ثابتة)
const GAZA_GOVERNORATES = [
  { value: "شمال_غزة", label: "محافظة شمال غزة", hint: "مثال: جباليا، بيت لاهيا، بيت حانون" },
  { value: "غزة", label: "محافظة غزة", hint: "مثال: الرمال، الزيتون، الشجاعية، النصر، الشيخ رضوان" },
  { value: "الوسطى", label: "محافظة الوسطى (دير البلح)", hint: "مثال: النصيرات، البريج، المغازي، دير البلح، الزوايدة" },
  { value: "خان_يونس", label: "محافظة خان يونس", hint: "مثال: خان يونس البلد، الأمل، عبسان، بني سهيلا" },
  { value: "رفح", label: "محافظة رفح", hint: "مثال: الشابورة، تل السلطان، الجنينة، حي البرازيل" }
]

const DEFAULT_CATEGORIES = [
  "خضروات",
  "فواكه",
  "لحوم ودواجن",
  "مواد تموينية",
  "زيوت وبقوليات",
  "ألبان وأجبان",
  "مشروبات وحلويات",
  "أخرى",
]

const CATEGORY_PALETTES = [
  { bg: "#e8f5ea", color: "#15803d", border: "#bbf7d0" },
  { bg: "#fff7ed", color: "#c2410c", border: "#fed7aa" },
  { bg: "#fef9c3", color: "#a16207", border: "#fde68a" },
  { bg: "#eff6ff", color: "#1d4ed8", border: "#bfdbfe" },
  { bg: "#f5f3ff", color: "#6d28d9", border: "#ddd6fe" },
  { bg: "#fce7f3", color: "#be185d", border: "#fbcfe8" },
  { bg: "#ecfeff", color: "#0e7490", border: "#a5f3fc" },
  { bg: "#f0fdf4", color: "#166534", border: "#86efac" },
]

const _catColorCache = {}
let _catColorCounter = 0

function getCategoryPalette(category) {
  if (!category) return { bg: "#f1f5f9", color: "#64748b", border: "#e2e8f0" }
  if (!_catColorCache[category]) {
    _catColorCache[category] = CATEGORY_PALETTES[_catColorCounter % CATEGORY_PALETTES.length]
    _catColorCounter++
  }
  return _catColorCache[category]
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
  "موز": "https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=100&auto=format&fit=crop&q=80",
  "تفاح": "https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=100&auto=format&fit=crop&q=80",
}

function createSvgPlaceholder(name, category) {
  const char = (name || "ص").charAt(0)
  const palette = getCategoryPalette(category)
  const bg = palette.bg
  const color = palette.color
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><rect width="64" height="64" rx="12" fill="${bg}"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-weight="bold" font-size="28" fill="${color}">${char}</text></svg>`
  return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`
}


import {
  Store as StoreIcon,
  MapPin as MapPinIcon,
  Clock as ClockIcon,
  Map as MapIcon,
  Phone as PhoneIcon,
  Search as SearchIcon,
  Edit as EditIcon,
  AlertCircle as AlertCircleIcon,
  Package as PackageIcon,
  Plus as PlusIcon,
  ChevronLeft as ChevronLeftIcon,
  ExternalLink as ExternalLinkIcon,
  FilterX as FilterXIcon,
  PackageOpen as PackageOpenIcon,
  Globe as GlobeIcon,
  Image as ImageIcon,
  UploadCloud as UploadCloudIcon,
  Upload as UploadIcon,
  Trash2 as Trash2Icon
} from '@lucide/vue'

export default {
  name: 'StoreDetails',
  components: {
    AdminSidebar,
    AdminHeader,
    StoreIcon,
    MapPinIcon,
    ClockIcon,
    MapIcon,
    PhoneIcon,
    SearchIcon,
    EditIcon,
    AlertCircleIcon,
    PackageIcon,
    PlusIcon,
    ChevronLeftIcon,
    ExternalLinkIcon,
    FilterXIcon,
    PackageOpenIcon,
    GlobeIcon,
    ImageIcon,
    UploadCloudIcon,
    UploadIcon,
    Trash2Icon
  },
  data() {
    return {
      store: null,
      products: [],
      regions: [],
      isLoading: true,
      errorMsg: '',

      // فلاتر البحث
      searchQuery: '',
      selectedCategory: '',

      // نافذة التعديل للمتجر
      showEditModal: false,
      editForm: {},
      imageFile: null,
      coverImageFile: null,
      imagePreview: null,
      coverImagePreview: null,
      isSaving: false,
      formError: '',

      // نافذة إضافة / تعديل صنف للمتجر
      DEFAULT_CATEGORIES,
      showProductModal: false,
      productModalMode: 'create',
      activeProduct: null,
      productForm: {
        name: '',
        category: 'خضروات',
        price: '',
        image_url: ''
      },
      productFormErrors: {
        name: '',
        category: ''
      },
      isSavingProduct: false,
      productSaveError: ''
    }
  },
  computed: {
    GAZA_GOVERNORATES() {
      return GAZA_GOVERNORATES
    },
    subAreaPlaceholder() {
      const gov = GAZA_GOVERNORATES.find(g => g.value === this.editForm.governorate)
      return gov ? gov.hint : "مثال: الرمال - شارع عمر المختار"
    },
    uniqueCategories() {
      const cats = this.products.map(p => p.category).filter(c => c && c !== 'غير محدد')
      return [...new Set(cats)]
    },
    filteredProducts() {
      return this.products.filter(p => {
        const matchQuery = !this.searchQuery || p.name.toLowerCase().includes(this.searchQuery.toLowerCase())
        const matchCategory = !this.selectedCategory || p.category === this.selectedCategory
        return matchQuery && matchCategory
      })
    }
  },
  mounted() {
    this.fetchStoreDetails()
    window.addEventListener('keydown', this.handleKeyDown)
  },
  unmounted() {
    window.removeEventListener('keydown', this.handleKeyDown)
  },
  methods: {
    handleKeyDown(e) {
      if (e.key === 'Escape') {
        if (this.showProductModal) this.closeProductModal()
        if (this.showEditModal) this.closeEditModal()
      }
    },

    async fetchStoreDetails() {
      this.isLoading = true
      this.errorMsg = ''
      try {
        const id = this.$route.params.id
        const res = await apiClient.get(`/admin/stores/${id}`)
        if (res.data.status === 'success') {
          this.store = res.data.store
          this.products = res.data.products
          this.regions = res.data.regions || []
        } else {
          this.errorMsg = res.data.message || 'حدث خطأ'
        }
      } catch (err) {
        this.errorMsg = 'تعذر جلب تفاصيل المتجر'
      } finally {
        this.isLoading = false
      }
    },

    resetFilters() {
      this.searchQuery = ''
      this.selectedCategory = ''
    },

    openAddProductModal() {
      this.productModalMode = 'create'
      this.activeProduct = null
      this.productForm = {
        name: '',
        category: DEFAULT_CATEGORIES[0],
        price: '',
        image_url: ''
      }
      this.productFormErrors = { name: '', category: '' }
      this.productSaveError = ''
      this.showProductModal = true
    },

    editProduct(product) {
      this.productModalMode = 'edit'
      this.activeProduct = product
      this.productForm = {
        name: product.name || '',
        category: product.category || DEFAULT_CATEGORIES[0],
        price: product.price || '',
        image_url: product.image_url || ''
      }
      this.productFormErrors = { name: '', category: '' }
      this.productSaveError = ''
      this.showProductModal = true
    },

    closeProductModal() {
      this.showProductModal = false
      this.activeProduct = null
      this.productSaveError = ''
      this.productFormErrors = { name: '', category: '' }
    },

    validateProductForm() {
      this.productFormErrors = { name: '', category: '' }
      let valid = true

      if (!this.productForm.name || !this.productForm.name.trim()) {
        this.productFormErrors.name = 'اسم الصنف مطلوب.'
        valid = false
      } else if (this.productForm.name.trim().length < 2) {
        this.productFormErrors.name = 'الاسم يجب أن يكون حرفين على الأقل.'
        valid = false
      }

      if (!this.productForm.category) {
        this.productFormErrors.category = 'يرجى اختيار فئة المنتج.'
        valid = false
      }

      return valid
    },

    async saveProduct() {
      if (!this.validateProductForm()) return
      this.isSavingProduct = true
      this.productSaveError = ''

      try {
        if (this.productModalMode === 'create') {
          const payload = {
            name: this.productForm.name.trim(),
            category: this.productForm.category,
            price: this.productForm.price ? parseFloat(this.productForm.price) : 0,
            image_url: this.productForm.image_url ? this.productForm.image_url.trim() : null
          }
          const res = await apiClient.post(`/admin/stores/${this.store.id}/products`, payload)
          if (res.data.status === 'success') {
            this.closeProductModal()
            await this.fetchStoreDetails()
            globalState.triggerNotificationRefresh()
          } else {
            this.productSaveError = res.data.message || 'حدث خطأ أثناء حفظ الصنف'
          }
        } else {
          const payload = {
            item_name: this.productForm.name.trim(),
            category: this.productForm.category,
            price: this.productForm.price ? parseFloat(this.productForm.price) : 0,
            image_url: this.productForm.image_url ? this.productForm.image_url.trim() : null
          }
          const res = await apiClient.put(`/admin/products/${this.activeProduct.id}`, payload)
          if (res.data.status === 'success') {
            this.closeProductModal()
            await this.fetchStoreDetails()
            globalState.triggerNotificationRefresh()
          } else {
            this.productSaveError = res.data.message || 'حدث خطأ أثناء تحديث الصنف'
          }
        }
      } catch (err) {
        this.productSaveError = err.response?.data?.message || 'تعذر الاتصال بالخادم لحفظ الصنف.'
      } finally {
        this.isSavingProduct = false
      }
    },

    triggerProductFileInput() {
      this.$refs.productFileInput?.click()
    },

    handleProductFileUpload(event) {
      const file = event.target.files?.[0]
      if (file) this.processProductFile(file)
    },

    handleProductFileDrop(event) {
      const file = event.dataTransfer?.files?.[0]
      if (file) this.processProductFile(file)
    },

    processProductFile(file) {
      if (file.size > 10 * 1024 * 1024) {
        this.productSaveError = 'حجم الصورة كبير جداً (الأقصى 10 ميجابايت).'
        return
      }
      const reader = new FileReader()
      reader.onload = (e) => {
        const img = new Image()
        img.onload = () => {
          const canvas = document.createElement('canvas')
          const maxDim = 600
          let width = img.width
          let height = img.height
          if (width > height) {
            if (width > maxDim) {
              height = Math.round((height * maxDim) / width)
              width = maxDim
            }
          } else {
            if (height > maxDim) {
              width = Math.round((width * maxDim) / height)
              height = maxDim
            }
          }
          canvas.width = width
          canvas.height = height
          const ctx = canvas.getContext('2d')
          ctx.drawImage(img, 0, 0, width, height)
          this.productForm.image_url = canvas.toDataURL('image/webp', 0.85)
        }
        img.onerror = () => {
          this.productForm.image_url = e.target.result
        }
        img.src = e.target.result
      }
      reader.readAsDataURL(file)
    },

    removeProductFormImage() {
      this.productForm.image_url = ''
      if (this.$refs.productFileInput) this.$refs.productFileInput.value = ''
    },

    getItemImage(item) {
      if (!item) return createSvgPlaceholder("", "")
      if (item.image_url) return item.image_url
      if (item.image) return item.image

      const name = item.name || ""
      for (const [key, url] of Object.entries(PRODUCT_THUMBNAILS)) {
        if (name.includes(key)) return url
      }
      return createSvgPlaceholder(name, item.category)
    },

    handleImageError(event, item) {
      event.target.src = createSvgPlaceholder(item?.name, item?.category)
    },


    openEditModal() {
      this.editForm = {
        name: this.store.name || '',
        governorate: this.store.governorate || '',
        sub_area: this.store.sub_area || '',
        address: this.store.address || '',
        phone: this.store.phone || '',
        working_hours: this.store.working_hours || '',
        latitude: this.store.latitude || '',
        longitude: this.store.longitude || '',
        map_location: (this.store.latitude && this.store.longitude) ? `${this.store.latitude}, ${this.store.longitude}` : '',
        facebook_url: this.extractUsername(this.store.facebook_url, 'facebook.com'),
        instagram_url: this.extractUsername(this.store.instagram_url, 'instagram.com'),
        telegram_url: this.extractUsername(this.store.telegram_url, 't.me'),
      }
      this.imagePreview = this.store.image_url || null
      this.coverImagePreview = this.store.cover_image_url || null
      this.imageFile = null
      this.coverImageFile = null
      this.formError = ''
      this.showEditModal = true
    },

    closeEditModal() {
      this.showEditModal = false
    },

    formatPhone() {
      if (this.editForm.phone) {
        this.editForm.phone = this.editForm.phone.replace(/[^\d+]/g, '')
      }
    },

    extractUsername(url, domain) {
      if (!url) return ''
      let u = url.trim()
      u = u.replace(new RegExp(`^(https?:\\/\\/)?(www\\.)?${domain}\\/`, 'i'), '')
      return u
    },

    cleanSocialUrl(url, domain) {
      if (!url || !url.trim()) return ''
      let trimmed = url.trim()
      if (trimmed.startsWith('http://') || trimmed.startsWith('https://')) {
        return trimmed
      }
      trimmed = trimmed.replace(new RegExp(`^(https?:\\/\\/)?(www\\.)?${domain}\\/`, 'i'), '')
      return `https://${domain}/${trimmed.replace(/^\/+/, '')}`
    },

    parseMapLocation() {
      if (!this.editForm.map_location) return
      const loc = this.editForm.map_location.trim()
      const coordsMatch = loc.match(/(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/)
      if (coordsMatch) {
        this.editForm.latitude = coordsMatch[1]
        this.editForm.longitude = coordsMatch[2]
      }
    },

    triggerFileInput() {
      if (this.$refs.fileInput) this.$refs.fileInput.click()
    },

    triggerCoverFileInput() {
      if (this.$refs.coverFileInput) this.$refs.coverFileInput.click()
    },

    handleFileSelect(e) {
      const file = e.target.files[0]
      if (file) this.processImageFile(file)
    },

    handleCoverFileSelect(e) {
      const file = e.target.files[0]
      if (file) this.processCoverImageFile(file)
    },

    handleDrop(e) {
      const file = e.dataTransfer.files[0]
      if (file && file.type.startsWith('image/')) this.processImageFile(file)
    },

    handleCoverDrop(e) {
      const file = e.dataTransfer.files[0]
      if (file && file.type.startsWith('image/')) this.processCoverImageFile(file)
    },

    processImageFile(file) {
      if (file.size > 3 * 1024 * 1024) {
        this.formError = 'حجم شعار المتجر يجب ألا يتجاوز 3 ميجابايت'
        return
      }
      this.imageFile = file
      this.formError = ''
      const reader = new FileReader()
      reader.onload = (e) => { this.imagePreview = e.target.result }
      reader.readAsDataURL(file)
    },

    processCoverImageFile(file) {
      if (file.size > 5 * 1024 * 1024) {
        this.formError = 'حجم صورة الغلاف يجب ألا يتجاوز 5 ميجابايت'
        return
      }
      this.coverImageFile = file
      this.formError = ''
      const reader = new FileReader()
      reader.onload = (e) => { this.coverImagePreview = e.target.result }
      reader.readAsDataURL(file)
    },

    removeImage() {
      this.imageFile = null
      this.imagePreview = null
      if (this.$refs.fileInput) this.$refs.fileInput.value = ''
    },

    removeCoverImage() {
      this.coverImageFile = null
      this.coverImagePreview = null
      if (this.$refs.coverFileInput) this.$refs.coverFileInput.value = ''
    },

    async submitEdit() {
      if (!this.editForm.name.trim()) {
        this.formError = 'اسم المتجر مطلوب'
        return
      }
      if (!this.editForm.governorate) {
        this.formError = 'يجب تحديد المحافظة أولاً'
        return
      }
      if (!this.editForm.phone || !this.editForm.phone.trim()) {
        this.formError = 'رقم الهاتف مطلوب'
        return
      }

      this.isSaving = true
      this.formError = ''
      try {
        const id = this.$route.params.id
        const formData = new FormData()
        formData.append('name', this.editForm.name.trim())
        formData.append('governorate', this.editForm.governorate)
        formData.append('sub_area', this.editForm.sub_area ? this.editForm.sub_area.trim() : '')
        formData.append('address', this.editForm.address ? this.editForm.address.trim() : '')
        formData.append('phone', this.editForm.phone.trim())
        if (this.editForm.working_hours) formData.append('working_hours', this.editForm.working_hours.trim())
        if (this.editForm.latitude) formData.append('latitude', this.editForm.latitude)
        if (this.editForm.longitude) formData.append('longitude', this.editForm.longitude)
        formData.append('region_id', this.store.region_id || '')

        const fb = this.cleanSocialUrl(this.editForm.facebook_url, 'facebook.com')
        const ig = this.cleanSocialUrl(this.editForm.instagram_url, 'instagram.com')
        const tg = this.cleanSocialUrl(this.editForm.telegram_url, 't.me')

        if (fb) formData.append('facebook_url', fb)
        if (ig) formData.append('instagram_url', ig)
        if (tg) formData.append('telegram_url', tg)

        if (this.imageFile) formData.append('image', this.imageFile)
        if (this.coverImageFile) formData.append('cover_image', this.coverImageFile)

        const res = await apiClient.post(`/admin/stores/${id}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })

        if (res.data.status === 'success') {
          this.store = res.data.store
          globalState.triggerNotificationRefresh()
          this.closeEditModal()
        } else {
          this.formError = res.data.message || 'حدث خطأ أثناء الحفظ'
        }
      } catch (err) {
        this.formError = 'حدث خطأ أثناء الحفظ'
      } finally {
        this.isSaving = false
      }
    },

    handleLogout() {
      localStorage.removeItem('wafar_token')
      localStorage.removeItem('wafar_user')
      this.$router.push({ name: 'AdminLogin' })
    }
  }
}
</script>


<style scoped>
/* مسار التصفح (Breadcrumb) */
.breadcrumb-bar {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  margin-bottom: 14px;
}

.breadcrumb-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: var(--wc-text-muted);
  text-decoration: none;
  font-weight: 600;
  transition: color 0.15s;
}

.breadcrumb-link:hover {
  color: var(--wc-green-bright);
}

.breadcrumb-sep {
  color: var(--wc-text-muted);
  opacity: 0.6;
}

.breadcrumb-current {
  color: var(--wc-text-dark);
  font-weight: 700;
}

/* 1. ترويسة صفحة المحل والغلاف الخالي من اللون الأخضر الشفاف */
.store-hero {
  position: relative;
  border-radius: 20px;
  overflow: hidden;
  margin-bottom: 28px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  background: var(--wc-gray-bg);
  min-height: 220px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.store-hero-bg {
  position: absolute;
  inset: 0;
  overflow: hidden;
}

.store-hero-bg-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* الكارت الأبيض العائم الناصع في المنتصف (Logo Card) */
.store-hero-content {
  position: relative;
  z-index: 3;
  padding: 24px 16px;
  display: flex;
  justify-content: center;
  width: 100%;
}

.store-hero-card {
  background: #ffffff;
  border: 1px solid var(--wc-border);
  border-radius: 20px;
  padding: 24px 44px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  min-width: 260px;
  max-width: 360px;
  text-align: center;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.store-hero-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 36px rgba(0, 0, 0, 0.2);
}

.store-hero-logo-wrap {
  width: 86px;
  height: 86px;
  border-radius: 16px;
  background: #ffffff;
  border: 1px solid var(--wc-border);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 6px;
}

.store-hero-logo {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.store-hero-logo-placeholder {
  color: var(--wc-green-bright);
}

.store-hero-title {
  font-size: 20px;
  font-weight: 900;
  color: var(--wc-text-dark);
  margin: 0;
}

/* 2. التخطيط الرئيسي لصفحة المتجر */
.store-main-layout {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 24px;
  align-items: start;
}

.sidebar-info-card {
  background: #ffffff;
  border: 1px solid var(--wc-border);
  border-radius: 16px;
  padding: 20px;
  box-shadow: var(--wc-shadow);
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.card-section-title {
  font-size: 16px;
  font-weight: 800;
  margin: 0;
  color: var(--wc-text-dark);
  padding-bottom: 10px;
  border-bottom: 1px solid var(--wc-border);
}

.info-list {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.info-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.info-icon-wrap {
  width: 34px;
  height: 34px;
  border-radius: 10px;
  background: var(--wc-green-light);
  color: var(--wc-green-bright);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.info-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.info-label {
  font-size: 11.5px;
  color: var(--wc-text-muted);
}

.info-val {
  font-size: 13.5px;
  color: var(--wc-text-dark);
  font-weight: 700;
}

.sidebar-socials-wrap {
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding-top: 10px;
  border-top: 1px dashed var(--wc-border);
}

.socials-badges-row {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.social-btn {
  padding: 5px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 700;
  color: #fff;
  text-decoration: none;
  transition: opacity 0.15s;
}

.social-btn:hover {
  opacity: 0.88;
}

.social-btn--fb { background: #1877f2; }
.social-btn--ig { background: linear-gradient(135deg, #f09433, #dc2743, #bc1888); }
.social-btn--tg { background: #229ed9; }

.btn-map-link {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px;
  border-radius: 10px;
  background: #f1f5f9;
  color: #334155;
  border: 1px solid var(--wc-border);
  font-weight: 700;
  font-size: 13px;
  text-decoration: none;
  transition: all 0.15s;
}

.btn-map-link:hover {
  background: #e2e8f0;
}

.btn-edit-full {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px;
  border-radius: 10px;
  background: var(--wc-white);
  border: 1px solid var(--wc-green-bright);
  color: var(--wc-green-bright);
  font-weight: 700;
  font-size: 13.5px;
  cursor: pointer;
  transition: all 0.15s;
}

.btn-edit-full:hover {
  background: var(--wc-green-light);
}

/* 3. شريط المنتجات والتصنيفات بعرض 100% */
.store-products-col {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.products-toolbar {
  background: #ffffff;
  border: 1px solid var(--wc-border);
  border-radius: 14px;
  padding: 16px;
  box-shadow: var(--wc-shadow);
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.toolbar-top-row {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.products-search-wrap {
  position: relative;
  flex: 1;
  min-width: 220px;
}

.products-search-wrap .search-icon {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--wc-text-muted);
}

.products-search-input {
  width: 100%;
  padding: 10px 38px 10px 14px;
  border: 1.5px solid var(--wc-border);
  border-radius: 10px;
  font-size: 14px;
  outline: none;
  transition: border-color 0.15s;
}

.products-search-input:focus {
  border-color: var(--wc-green-bright);
}

.products-category-select {
  padding: 10px 14px;
  border: 1.5px solid var(--wc-border);
  border-radius: 10px;
  font-size: 14px;
  outline: none;
  background: #ffffff;
}

.btn-reset-filter {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 10px 14px;
  background: #f1f5f9;
  border: 1px solid var(--wc-border);
  border-radius: 10px;
  color: var(--wc-text-muted);
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
}

.btn-add-product {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 10px 16px;
  background: var(--wc-green-bright);
  color: #fff;
  border: none;
  border-radius: 10px;
  font-weight: 700;
  font-size: 13.5px;
  cursor: pointer;
  margin-right: auto;
}

.category-tabs {
  display: flex;
  align-items: center;
  gap: 8px;
  overflow-x: auto;
  padding-top: 6px;
  border-top: 1px solid var(--wc-border);
}

.tab-btn {
  padding: 6px 14px;
  border-radius: 999px;
  border: 1px solid var(--wc-border);
  background: var(--wc-gray-bg);
  color: var(--wc-text-dark);
  font-size: 12.5px;
  font-weight: 600;
  cursor: pointer;
  white-space: nowrap;
  transition: all 0.15s;
}

.tab-btn:hover {
  border-color: var(--wc-green-bright);
  color: var(--wc-green-bright);
}

.tab-btn--active {
  background: var(--wc-green-bright);
  color: #ffffff;
  border-color: var(--wc-green-bright);
  font-weight: 700;
}

/* 4. مكون الحالة الفارغة الجذاب (Empty State Component) */
.empty-state-card {
  background: #ffffff;
  border: 1px solid var(--wc-border);
  border-radius: 16px;
  padding: 48px 24px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  box-shadow: var(--wc-shadow);
}

.empty-state-icon-wrap {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: var(--wc-green-light);
  color: var(--wc-green-bright);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 4px;
}

.empty-state-title {
  font-size: 18px;
  font-weight: 800;
  color: var(--wc-text-dark);
  margin: 0;
}

.empty-state-subtitle {
  font-size: 13.5px;
  color: var(--wc-text-muted);
  max-width: 440px;
  margin: 0 0 12px 0;
  line-height: 1.6;
}

.btn-empty-action {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 800;
  cursor: pointer;
  border: 1px solid var(--wc-border);
  background: var(--wc-gray-bg);
  color: var(--wc-text-dark);
  transition: all 0.15s;
}

.btn-empty-action--primary {
  background: var(--wc-green-bright);
  color: #ffffff;
  border: none;
  box-shadow: 0 4px 14px rgba(34, 168, 62, 0.25);
}

.btn-empty-action--primary:hover {
  background: var(--wc-green);
  transform: translateY(-1px);
}

/* الأنماط الخاصة بالحقول وتأثيرات النافذة المنبثقة */
.form-section {
  background: #f8fafc;
  border: 1px solid var(--wc-border);
  border-radius: 12px;
  padding: 16px;
}

.form-section-title {
  font-size: 14px;
  font-weight: 800;
  color: var(--wc-text-dark);
  margin: 0 0 14px 0;
  display: flex;
  align-items: center;
  gap: 8px;
  padding-bottom: 8px;
  border-bottom: 1px solid var(--wc-border);
}

.working-hours-wrap {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.quick-presets {
  display: flex;
  gap: 8px;
}

.preset-btn {
  padding: 4px 10px;
  font-size: 12px;
  border: 1px solid var(--wc-border);
  border-radius: 6px;
  background: #ffffff;
  color: var(--wc-text-muted);
  cursor: pointer;
  transition: all 0.15s;
}

.preset-btn:hover {
  border-color: var(--wc-green-bright);
  color: var(--wc-green-bright);
  background: var(--wc-green-light);
}

.input-prefix-wrap {
  display: flex;
  align-items: center;
  border: 1px solid var(--wc-border);
  border-radius: 8px;
  background: #ffffff;
  overflow: hidden;
}

.input-prefix {
  background: #f1f5f9;
  color: #64748b;
  padding: 8px 10px;
  font-size: 13px;
  font-weight: 600;
  border-right: 1px solid var(--wc-border);
  user-select: none;
}

.input-with-prefix {
  border: none !important;
  border-radius: 0 !important;
  padding: 8px 10px !important;
  flex: 1;
}

.image-uploads-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}

.image-upload-area {
  border: 2px dashed var(--wc-border);
  border-radius: 12px;
  padding: 16px;
  text-align: center;
  cursor: pointer;
  background: #ffffff;
  transition: all 0.2s;
  min-height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.image-upload-area:hover {
  border-color: var(--wc-green-bright);
  background: var(--wc-green-light);
}

.image-upload-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
}

.image-upload-placeholder p {
  margin: 0;
  font-size: 13px;
  color: var(--wc-text-dark);
}

.image-upload-placeholder span {
  font-size: 11px;
  color: var(--wc-text-muted);
}

.image-preview-wrap {
  position: relative;
  width: 100%;
  height: 100px;
}

.image-preview {
  width: 100%;
  height: 100%;
  object-fit: contain;
  border-radius: 8px;
}

.image-preview-remove {
  position: absolute;
  top: -8px;
  right: -8px;
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: #ef4444;
  color: #fff;
  border: none;
  font-size: 12px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form-hint {
  font-size: 11px;
  font-weight: 600;
  color: #94a3b8;
  background: #f1f5f9;
  padding: 2px 7px;
  border-radius: 20px;
  margin-right: 6px;
}

@media (max-width: 992px) {
  .store-main-layout {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 600px) {
  .image-uploads-grid {
    grid-template-columns: 1fr;
  }
}

/* ══════════════════════════════════
   أشكال وصورة المنتج وأنماط مودال الإضافة والتعديل
══════════════════════════════════ */
.product-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}

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
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
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

.required-star {
  color: #ef4444;
  font-weight: bold;
}

.field-error {
  font-size: 12px;
  color: #ef4444;
  margin-top: 4px;
  display: block;
}

.form-input--error {
  border-color: #ef4444 !important;
}

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
</style>


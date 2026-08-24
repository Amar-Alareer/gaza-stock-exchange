<template>
    <div class="dashboard-page">
        <AdminSidebar />

        <div class="dashboard-main">
            <AdminHeader @logout="handleLogout" />

            <div class="dashboard-content">
                <!-- ترويسة الصفحة وشريط الفلترة والأدوات الموحد -->
                <div class="stores-page-header">
                    <div class="stores-page-header__text">
                        <h1 class="stores-page-title">
                            <StoreIcon
                                :size="24"
                                style="color: var(--wc-green-bright)"
                            />
                            إدارة المتاجر
                        </h1>
                        <p class="stores-page-subtitle">
                            عرض وإدارة جميع المتاجر المسجلة والتحكم ببياناتها
                        </p>
                    </div>

                    <button
                        class="btn-add"
                        @click="openAddModal"
                        id="btn-add-store"
                    >
                        <PlusIcon :size="16" />
                        إضافة متجر جديد
                    </button>
                </div>

                <!-- شريط البحث والفلاتر الموحد (Flex space-between) -->
                <div class="stores-filter-bar">
                    <!-- اليسار: شريط البحث -->
                    <div class="stores-search-wrap">
                        <SearchIcon :size="16" class="stores-search-icon" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="ابحث باسم المتجر، الهاتف، أو العنوان..."
                            class="stores-search-input"
                            @input="onSearchChange"
                            id="stores-search-input"
                        />
                    </div>

                    <!-- اليمين: الفلاتر + عدد المتاجر + أزرار العرض -->
                    <div class="stores-filter-controls">
                        <!-- عدد المتاجر -->
                        <div class="stores-count-badge" v-if="!isLoading">
                            <span>{{ filteredStores.length }}</span> متجر
                        </div>

                        <!-- فلتر المحافظة -->
                        <select
                            v-model="selectedGovernorate"
                            class="stores-filter-select"
                            id="stores-region-filter"
                        >
                            <option value="">كل المحافظات</option>
                            <option v-for="gov in GAZA_GOVERNORATES" :key="gov.value" :value="gov.value">
                                {{ gov.label }}
                            </option>
                        </select>

                        <!-- خيار الترتيب -->
                        <select
                            v-model="sortBy"
                            class="stores-filter-select"
                            id="stores-sort-select"
                        >
                            <option value="latest">الأحدث إضافة</option>
                            <option value="oldest">الأقدم إضافة</option>
                            <option value="name_asc">الاسم (أ - ي)</option>
                            <option value="name_desc">الاسم (ي - أ)</option>
                        </select>

                        <!-- فاصل رأسي -->
                        <div class="stores-toolbar-divider"></div>

                        <!-- تبديل العرض (كروت / قائمة) -->
                        <div class="view-mode-toggle">
                            <button
                                type="button"
                                class="view-btn"
                                :class="{ 'view-btn--active': viewMode === 'grid' }"
                                @click="viewMode = 'grid'"
                                title="عرض الكروت"
                            >
                                <GridIcon :size="16" />
                            </button>
                            <button
                                type="button"
                                class="view-btn"
                                :class="{ 'view-btn--active': viewMode === 'list' }"
                                @click="viewMode = 'list'"
                                title="عرض القائمة"
                            >
                                <ListIcon :size="16" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- حالة التحميل -->
                <div v-if="isLoading" class="loading-state">
                    <div class="loading-spinner"></div>
                    <span>جارٍ تحميل المتاجر...</span>
                </div>

                <!-- حالة الخطأ -->
                <div v-else-if="errorMsg" class="error-banner">
                    <AlertCircleIcon :size="20" />
                    <span>{{ errorMsg }}</span>
                    <button class="retry-btn" @click="fetchStores">
                        إعادة المحاولة
                    </button>
                </div>

                <!-- عرض المتاجر -->
                <template v-else>
                    <div v-if="filteredStores.length === 0" class="empty-state">
                        <div class="empty-icon">🏪</div>
                        <p>لا توجد متاجر مطابقة للبحث</p>
                        <button class="btn-add" @click="openAddModal">
                            <PlusIcon :size="16" />
                            أضف أول متجر
                        </button>
                    </div>

                    <!-- 1. نمط عرض الكروت (Grid View) -->
                    <div v-else-if="viewMode === 'grid'" class="stores-grid">
                        <div
                            v-for="store in filteredStores"
                            :key="store.id"
                            class="store-card"
                            style="cursor: pointer"
                            @click="
                                $router.push({
                                    name: 'StoreDetails',
                                    params: { id: store.id },
                                })
                            "
                        >
                            <!-- غلاف المتجر والشعار وشارة المنطقة والحالة -->
                            <div class="store-card__image-wrap">
                                <img
                                    v-if="store.cover_image_url || store.image_url"
                                    :src="store.cover_image_url || store.image_url"
                                    :alt="store.name"
                                    class="store-card__image"
                                    @error="handleImageError($event)"
                                />
                                <div
                                    v-else
                                    class="store-card__image-placeholder"
                                >
                                    <StoreIcon :size="40" />
                                </div>

                                <!-- شارة حالة المتجر (نشط) -->
                                <div class="store-card__status-tag">
                                    <span class="status-dot"></span>
                                    <span>نشط</span>
                                </div>

                                <!-- شارة المنطقة الخلفية الشفافة blur -->
                                <div class="store-card__region-tag">
                                    {{ store.region || store.address || "غير محدد" }}
                                </div>

                                <!-- شعار المتجر الدائري المتداخل على الغلاف -->
                                <div class="store-card__logo-avatar">
                                    <img
                                        v-if="store.image_url"
                                        :src="store.image_url"
                                        :alt="store.name"
                                    />
                                    <StoreIcon v-else :size="20" />
                                </div>
                            </div>

                            <!-- محتوى البطاقة -->
                            <div class="store-card__body">
                                <div class="store-card__title-row">
                                    <h3 class="store-card__name">
                                        {{ store.name }}
                                    </h3>
                                </div>

                                <div class="store-card__info">
                                    <div
                                        class="store-card__info-row"
                                        v-if="store.address"
                                    >
                                        <MapPinIcon :size="14" />
                                        <span>{{ store.address }}</span>
                                    </div>

                                    <div
                                        class="store-card__info-row"
                                        v-if="store.phone"
                                    >
                                        <PhoneIcon :size="14" />
                                        <span dir="ltr">{{ store.phone }}</span>
                                    </div>
                                    <div
                                        class="store-card__info-row store-card__info-row--muted"
                                        v-else
                                    >
                                        <PhoneIcon :size="14" />
                                        <span>لا يوجد رقم هاتف</span>
                                    </div>

                                    <div
                                        class="store-card__info-row"
                                        v-if="store.working_hours"
                                    >
                                        <ClockIcon :size="14" />
                                        <span>{{ store.working_hours }}</span>
                                    </div>
                                </div>

                                <!-- أزرار التواصل وساعات العمل/المنتجات -->
                                <div class="store-card__footer-meta">
                                    <div
                                        class="store-card__socials"
                                        v-if="
                                            store.facebook_url ||
                                            store.instagram_url ||
                                            store.telegram_url
                                        "
                                    >
                                        <a
                                            v-if="store.facebook_url"
                                            :href="store.facebook_url"
                                            target="_blank"
                                            class="social-badge social-badge--fb"
                                            title="فيسبوك"
                                            @click.stop
                                        >
                                            <span>f</span>
                                        </a>
                                        <a
                                            v-if="store.instagram_url"
                                            :href="store.instagram_url"
                                            target="_blank"
                                            class="social-badge social-badge--ig"
                                            title="إنستغرام"
                                            @click.stop
                                        >
                                            <span>ig</span>
                                        </a>
                                        <a
                                            v-if="store.telegram_url"
                                            :href="store.telegram_url"
                                            target="_blank"
                                            class="social-badge social-badge--tg"
                                            title="تيليغرام"
                                            @click.stop
                                        >
                                            <span>tg</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- أزرار الإجراءات -->
                            <div class="store-card__actions">
                                <router-link
                                    :to="{
                                        name: 'StoreDetails',
                                        params: { id: store.id },
                                    }"
                                    class="icon-btn"
                                    title="رؤية التفاصيل"
                                    style="
                                        color: var(--wc-green);
                                        background: var(--wc-green-light);
                                    "
                                    @click.stop
                                >
                                    <EyeIcon :size="15" />
                                </router-link>
                                <button
                                    class="icon-btn icon-btn--edit"
                                    @click.stop="openEditModal(store)"
                                    :id="`btn-edit-store-${store.id}`"
                                    title="تعديل"
                                >
                                    <EditIcon :size="15" />
                                </button>
                                <button
                                    class="icon-btn icon-btn--delete"
                                    @click.stop="openDeleteModal(store)"
                                    :id="`btn-delete-store-${store.id}`"
                                    title="حذف"
                                >
                                    <TrashIcon :size="15" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 2. نمط عرض القائمة (List View) -->
                    <div v-else class="stores-list-table-wrap">
                        <table class="stores-table">
                            <thead>
                                <tr>
                                    <th>المتجر</th>
                                    <th>العنوان / المنطقة</th>
                                    <th>الهاتف</th>
                                    <th>ساعات العمل</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="store in filteredStores"
                                    :key="store.id"
                                    @click="
                                        $router.push({
                                            name: 'StoreDetails',
                                            params: { id: store.id },
                                        })
                                    "
                                    style="cursor: pointer;"
                                >
                                    <td>
                                        <div class="table-store-cell">
                                            <img
                                                v-if="store.image_url"
                                                :src="store.image_url"
                                                class="table-store-img"
                                            />
                                            <div v-else class="table-store-img-placeholder">
                                                <StoreIcon :size="18" />
                                            </div>
                                            <div>
                                                <strong class="table-store-name">{{ store.name }}</strong>
                                                <span class="table-store-date" v-if="store.created_at">{{ store.created_at }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ store.address || store.region || "غير محدد" }}</td>
                                    <td dir="ltr" style="text-align: right;">{{ store.phone || "—" }}</td>
                                    <td>{{ store.working_hours || "—" }}</td>
                                    <td>
                                        <span class="table-status-badge">
                                            <span class="status-dot"></span> نشط
                                        </span>
                                    </td>
                                    <td @click.stop>
                                        <div class="table-actions">
                                            <router-link
                                                :to="{ name: 'StoreDetails', params: { id: store.id } }"
                                                class="icon-btn"
                                                title="تفاصيل"
                                            >
                                                <EyeIcon :size="15" />
                                            </router-link>
                                            <button
                                                class="icon-btn icon-btn--edit"
                                                @click="openEditModal(store)"
                                                title="تعديل"
                                            >
                                                <EditIcon :size="15" />
                                            </button>
                                            <button
                                                class="icon-btn icon-btn--delete"
                                                @click="openDeleteModal(store)"
                                                title="حذف"
                                            >
                                                <TrashIcon :size="15" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>
        </div>

        <!-- ══════════════════════════════════════════
         Modal: إضافة / تعديل متجر
    ══════════════════════════════════════════ -->
        <!-- ══════════════════════════════════════════
         Modal: إضافة / تعديل متجر
    ══════════════════════════════════════════ -->
        <div
            v-if="showFormModal"
            class="modal-overlay"
            @click.self="closeFormModal"
        >
            <div class="modal modal--lg" role="dialog" aria-modal="true">
                <div class="modal__header">
                    <h3
                        class="modal__title"
                        style="display: flex; align-items: center; gap: 8px"
                    >
                        <component
                            :is="isEditMode ? EditIcon : PlusIcon"
                            :size="18"
                            style="color: var(--wc-green-bright)"
                        />
                        {{
                            isEditMode
                                ? "تعديل بيانات المتجر"
                                : "إضافة متجر جديد"
                        }}
                    </h3>
                    <button class="modal__close" @click="closeFormModal">
                        ✕
                    </button>
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
                                <label class="form-label"
                                    >اسم المتجر
                                    <span class="required">*</span></label
                                >
                                <input
                                    v-model="storeForm.name"
                                    type="text"
                                    class="form-input"
                                    placeholder="مثال: سوبر ماركت الأمل"
                                    id="store-form-name"
                                />
                            </div>

                            <!-- رقم الهاتف -->
                            <div class="form-group">
                                <label class="form-label"
                                    >رقم الهاتف
                                    <span class="required">*</span></label
                                >
                                <input
                                    v-model="storeForm.phone"
                                    type="tel"
                                    class="form-input"
                                    placeholder="059xxxxxxx"
                                    dir="ltr"
                                    id="store-form-phone"
                                    @input="formatPhone"
                                />
                            </div>

                            <!-- المحافظة (إجباري) -->
                            <div class="form-group">
                                <label class="form-label">
                                    المحافظة <span class="required">*</span>
                                </label>
                                <select
                                    v-model="storeForm.governorate"
                                    class="form-input"
                                    id="store-form-governorate"
                                >
                                    <option value="">اختر المحافظة...</option>
                                    <option v-for="gov in GAZA_GOVERNORATES" :key="gov.value" :value="gov.value">
                                        {{ gov.label }}
                                    </option>
                                </select>
                            </div>

                            <!-- الحي / المنطقة الفرعية (اختياري) -->
                            <div class="form-group">
                                <label class="form-label">
                                    الحي / المنطقة الفرعية
                                    <span class="form-hint">اختياري</span>
                                </label>
                                <input
                                    v-model="storeForm.sub_area"
                                    type="text"
                                    class="form-input"
                                    :placeholder="subAreaPlaceholder"
                                    id="store-form-sub-area"
                                />
                            </div>

                            <!-- العنوان التفصيلي / الشارع (اختياري) -->
                            <div class="form-group form-group--full">
                                <label class="form-label">العنوان التفصيلي / الشارع
                                    <span class="form-hint">اختياري</span>
                                </label>
                                <input
                                    v-model="storeForm.address"
                                    type="text"
                                    class="form-input"
                                    placeholder="مثال: شارع عمر المختار - مقابل المخبز"
                                    id="store-form-address"
                                />
                            </div>

                            <!-- ساعات العمل -->
                            <div class="form-group form-group--full">
                                <label class="form-label">ساعات العمل</label>
                                <div class="working-hours-wrap">
                                    <input
                                        v-model="storeForm.working_hours"
                                        type="text"
                                        class="form-input"
                                        placeholder="مثال: من 8:00 صباحاً إلى 10:00 مساءً"
                                        id="store-form-working-hours"
                                    />
                                    <div class="quick-presets">
                                        <button
                                            type="button"
                                            class="preset-btn"
                                            @click="
                                                storeForm.working_hours =
                                                    'مفتوح 24 ساعة'
                                            "
                                        >
                                            مفتوح 24 ساعة
                                        </button>
                                        <button
                                            type="button"
                                            class="preset-btn"
                                            @click="
                                                storeForm.working_hours =
                                                    'من 8:00 صباحاً - 10:00 مساءً'
                                            "
                                        >
                                            8:00 ص - 10:00 م
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- القسم الثاني: موقع المتجر على الخريطة -->
                    <div class="form-section" style="margin-top: 14px">
                        <h4 class="form-section-title">
                            <MapPinIcon :size="16" /> موقع المتجر (GPS / خرائط جوجل)
                        </h4>
                        <div class="form-grid">
                            <div class="form-group form-group--full">
                                <label class="form-label">رابط موقع المتجر على الخريطة أو الإحداثيات</label>
                                <input
                                    v-model="storeForm.map_location"
                                    type="text"
                                    class="form-input"
                                    placeholder="أدخل رابط خرائط جوجل أو الإحداثيات (مثال: 31.5, 34.45)"
                                    dir="ltr"
                                    id="store-form-map"
                                    @change="parseMapLocation"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- القسم الثالث: حسابات التواصل الاجتماعي -->
                    <div class="form-section" style="margin-top: 14px">
                        <h4 class="form-section-title">
                            <GlobeIcon :size="16" /> حسابات التواصل الاجتماعي (اختياري)
                        </h4>
                        <div class="form-grid">
                            <!-- رابط فيسبوك -->
                            <div class="form-group">
                                <label class="form-label">حساب فيسبوك</label>
                                <div class="input-prefix-wrap" dir="ltr">
                                    <span class="input-prefix">facebook.com/</span>
                                    <input
                                        v-model="storeForm.facebook_url"
                                        type="text"
                                        class="form-input input-with-prefix"
                                        placeholder="username"
                                        id="store-form-fb"
                                    />
                                </div>
                            </div>

                            <!-- رابط إنستغرام -->
                            <div class="form-group">
                                <label class="form-label">حساب إنستغرام</label>
                                <div class="input-prefix-wrap" dir="ltr">
                                    <span class="input-prefix">instagram.com/</span>
                                    <input
                                        v-model="storeForm.instagram_url"
                                        type="text"
                                        class="form-input input-with-prefix"
                                        placeholder="username"
                                        id="store-form-ig"
                                    />
                                </div>
                            </div>

                            <!-- رابط تيليغرام -->
                            <div class="form-group">
                                <label class="form-label">قناة / حساب تيليغرام</label>
                                <div class="input-prefix-wrap" dir="ltr">
                                    <span class="input-prefix">t.me/</span>
                                    <input
                                        v-model="storeForm.telegram_url"
                                        type="text"
                                        class="form-input input-with-prefix"
                                        placeholder="username"
                                        id="store-form-tg"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- القسم الرابع: صور المتجر (الشعار والغلاف) -->
                    <div class="form-section" style="margin-top: 14px">
                        <h4 class="form-section-title">
                            <ImageIcon :size="16" /> صور المتجر
                        </h4>
                        <div class="image-uploads-grid">
                            <!-- رفع الشعار -->
                            <div class="form-group">
                                <label class="form-label">شعار المتجر (Logo)</label>
                                <div
                                    class="image-upload-area"
                                    @click="triggerFileInput"
                                    @dragover.prevent
                                    @drop.prevent="handleDrop"
                                    id="store-image-upload"
                                >
                                    <input
                                        ref="fileInput"
                                        type="file"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        style="display: none"
                                        @change="handleFileSelect"
                                        id="store-form-image-input"
                                    />
                                    <div v-if="imagePreview" class="image-preview-wrap">
                                        <img
                                            :src="imagePreview"
                                            alt="معاينة الشعار"
                                            class="image-preview"
                                        />
                                        <button
                                            type="button"
                                            class="image-preview-remove"
                                            @click.stop="removeImage"
                                            title="إزالة الصورة"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                    <div v-else class="image-upload-placeholder">
                                        <UploadCloudIcon
                                            :size="30"
                                            style="color: var(--wc-green-bright); opacity: 0.7;"
                                        />
                                        <p>رفع <strong>الشعار (Logo)</strong></p>
                                        <span>PNG, JPG, WEBP — حتى 3MB</span>
                                    </div>
                                </div>
                            </div>

                            <!-- رفع الغلاف -->
                            <div class="form-group">
                                <label class="form-label">صورة غلاف المتجر (Banner)</label>
                                <div
                                    class="image-upload-area"
                                    @click="triggerCoverFileInput"
                                    @dragover.prevent
                                    @drop.prevent="handleCoverDrop"
                                    id="store-cover-upload"
                                >
                                    <input
                                        ref="coverFileInput"
                                        type="file"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        style="display: none"
                                        @change="handleCoverFileSelect"
                                        id="store-form-cover-input"
                                    />
                                    <div v-if="coverImagePreview" class="image-preview-wrap">
                                        <img
                                            :src="coverImagePreview"
                                            alt="معاينة الغلاف"
                                            class="image-preview"
                                        />
                                        <button
                                            type="button"
                                            class="image-preview-remove"
                                            @click.stop="removeCoverImage"
                                            title="إزالة الصورة"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                    <div v-else class="image-upload-placeholder">
                                        <UploadCloudIcon
                                            :size="30"
                                            style="color: var(--wc-green-bright); opacity: 0.7;"
                                        />
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
                    <button class="btn-cancel" @click="closeFormModal">
                        إلغاء
                    </button>
                    <button
                        class="btn-save"
                        @click="submitForm"
                        :disabled="isSaving"
                        id="store-form-submit"
                    >
                        <span v-if="isSaving" class="btn-spinner"></span>
                        {{
                            isSaving
                                ? "جارٍ الحفظ..."
                                : isEditMode
                                  ? "تحديث بيانات المتجر"
                                  : "حفظ وإضافة المتجر"
                        }}
                    </button>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════
         Modal: تأكيد الحذف
    ══════════════════════════════════════════ -->
        <div
            v-if="showDeleteModal"
            class="modal-overlay"
            @click.self="showDeleteModal = false"
        >
            <div class="modal modal--sm" role="dialog">
                <div class="modal__header">
                    <h3
                        class="modal__title modal__title--danger"
                        style="display: flex; align-items: center; gap: 8px"
                    >
                        <TrashIcon :size="18" />
                        تأكيد حذف المتجر
                    </h3>
                    <button
                        class="modal__close"
                        @click="showDeleteModal = false"
                    >
                        ✕
                    </button>
                </div>
                <div class="modal__body">
                    <p class="delete-warning">
                        هل أنت متأكد من حذف متجر:
                        <strong>{{ storeToDelete?.name }}</strong
                        >؟
                    </p>
                    <p class="delete-note">لا يمكن التراجع عن هذا الإجراء.</p>
                    <div
                        v-if="deleteError"
                        class="error-alert"
                        style="margin-top: 8px"
                    >
                        <span>{{ deleteError }}</span>
                    </div>
                </div>
                <div class="modal__footer">
                    <button class="btn-cancel" @click="showDeleteModal = false">
                        إلغاء
                    </button>
                    <button
                        class="btn-delete"
                        @click="confirmDelete"
                        :disabled="isDeleting"
                        id="store-delete-confirm"
                    >
                        <span v-if="isDeleting" class="btn-spinner"></span>
                        {{ isDeleting ? "جارٍ الحذف..." : "نعم، احذف" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminSidebar from "../components/AdminSidebar.vue";
import AdminHeader from "../components/AdminHeader.vue";
import apiClient from "../api/axios.js";
import { globalState } from "../state.js";

// ════════════════════════════════════════════════
// المحافظات الخمس لقطاع غزة (ثابتة)
// ════════════════════════════════════════════════
const GAZA_GOVERNORATES = [
    {
        value: "شمال_غزة",
        label: "محافظة شمال غزة",
        hint: "مثال: جباليا، بيت لاهيا، بيت حانون",
    },
    {
        value: "غزة",
        label: "محافظة غزة",
        hint: "مثال: الرمال، الزيتون، الشجاعية، النصر، الشيخ رضوان",
    },
    {
        value: "الوسطى",
        label: "محافظة الوسطى (دير البلح)",
        hint: "مثال: النصيرات، البريج، المغازي، دير البلح، الزوايدة",
    },
    {
        value: "خان_يونس",
        label: "محافظة خان يونس",
        hint: "مثال: خان يونس البلد، الأمل، عبسان، بني سهيلا",
    },
    {
        value: "رفح",
        label: "محافظة رفح",
        hint: "مثال: الشابورة، تل السلطان، الجنينة، حي البرازيل",
    },
];

import {
    Store as StoreIcon,
    Plus as PlusIcon,
    Edit as EditIcon,
    Trash2 as TrashIcon,
    Search as SearchIcon,
    Phone as PhoneIcon,
    Calendar as CalendarIcon,
    UploadCloud as UploadCloudIcon,
    AlertCircle as AlertCircleIcon,
    Eye as EyeIcon,
    Clock as ClockIcon,
    MapPin as MapPinIcon,
    Globe as GlobeIcon,
    Image as ImageIcon,
    LayoutGrid as GridIcon,
    List as ListIcon,
    Package as PackageIcon,
} from "@lucide/vue";

export default {
    name: "AdminStores",
    components: {
        AdminSidebar,
        AdminHeader,
        StoreIcon,
        PlusIcon,
        EditIcon,
        TrashIcon,
        SearchIcon,
        PhoneIcon,
        CalendarIcon,
        UploadCloudIcon,
        AlertCircleIcon,
        EyeIcon,
        ClockIcon,
        MapPinIcon,
        GlobeIcon,
        ImageIcon,
        GridIcon,
        ListIcon,
        PackageIcon,
    },

    data() {
        return {
            // بيانات المتاجر
            stores: [],
            regions: [],
            isLoading: true,
            errorMsg: "",

            // بحث وفلترة وترتيب ونمط العرض
            searchQuery: "",
            selectedRegion: "",
            selectedGovernorate: "",
            sortBy: "latest",
            viewMode: "grid", // 'grid' | 'list'
            searchTimer: null,

            // نافذة الإضافة/التعديل
            showFormModal: false,
            isEditMode: false,
            isSaving: false,
            formError: "",
            storeForm: this.emptyForm(),
            imagePreview: null,
            imageFile: null,
            coverImagePreview: null,
            coverImageFile: null,

            // نافذة الحذف
            showDeleteModal: false,
            isDeleting: false,
            deleteError: "",
            storeToDelete: null,
        };
    },

    computed: {
        GAZA_GOVERNORATES() {
            return GAZA_GOVERNORATES;
        },

        subAreaPlaceholder() {
            const gov = GAZA_GOVERNORATES.find(
                (g) => g.value === this.storeForm.governorate,
            );
            return gov ? gov.hint : "مثال: الرمال - شارع عمر المختار";
        },

        filteredStores() {
            let list = [...this.stores];
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                list = list.filter(
                    (s) =>
                        s.name.toLowerCase().includes(q) ||
                        (s.phone && s.phone.includes(q)) ||
                        (s.address && s.address.toLowerCase().includes(q)),
                );
            }
            if (this.selectedGovernorate) {
                list = list.filter(
                    (s) => (s.governorate || "") === this.selectedGovernorate,
                );
            }
            // الترتيب
            if (this.sortBy === "latest") {
                list.sort((a, b) => b.id - a.id);
            } else if (this.sortBy === "oldest") {
                list.sort((a, b) => a.id - b.id);
            } else if (this.sortBy === "name_asc") {
                list.sort((a, b) => a.name.localeCompare(b.name, "ar"));
            } else if (this.sortBy === "name_desc") {
                list.sort((a, b) => b.name.localeCompare(a.name, "ar"));
            }
            return list;
        },
    },

    mounted() {
        this.fetchStores();
    },

    methods: {
        emptyForm() {
            return {
                id: null,
                name: "",
                governorate: "",
                sub_area: "",
                address: "",
                region_id: "",
                phone: "",
                working_hours: "",
                latitude: "",
                longitude: "",
                map_location: "",
                facebook_url: "",
                instagram_url: "",
                telegram_url: "",
            };
        },

        /** جلب المتاجر من API */
        async fetchStores() {
            this.isLoading = true;
            this.errorMsg = "";
            try {
                const params = {};
                if (this.selectedRegion) params.region_id = this.selectedRegion;

                const res = await apiClient.get("/admin/stores", { params });
                if (res.data.status === "success") {
                    this.stores = res.data.stores;
                    this.regions = res.data.regions;
                }
            } catch (err) {
                this.errorMsg =
                    "تعذر الاتصال بالخادم. يرجى التحقق من تشغيل Laravel.";
                console.error(err);
            } finally {
                this.isLoading = false;
            }
        },

        /** بحث مع تأخير (debounce) */
        onSearchChange() {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => {
                // البحث محلي فقط
            }, 300);
        },

        // ════════════════════════════
        // إدارة نموذج الإضافة / التعديل
        // ════════════════════════════
        openAddModal() {
            this.isEditMode = false;
            this.storeForm = this.emptyForm();
            this.imagePreview = null;
            this.imageFile = null;
            this.coverImagePreview = null;
            this.coverImageFile = null;
            this.formError = "";
            this.showFormModal = true;
        },

        openEditModal(store) {
            this.isEditMode = true;
            this.storeForm = {
                id: store.id,
                name: store.name || "",
                governorate: store.governorate || "",
                sub_area: store.sub_area || "",
                address: store.address || "",
                region_id: store.region_id || "",
                phone: store.phone || "",
                working_hours: store.working_hours || "",
                latitude: store.latitude || "",
                longitude: store.longitude || "",
                map_location:
                    store.latitude && store.longitude
                        ? `${store.latitude}, ${store.longitude}`
                        : "",
                facebook_url: this.extractUsername(
                    store.facebook_url,
                    "facebook.com",
                ),
                instagram_url: this.extractUsername(
                    store.instagram_url,
                    "instagram.com",
                ),
                telegram_url: this.extractUsername(
                    store.telegram_url,
                    "t.me",
                ),
            };
            this.imagePreview = store.image_url || null;
            this.imageFile = null;
            this.coverImagePreview = store.cover_image_url || null;
            this.coverImageFile = null;
            this.formError = "";
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
            this.storeForm = this.emptyForm();
            this.imagePreview = null;
            this.imageFile = null;
            this.coverImagePreview = null;
            this.coverImageFile = null;
            this.formError = "";
        },

        // إدارة رفع ملفات الصور
        triggerFileInput() {
            if (this.$refs.fileInput) this.$refs.fileInput.click();
        },
        triggerCoverFileInput() {
            if (this.$refs.coverFileInput) this.$refs.coverFileInput.click();
        },

        handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) this.processImageFile(file);
        },
        handleCoverFileSelect(e) {
            const file = e.target.files[0];
            if (file) this.processCoverImageFile(file);
        },

        handleDrop(e) {
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith("image/"))
                this.processImageFile(file);
        },
        handleCoverDrop(e) {
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith("image/"))
                this.processCoverImageFile(file);
        },

        processImageFile(file) {
            if (file.size > 3 * 1024 * 1024) {
                this.formError = "حجم شعار المتجر يجب ألا يتجاوز 3 ميجابايت";
                return;
            }
            this.imageFile = file;
            this.formError = "";
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        processCoverImageFile(file) {
            if (file.size > 5 * 1024 * 1024) {
                this.formError = "حجم صورة الغلاف يجب ألا يتجاوز 5 ميجابايت";
                return;
            }
            this.coverImageFile = file;
            this.formError = "";
            const reader = new FileReader();
            reader.onload = (e) => {
                this.coverImagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeImage() {
            this.imageFile = null;
            this.imagePreview = null;
            if (this.$refs.fileInput) this.$refs.fileInput.value = "";
        },

        removeCoverImage() {
            this.coverImageFile = null;
            this.coverImagePreview = null;
            if (this.$refs.coverFileInput) this.$refs.coverFileInput.value = "";
        },

        handleImageError(e) {
            e.target.style.display = "none";
        },

        // تنظيف وتنسيق المدخلات
        formatPhone() {
            if (this.storeForm.phone) {
                this.storeForm.phone = this.storeForm.phone.replace(/[^\d+]/g, "");
            }
        },

        extractUsername(url, domain) {
            if (!url) return "";
            let u = url.trim();
            u = u.replace(
                new RegExp(`^(https?:\\/\\/)?(www\\.)?${domain}\\/`, "i"),
                "",
            );
            return u;
        },

        cleanSocialUrl(url, domain) {
            if (!url || !url.trim()) return "";
            let trimmed = url.trim();
            if (trimmed.startsWith("http://") || trimmed.startsWith("https://")) {
                return trimmed;
            }
            trimmed = trimmed.replace(
                new RegExp(`^(https?:\\/\\/)?(www\\.)?${domain}\\/`, "i"),
                "",
            );
            return `https://${domain}/${trimmed.replace(/^\/+/, "")}`;
        },

        parseMapLocation() {
            if (!this.storeForm.map_location) return;
            const loc = this.storeForm.map_location.trim();
            const coordsMatch = loc.match(/(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);
            if (coordsMatch) {
                this.storeForm.latitude = coordsMatch[1];
                this.storeForm.longitude = coordsMatch[2];
            }
        },

        async submitForm() {
            if (!this.storeForm.name.trim()) {
                this.formError = "اسم المتجر مطلوب";
                return;
            }
            if (!this.storeForm.governorate) {
                this.formError = "يجب تحديد المحافظة أولاً";
                return;
            }
            if (!this.storeForm.phone || !this.storeForm.phone.trim()) {
                this.formError = "رقم الهاتف مطلوب";
                return;
            }

            this.isSaving = true;
            this.formError = "";

            try {
                const formData = new FormData();
                formData.append("name", this.storeForm.name.trim());
                formData.append("governorate", this.storeForm.governorate);
                formData.append("sub_area", this.storeForm.sub_area ? this.storeForm.sub_area.trim() : "");
                formData.append("address", this.storeForm.address ? this.storeForm.address.trim() : "");
                formData.append("phone", this.storeForm.phone.trim());

                if (this.storeForm.region_id) {
                    formData.append("region_id", this.storeForm.region_id);
                }
                if (this.storeForm.working_hours) {
                    formData.append(
                        "working_hours",
                        this.storeForm.working_hours.trim(),
                    );
                }
                if (this.storeForm.latitude) {
                    formData.append("latitude", this.storeForm.latitude);
                }
                if (this.storeForm.longitude) {
                    formData.append("longitude", this.storeForm.longitude);
                }

                // حسابات التواصل Social URLs
                const fb = this.cleanSocialUrl(
                    this.storeForm.facebook_url,
                    "facebook.com",
                );
                const ig = this.cleanSocialUrl(
                    this.storeForm.instagram_url,
                    "instagram.com",
                );
                const tg = this.cleanSocialUrl(
                    this.storeForm.telegram_url,
                    "t.me",
                );

                if (fb) formData.append("facebook_url", fb);
                if (ig) formData.append("instagram_url", ig);
                if (tg) formData.append("telegram_url", tg);

                if (this.imageFile) formData.append("image", this.imageFile);
                if (this.coverImageFile)
                    formData.append("cover_image", this.coverImageFile);

                let res;
                if (this.isEditMode) {
                    res = await apiClient.post(
                        `/admin/stores/${this.storeForm.id}`,
                        formData,
                        {
                            headers: { "Content-Type": "multipart/form-data" },
                        },
                    );
                } else {
                    res = await apiClient.post("/admin/stores", formData, {
                        headers: { "Content-Type": "multipart/form-data" },
                    });
                }

                if (res.data.status === "success") {
                    this.closeFormModal();
                    globalState.triggerNotificationRefresh();
                    await this.fetchStores();
                }
            } catch (err) {
                if (err.response?.data?.errors) {
                    const errors = Object.values(
                        err.response.data.errors,
                    ).flat();
                    this.formError = errors[0];
                } else {
                    this.formError =
                        "حدث خطأ أثناء الحفظ. يرجى المحاولة مجدداً.";
                }
                console.error(err);
            } finally {
                this.isSaving = false;
            }
        },

        // ════════════════════════════
        // إدارة الحذف
        // ════════════════════════════
        openDeleteModal(store) {
            this.storeToDelete = store;
            this.deleteError = "";
            this.showDeleteModal = true;
        },

        async confirmDelete() {
            if (!this.storeToDelete) return;
            this.isDeleting = true;
            this.deleteError = "";
            try {
                const res = await apiClient.delete(
                    `/admin/stores/${this.storeToDelete.id}`,
                );
                if (res.data.status === "success") {
                    this.showDeleteModal = false;
                    this.stores = this.stores.filter(
                        (s) => s.id !== this.storeToDelete.id,
                    );
                    this.storeToDelete = null;
                    globalState.triggerNotificationRefresh();
                }
            } catch (err) {
                this.deleteError = "تعذر حذف المتجر. يرجى المحاولة مجدداً.";
                console.error(err);
            } finally {
                this.isDeleting = false;
            }
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
   ترويسة صفحة المتاجر
══════════════════════════════════ */
.stores-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.stores-page-title {
    font-size: 22px;
    font-weight: 900;
    margin: 0 0 4px 0;
    color: var(--wc-text-dark);
    display: flex;
    align-items: center;
    gap: 10px;
}

.stores-page-subtitle {
    font-size: 13px;
    color: var(--wc-text-muted);
    margin: 0;
}

/* ══════════════════════════════════
   شريط البحث والفلترة (space-between)
══════════════════════════════════ */
.stores-filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    background: var(--wc-white);
    padding: 12px 18px;
    border-radius: 14px;
    border: 1px solid var(--wc-border);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    margin-bottom: 20px;
    min-height: 60px;
}

.stores-search-wrap {
    position: relative;
    width: 320px;
    flex-shrink: 1;
    min-width: 180px;
}

.stores-search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--wc-text-muted);
    pointer-events: none;
}

.stores-search-input {
    width: 100%;
    padding: 9px 38px 9px 14px;
    border: 1.5px solid var(--wc-border);
    border-radius: 10px;
    background: #f8fafc;
    font-size: 13.5px;
    font-family: inherit;
    color: var(--wc-text-dark);
    outline: none;
    transition: border-color 0.15s, background 0.15s;
    box-sizing: border-box;
    direction: rtl;
}

.stores-search-input:focus {
    border-color: var(--wc-green-bright);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.10);
}

.stores-filter-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.stores-toolbar-divider {
    width: 1px;
    height: 28px;
    background: #e2e8f0;
    margin: 0 2px;
    flex-shrink: 0;
}

.stores-filter-select {
    padding: 8px 11px;
    border: 1.5px solid var(--wc-border);
    border-radius: 10px;
    background: #f8fafc;
    font-size: 13px;
    font-family: inherit;
    color: var(--wc-text-dark);
    outline: none;
    cursor: pointer;
    min-width: 130px;
    transition: border-color 0.15s, background 0.15s;
    direction: rtl;
    white-space: nowrap;
}

.stores-filter-select:focus,
.stores-filter-select:hover {
    border-color: var(--wc-green-bright);
    background: #ffffff;
}

.stores-count-badge {
    background: var(--wc-green-light);
    color: var(--wc-green);
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 700;
    white-space: nowrap;
    border: 1px solid #bbf7d0;
    flex-shrink: 0;
}

.stores-count-badge span {
    font-size: 14px;
    font-weight: 900;
}

.view-mode-toggle {
    display: flex;
    align-items: center;
    background: var(--wc-gray-bg, #f1f5f9);
    padding: 3px;
    border-radius: 8px;
    border: 1px solid var(--wc-border);
    gap: 2px;
}

.view-btn {
    border: none;
    background: transparent;
    padding: 6px 10px;
    border-radius: 6px;
    color: var(--wc-text-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.view-btn:hover {
    color: var(--wc-green-bright);
}

.view-btn--active {
    background: #ffffff;
    color: var(--wc-green-bright);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* ══════════════════════════════════
   شبكة بطاقات المتاجر
══════════════════════════════════ */
.stores-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.store-card {
    background: var(--wc-white);
    border: 1px solid var(--wc-border);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--wc-shadow);
    display: flex;
    flex-direction: column;
    position: relative;
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.store-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(15, 61, 31, 0.14);
}

/* صورة/غلاف المتجر */
.store-card__image-wrap {
    position: relative;
    height: 150px;
    background: var(--wc-green-light);
    overflow: visible;
}

.store-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.store-card:hover .store-card__image {
    transform: scale(1.04);
}

.store-card__image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--wc-green-bright);
    opacity: 0.5;
    background: linear-gradient(135deg, #e8f5ea, #d4edda);
}

.store-card__region-tag {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(15, 23, 42, 0.75);
    color: #fff;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    backdrop-filter: blur(6px);
    z-index: 2;
}

.store-card__status-tag {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(255, 255, 255, 0.9);
    color: #15803d;
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 5px;
    backdrop-filter: blur(4px);
    z-index: 2;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 6px #22c55e;
}

.store-card__logo-avatar {
    position: absolute;
    bottom: -18px;
    right: 16px;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    border: 3px solid #ffffff;
    background: #ffffff;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3;
}

.store-card__logo-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* محتوى البطاقة */
.store-card__body {
    padding: 24px 16px 16px 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* ══════════════════════════════════
   عرض القائمة (List View Table)
══════════════════════════════════ */
.stores-list-table-wrap {
    background: #ffffff;
    border: 1px solid var(--wc-border);
    border-radius: 14px;
    overflow-x: auto;
    box-shadow: var(--wc-shadow);
}

.stores-table {
    width: 100%;
    border-collapse: collapse;
    text-align: right;
    font-size: 14px;
}

.stores-table th {
    background: #f8fafc;
    color: #475569;
    padding: 12px 16px;
    font-weight: 700;
    border-bottom: 1px solid var(--wc-border);
    white-space: nowrap;
}

.stores-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--wc-border);
    color: var(--wc-text-dark);
    vertical-align: middle;
}

.stores-table tr:hover {
    background: #f8fafc;
}

.table-store-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.table-store-img {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid var(--wc-border);
}

.table-store-img-placeholder {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: var(--wc-green-light);
    color: var(--wc-green-bright);
    display: flex;
    align-items: center;
    justify-content: center;
}

.table-store-name {
    display: block;
    font-size: 14px;
    color: var(--wc-text-dark);
}

.table-store-date {
    display: block;
    font-size: 11px;
    color: var(--wc-text-muted);
}

.table-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 8px;
    border-radius: 20px;
    background: #e8f5ea;
    color: #15803d;
    font-size: 12px;
    font-weight: 600;
}

.table-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.store-card__name {
    font-size: 16px;
    font-weight: 800;
    margin: 0;
    color: var(--wc-text-dark);
    line-height: 1.3;
}

.store-card__info {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.store-card__info-row {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    color: var(--wc-text-dark);
}

.store-card__info-row--muted {
    color: var(--wc-text-muted);
}

/* روابط التواصل */
.store-card__socials {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 2px;
}

.social-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    transition:
        opacity 0.15s,
        transform 0.15s;
}

.social-badge:hover {
    opacity: 0.85;
    transform: scale(1.08);
}

.social-badge--fb {
    background: #1877f2;
    color: #fff;
}
.social-badge--ig {
    background: linear-gradient(
        135deg,
        #f09433,
        #e6683c,
        #dc2743,
        #cc2366,
        #bc1888
    );
    color: #fff;
}
.social-badge--tg {
    background: #229ed9;
    color: #fff;
}

/* أزرار الإجراءات */
.store-card__actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    padding: 12px 16px;
    border-top: 1px solid var(--wc-border);
    background: var(--wc-gray-bg);
}

/* ══════════════════════════════════
   منطقة رفع الصورة
══════════════════════════════════ */
.image-upload-area {
    border: 2px dashed var(--wc-border);
    border-radius: 12px;
    padding: 20px;
    cursor: pointer;
    transition:
        border-color 0.2s,
        background 0.2s;
    background: var(--wc-gray-bg);
    min-height: 130px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.image-upload-area:hover {
    border-color: var(--wc-green-bright);
    background: var(--wc-green-light);
}

.image-upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    text-align: center;
    color: var(--wc-text-muted);
}

.image-upload-placeholder p {
    margin: 0;
    font-size: 14px;
}

.image-upload-placeholder strong {
    color: var(--wc-green-bright);
}

.image-upload-placeholder span {
    font-size: 12px;
    color: var(--wc-text-muted);
}

.image-preview-wrap {
    position: relative;
    width: 100%;
    height: 160px;
}

.image-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.image-preview-remove {
    position: absolute;
    top: 6px;
    left: 6px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s;
}

.image-preview-remove:hover {
    background: var(--wc-danger);
}

/* Modal كبير */
.modal--lg {
    max-width: 720px;
}

/* أقسام النموذج والتحسينات البصرية */
.form-section {
    background: #f8fafc;
    border: 1px solid var(--wc-border);
    border-radius: 12px;
    padding: 16px;
}

.form-section-title {
    margin: 0 0 12px 0;
    font-size: 14px;
    font-weight: 700;
    color: var(--wc-text-dark);
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--wc-border);
}

.form-group--full {
    grid-column: 1 / -1;
}

.working-hours-wrap {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.quick-presets {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.preset-btn {
    background: #ffffff;
    border: 1px solid var(--wc-border);
    color: var(--wc-text-dark);
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
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
    transition: border-color 0.2s;
}

.input-prefix-wrap:focus-within {
    border-color: var(--wc-green-bright);
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

@media (max-width: 600px) {
    .image-uploads-grid {
        grid-template-columns: 1fr;
    }
}

/* تنبيه "اختياري" الهادئ بجانب label الحقول الفرعية */
.form-hint {
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
    background: #f1f5f9;
    padding: 2px 7px;
    border-radius: 20px;
    margin-right: 6px;
    vertical-align: middle;
}
</style>

<template>
    <!-- صفحة إدارة العملاء (Customer Management Dashboard) -->
    <div class="dashboard-page">
        <AdminSidebar />

        <div class="dashboard-main">
            <AdminHeader @logout="handleLogout" />

            <div class="dashboard-content">
                <!-- ترويسة الصفحة -->
                <div class="customers-header">
                    <div class="customers-header__text">
                        <h1 class="customers-title">
                            <UsersIcon :size="24" class="title-icon" />
                            إدارة العملاء
                        </h1>
                        <p class="customers-subtitle">
                            عرض وإدارة حسابات العملاء والمستخدمين المسجلين في منصة وفر كاش
                        </p>
                    </div>

                    <div class="header-actions">
                        <button
                            class="btn-refresh"
                            @click="fetchCustomers"
                            :disabled="isLoading"
                            title="تحديث البيانات"
                        >
                            <RefreshCwIcon :size="16" :class="{ 'spin-icon': isLoading }" />
                            <span>تحديث</span>
                        </button>

                        <button class="btn-add" @click="openCreateModal" id="btn-add-customer">
                            <PlusIcon :size="16" />
                            <span>إضافة عميل جديد</span>
                        </button>
                    </div>
                </div>

                <!-- شريط الإحصائيات السريعة -->
                <section class="stats-row">
                    <div class="stat-box">
                        <div class="stat-box__icon total">
                            <UsersIcon :size="20" />
                        </div>
                        <div class="stat-box__data">
                            <span class="stat-box__value">{{ stats.total }}</span>
                            <span class="stat-box__label">إجمالي العملاء</span>
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-box__icon month">
                            <UserPlusIcon :size="20" />
                        </div>
                        <div class="stat-box__data">
                            <span class="stat-box__value">{{ stats.this_month }}</span>
                            <span class="stat-box__label">المسجلين هذا الشهر</span>
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-box__icon phone">
                            <PhoneIcon :size="20" />
                        </div>
                        <div class="stat-box__data">
                            <span class="stat-box__value">{{ stats.with_phone }}</span>
                            <span class="stat-box__label">أرقام هواتف مسجلة</span>
                        </div>
                    </div>
                </section>

                <!-- شريط البحث والفلترة -->
                <div class="filters-bar">
                    <div class="search-input-wrap">
                        <SearchIcon :size="16" class="search-icon" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="ابحث بالاسم، البريد، اسم المستخدم، الهاتف، أو العنوان..."
                            class="search-input"
                        />
                        <button v-if="searchQuery" @click="searchQuery = ''" class="clear-search-btn">✕</button>
                    </div>

                    <div class="filter-controls">
                        <!-- فلتر المنطقة -->
                        <select v-model="selectedRegionId" class="filter-select">
                            <option value="">جميع المناطق والمحافظات</option>
                            <option v-for="region in regions" :key="region.id" :value="region.id">
                                {{ getRegionName(region) }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- إشعار النجاح المؤقت -->
                <transition name="fade">
                    <div v-if="toastMessage" class="toast-alert" :class="toastType">
                        <span>{{ toastMessage }}</span>
                        <button @click="toastMessage = ''" class="toast-close">✕</button>
                    </div>
                </transition>

                <!-- جدول العملاء -->
                <section class="panel">
                    <div class="panel__header">
                        <h2>
                            قائمة العملاء
                            <span class="count-badge">{{ filteredCustomers.length }} عميل</span>
                        </h2>
                    </div>

                    <!-- حالة التحميل -->
                    <div v-if="isLoading" class="loading-container">
                        <div class="spinner"></div>
                        <p>جاري تحميل بيانات العملاء...</p>
                    </div>

                    <!-- جدول البيانات -->
                    <div class="table-container" v-else-if="filteredCustomers.length > 0">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العميل</th>
                                    <th>معلومات الاتصال</th>
                                    <th>المنطقة / العنوان</th>
                                    <th>تاريخ التسجيل</th>
                                    <th style="text-align: center;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(customer, index) in filteredCustomers" :key="customer.id">
                                    <td class="col-index">{{ index + 1 }}</td>
                                    <td>
                                        <div class="user-cell">
                                            <img
                                                :src="customer.avatar || customer.profile_picture_url || getAvatarFallback(customer.name)"
                                                :alt="customer.name"
                                                class="user-avatar"
                                                @error="onAvatarError($event, customer.name)"
                                            />
                                            <div class="user-info">
                                                <span class="user-name">{{ customer.name }}</span>
                                                <span class="user-username" v-if="customer.username">@{{ customer.username }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            <div class="contact-item">
                                                <MailIcon :size="13" />
                                                <span>{{ customer.email }}</span>
                                            </div>
                                            <div class="contact-item" v-if="customer.phone">
                                                <PhoneIcon :size="13" />
                                                <span dir="ltr">{{ customer.phone }}</span>
                                            </div>
                                            <div class="contact-item muted" v-else>
                                                <span>لا يوجد هاتف</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="location-info">
                                            <span class="region-badge" v-if="getCustomerRegion(customer)">
                                                <MapPinIcon :size="12" />
                                                {{ getCustomerRegion(customer) }}
                                            </span>
                                            <span class="address-text" v-if="customer.address">{{ customer.address }}</span>
                                            <span class="muted" v-if="!getCustomerRegion(customer) && !customer.address">—</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            <span class="date-main">{{ customer.created_at || '—' }}</span>
                                            <span class="date-human" v-if="customer.created_at_human">{{ customer.created_at_human }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="actions-cell">
                                            <button
                                                class="action-btn view-btn"
                                                @click="openDetailsModal(customer)"
                                                title="عرض تفاصيل العميل"
                                            >
                                                <EyeIcon :size="15" />
                                            </button>
                                            <button
                                                class="action-btn edit-btn"
                                                @click="openEditModal(customer)"
                                                title="تعديل بيانات العميل"
                                            >
                                                <EditIcon :size="15" />
                                            </button>
                                            <button
                                                class="action-btn delete-btn"
                                                @click="confirmDelete(customer)"
                                                title="حذف حساب العميل"
                                            >
                                                <Trash2Icon :size="15" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- حالة عدم وجود بيانات -->
                    <div v-else class="empty-state">
                        <div class="empty-icon">
                            <UsersIcon :size="48" />
                        </div>
                        <h3>لا يوجد عملاء مطابِقين</h3>
                        <p v-if="searchQuery || selectedRegionId">
                            لم نجد أي عميل يطابق معايير البحث والفلترة المحددة.
                        </p>
                        <p v-else>
                            لم يتم تسجيل أي عميل حتى الآن. اضغط على "إضافة عميل جديد" للبدء.
                        </p>
                        <button v-if="searchQuery || selectedRegionId" @click="resetFilters" class="btn-clear-filters">
                            إلغاء الفلاتر
                        </button>
                    </div>
                </section>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════
             Modal: إضافة / تعديل عميل
        ══════════════════════════════════════════════ -->
        <div v-if="showFormModal" class="modal-overlay" @click.self="closeFormModal">
            <div class="modal-card">
                <div class="modal-header">
                    <h3>
                        <UserPlusIcon v-if="modalMode === 'create'" :size="18" />
                        <EditIcon v-else :size="18" />
                        <span>{{ modalMode === 'create' ? 'إضافة عميل جديد' : 'تعديل بيانات العميل' }}</span>
                    </h3>
                    <button class="modal-close" @click="closeFormModal">✕</button>
                </div>

                <form @submit.prevent="saveCustomer" class="modal-body">
                    <div v-if="formError" class="modal-error">
                        <AlertTriangleIcon :size="16" />
                        <span>{{ formError }}</span>
                    </div>

                    <div class="form-grid">
                        <!-- الاسم الكامل -->
                        <div class="form-group full-width">
                            <label>الاسم الكامل <span class="req">*</span></label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="input-field"
                                placeholder="مثال: أحمد محمد أبو سالم"
                                required
                            />
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="form-group">
                            <label>البريد الإلكتروني <span class="req">*</span></label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="input-field"
                                placeholder="user@example.com"
                                required
                            />
                        </div>

                        <!-- اسم المستخدم -->
                        <div class="form-group">
                            <label>اسم المستخدم (اختياري)</label>
                            <input
                                v-model="form.username"
                                type="text"
                                class="input-field"
                                placeholder="ahmed_99"
                            />
                        </div>

                        <!-- رقم الهاتف -->
                        <div class="form-group">
                            <label>رقم الهاتف</label>
                            <input
                                v-model="form.phone"
                                type="text"
                                class="input-field"
                                placeholder="059xxxxxxx"
                                dir="ltr"
                            />
                        </div>

                        <!-- المنطقة / المحافظة -->
                        <div class="form-group">
                            <label>المنطقة / المحافظة</label>
                            <select v-model="form.region_id" class="input-field">
                                <option value="">اختر المنطقة...</option>
                                <option v-for="region in regions" :key="region.id" :value="region.id">
                                    {{ getRegionName(region) }}
                                </option>
                            </select>
                        </div>

                        <!-- العنوان التفصيلي -->
                        <div class="form-group full-width">
                            <label>العنوان التفصيلي</label>
                            <input
                                v-model="form.address"
                                type="text"
                                class="input-field"
                                placeholder="الشارع، بجوار معلم معروف..."
                            />
                        </div>

                        <!-- كلمة المرور -->
                        <div class="form-group full-width">
                            <label>
                                كلمة المرور
                                <span class="req" v-if="modalMode === 'create'">*</span>
                                <span class="hint-text" v-else>(اتركها فارغة إذا كنت لا تريد تغييرها)</span>
                            </label>
                            <input
                                v-model="form.password"
                                type="password"
                                class="input-field"
                                :placeholder="modalMode === 'create' ? '6 أحرف على الأقل' : '••••••••'"
                                :required="modalMode === 'create'"
                                minlength="6"
                            />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" @click="closeFormModal" :disabled="isSubmitting">إلغاء</button>
                        <button type="submit" class="btn-save" :disabled="isSubmitting">
                            <span v-if="isSubmitting">جاري الحفظ...</span>
                            <span v-else>{{ modalMode === 'create' ? 'إضافة العميل' : 'حفظ التعديلات' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════
             Modal: تفاصيل العميل (View Details)
        ══════════════════════════════════════════════ -->
        <div v-if="showDetailsModal && selectedCustomer" class="modal-overlay" @click.self="showDetailsModal = false">
            <div class="modal-card details-card">
                <div class="modal-header">
                    <h3>
                        <UsersIcon :size="18" />
                        <span>الملف التعريفي للعميل</span>
                    </h3>
                    <button class="modal-close" @click="showDetailsModal = false">✕</button>
                </div>

                <div class="modal-body details-body">
                    <div class="details-top">
                        <img
                            :src="selectedCustomer.avatar || selectedCustomer.profile_picture_url || getAvatarFallback(selectedCustomer.name)"
                            :alt="selectedCustomer.name"
                            class="details-avatar"
                            @error="onAvatarError($event, selectedCustomer.name)"
                        />
                        <div class="details-heading">
                            <h2>{{ selectedCustomer.name }}</h2>
                            <span class="details-role-badge">عميل منصة</span>
                            <span class="details-username" v-if="selectedCustomer.username">@{{ selectedCustomer.username }}</span>
                        </div>
                    </div>

                    <div class="details-info-grid">
                        <div class="detail-item">
                            <span class="detail-label"><MailIcon :size="14" /> البريد الإلكتروني:</span>
                            <span class="detail-val">{{ selectedCustomer.email }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label"><PhoneIcon :size="14" /> رقم الهاتف:</span>
                            <span class="detail-val" dir="ltr">{{ selectedCustomer.phone || 'غير مسجل' }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label"><MapPinIcon :size="14" /> المنطقة:</span>
                            <span class="detail-val">{{ getCustomerRegion(selectedCustomer) || 'غير محددة' }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label"><MapPinIcon :size="14" /> العنوان:</span>
                            <span class="detail-val">{{ selectedCustomer.address || 'غير مسجل' }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label"><CalendarIcon :size="14" /> تاريخ الانضمام:</span>
                            <span class="detail-val">{{ selectedCustomer.created_at || '—' }} <span v-if="selectedCustomer.created_at_human">({{ selectedCustomer.created_at_human }})</span></span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label"><ShieldIcon :size="14" /> معرف الحساب (ID):</span>
                            <span class="detail-val">#{{ selectedCustomer.id }}</span>
                        </div>
                    </div>

                    <div class="modal-footer" style="padding: 0; margin-top: 24px;">
                        <button type="button" class="btn-cancel" @click="showDetailsModal = false">إغلاق</button>
                        <button
                            type="button"
                            class="btn-save"
                            @click="editFromDetails(selectedCustomer)"
                        >
                            تعديل البيانات
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════
             Modal: تأكيد الحذف
        ══════════════════════════════════════════════ -->
        <div v-if="showDeleteModal && customerToDelete" class="modal-overlay" @click.self="showDeleteModal = false">
            <div class="modal-card delete-card">
                <div class="delete-icon-wrap">
                    <Trash2Icon :size="32" />
                </div>
                <h3>تأكيد حذف حساب العميل</h3>
                <p>
                    هل أنت متأكد من رغبتك في حذف حساب العميل <strong>"{{ customerToDelete.name }}"</strong>؟
                    لا يمكن التراجع عن هذا الإجراء وسيتم حذف جميع البيانات المرتبطة بالحساب.
                </p>

                <div class="delete-actions">
                    <button class="btn-cancel" @click="showDeleteModal = false" :disabled="isDeleting">إلغاء</button>
                    <button class="btn-danger-confirm" @click="deleteCustomerConfirm" :disabled="isDeleting">
                        <span v-if="isDeleting">جاري الحذف...</span>
                        <span v-else>نعم، احذف العميل</span>
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
import {
    Users as UsersIcon,
    UserPlus as UserPlusIcon,
    Search as SearchIcon,
    Mail as MailIcon,
    Phone as PhoneIcon,
    MapPin as MapPinIcon,
    Calendar as CalendarIcon,
    Shield as ShieldIcon,
    Eye as EyeIcon,
    Edit as EditIcon,
    Trash2 as Trash2Icon,
    AlertTriangle as AlertTriangleIcon,
    Plus as PlusIcon,
    RefreshCw as RefreshCwIcon,
} from "@lucide/vue";

export default {
    name: "AdminCustomers",
    components: {
        AdminSidebar,
        AdminHeader,
        UsersIcon,
        UserPlusIcon,
        SearchIcon,
        MailIcon,
        PhoneIcon,
        MapPinIcon,
        CalendarIcon,
        ShieldIcon,
        EyeIcon,
        EditIcon,
        Trash2Icon,
        AlertTriangleIcon,
        PlusIcon,
        RefreshCwIcon,
    },
    data() {
        return {
            customers: [],
            regions: [],
            stats: {
                total: 0,
                this_month: 0,
                with_phone: 0,
            },
            isLoading: false,
            isSubmitting: false,
            isDeleting: false,

            searchQuery: "",
            selectedRegionId: "",

            // Forms & Modals
            showFormModal: false,
            modalMode: "create", // 'create' | 'edit'
            currentCustomerId: null,
            form: {
                name: "",
                email: "",
                username: "",
                phone: "",
                address: "",
                region_id: "",
                password: "",
            },
            formError: "",

            // Details Modal
            showDetailsModal: false,
            selectedCustomer: null,

            // Delete Modal
            showDeleteModal: false,
            customerToDelete: null,

            // Toast
            toastMessage: "",
            toastType: "toast-success",
        };
    },
    computed: {
        filteredCustomers() {
            let list = this.customers;

            if (this.selectedRegionId) {
                const regId = Number(this.selectedRegionId);
                list = list.filter((c) => {
                    const cRegId = c.region_id || (c.region && c.region.id);
                    return Number(cRegId) === regId;
                });
            }

            if (this.searchQuery.trim()) {
                const q = this.searchQuery.trim().toLowerCase();
                list = list.filter(
                    (c) =>
                        (c.name && c.name.toLowerCase().includes(q)) ||
                        (c.email && c.email.toLowerCase().includes(q)) ||
                        (c.username && c.username.toLowerCase().includes(q)) ||
                        (c.phone && c.phone.includes(q)) ||
                        (c.address && c.address.toLowerCase().includes(q))
                );
            }

            return list;
        },
    },
    created() {
        this.fetchCustomers();
    },
    methods: {
        async fetchCustomers() {
            this.isLoading = true;
            try {
                const response = await apiClient.get("/admin/customers");
                const data = response.data;

                if (data.status === "success" || Array.isArray(data.customers)) {
                    this.customers = data.customers || [];
                    this.regions = data.regions || [];
                    if (data.stats) {
                        this.stats = data.stats;
                    } else {
                        this.calculateStats();
                    }
                } else if (Array.isArray(data)) {
                    this.customers = data;
                    this.calculateStats();
                }
            } catch (err) {
                console.error("Failed to load customers:", err);
                this.showToast("فشل في تحميل بيانات العملاء من الخادم", "toast-error");
            } finally {
                this.isLoading = false;
            }
        },

        calculateStats() {
            this.stats.total = this.customers.length;
            this.stats.this_month = this.customers.length;
            this.stats.with_phone = this.customers.filter((c) => !!c.phone).length;
        },

        getRegionName(region) {
            if (!region) return "";
            if (region.display) return region.display;
            const city = region.city_or_governorate || "";
            const area = region.area_name ? ` - ${region.area_name}` : "";
            return `${city}${area}`;
        },

        getCustomerRegion(customer) {
            if (!customer) return "";
            if (customer.region) {
                if (typeof customer.region === "object") {
                    return this.getRegionName(customer.region);
                }
                return customer.region;
            }
            if (customer.region_id && this.regions.length > 0) {
                const found = this.regions.find((r) => r.id === customer.region_id);
                if (found) return this.getRegionName(found);
            }
            return "";
        },

        getAvatarFallback(name) {
            const encoded = encodeURIComponent(name || "User");
            return `https://api.dicebear.com/7.x/initials/svg?seed=${encoded}&backgroundColor=17692e`;
        },

        onAvatarError(event, name) {
            event.target.src = this.getAvatarFallback(name);
        },

        resetFilters() {
            this.searchQuery = "";
            this.selectedRegionId = "";
        },

        openCreateModal() {
            this.modalMode = "create";
            this.currentCustomerId = null;
            this.form = {
                name: "",
                email: "",
                username: "",
                phone: "",
                address: "",
                region_id: "",
                password: "",
            };
            this.formError = "";
            this.showFormModal = true;
        },

        openEditModal(customer) {
            this.modalMode = "edit";
            this.currentCustomerId = customer.id;
            this.form = {
                name: customer.name || "",
                email: customer.email || "",
                username: customer.username || "",
                phone: customer.phone || "",
                address: customer.address || "",
                region_id: customer.region_id || (customer.region && customer.region.id) || "",
                password: "",
            };
            this.formError = "";
            this.showFormModal = true;
        },

        openDetailsModal(customer) {
            this.selectedCustomer = customer;
            this.showDetailsModal = true;
        },

        editFromDetails(customer) {
            this.showDetailsModal = false;
            this.openEditModal(customer);
        },

        closeFormModal() {
            this.showFormModal = false;
            this.formError = "";
        },

        async saveCustomer() {
            if (!this.form.name.trim() || !this.form.email.trim()) {
                this.formError = "يرجى ملء جميع الحقول الإلزامية (الاسم والبريد الإلكتروني).";
                return;
            }

            if (this.modalMode === "create" && (!this.form.password || this.form.password.length < 6)) {
                this.formError = "كلمة المرور مطلوبة ويجب أن لا تقل عن 6 أحرف.";
                return;
            }

            this.isSubmitting = true;
            this.formError = "";

            const payload = {
                name: this.form.name.trim(),
                email: this.form.email.trim(),
                username: this.form.username.trim() || null,
                phone: this.form.phone.trim() || null,
                region_id: this.form.region_id ? Number(this.form.region_id) : null,
                address: this.form.address.trim() || null,
            };

            if (this.form.password) {
                payload.password = this.form.password;
            }

            try {
                if (this.modalMode === "create") {
                    const res = await apiClient.post("/admin/customers", payload);
                    const newCustomer = res.data.customer || res.data;
                    this.customers.unshift(newCustomer);
                    this.calculateStats();
                    this.showToast(res.data.message || "تمت إضافة العميل بنجاح", "toast-success");
                } else {
                    const res = await apiClient.put(`/admin/customers/${this.currentCustomerId}`, payload);
                    const updatedCustomer = res.data.customer || res.data;
                    const idx = this.customers.findIndex((c) => c.id === this.currentCustomerId);
                    if (idx !== -1) {
                        this.customers[idx] = updatedCustomer;
                    }
                    if (this.selectedCustomer && this.selectedCustomer.id === this.currentCustomerId) {
                        this.selectedCustomer = updatedCustomer;
                    }
                    this.calculateStats();
                    this.showToast(res.data.message || "تم تحديث بيانات العميل بنجاح", "toast-success");
                }

                this.closeFormModal();
            } catch (err) {
                console.error("Save customer error:", err);
                if (err.response && err.response.data) {
                    if (err.response.data.errors) {
                        this.formError = Object.values(err.response.data.errors).flat().join(" - ");
                    } else if (err.response.data.message) {
                        this.formError = err.response.data.message;
                    } else {
                        this.formError = "حدث خطأ أثناء حفظ البيانات.";
                    }
                } else {
                    this.formError = "تعذر الاتصال بالخادم، يرجى المحاولة مرة أخرى.";
                }
            } finally {
                this.isSubmitting = false;
            }
        },

        confirmDelete(customer) {
            this.customerToDelete = customer;
            this.showDeleteModal = true;
        },

        async deleteCustomerConfirm() {
            if (!this.customerToDelete) return;

            this.isDeleting = true;
            try {
                const res = await apiClient.delete(`/admin/customers/${this.customerToDelete.id}`);
                this.customers = this.customers.filter((c) => c.id !== this.customerToDelete.id);
                this.calculateStats();
                this.showToast(res.data.message || "تم حذف العميل بنجاح", "toast-success");
                this.showDeleteModal = false;
                this.customerToDelete = null;
            } catch (err) {
                console.error("Delete customer error:", err);
                this.showToast(err.response?.data?.message || "فشل في حذف العميل", "toast-error");
            } finally {
                this.isDeleting = false;
            }
        },

        showToast(msg, type = "toast-success") {
            this.toastMessage = msg;
            this.toastType = type;
            setTimeout(() => {
                this.toastMessage = "";
            }, 4000);
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
.customers-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.customers-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--wc-green-dark);
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.title-icon {
    color: var(--wc-green-bright);
}

.customers-subtitle {
    font-size: 14px;
    color: var(--wc-text-muted);
    margin: 0;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-refresh {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    color: var(--wc-text-dark);
    border: 1px solid var(--wc-border);
    padding: 10px 16px;
    border-radius: var(--wc-radius);
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-refresh:hover:not(:disabled) {
    background: var(--wc-gray-bg);
    border-color: #cbd5e1;
}

.btn-refresh:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--wc-green);
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    padding: 10px 20px;
    border-radius: var(--wc-radius);
    border: none;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s;
    box-shadow: 0 2px 8px rgba(23, 105, 46, 0.2);
}

.btn-add:hover {
    background: var(--wc-green-dark);
    transform: translateY(-1px);
}

/* إحصائيات سريعة */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-box {
    background: var(--wc-white);
    border: 1px solid var(--wc-border);
    border-radius: var(--wc-radius);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: var(--wc-shadow);
}

.stat-box__icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-box__icon.total {
    background: #e8f5ea;
    color: var(--wc-green);
}

.stat-box__icon.month {
    background: #e0f2fe;
    color: #0284c7;
}

.stat-box__icon.phone {
    background: #fef3c7;
    color: #d97706;
}

.stat-box__data {
    display: flex;
    flex-direction: column;
}

.stat-box__value {
    font-size: 22px;
    font-weight: 800;
    color: var(--wc-text-dark);
    line-height: 1.2;
}

.stat-box__label {
    font-size: 12px;
    color: var(--wc-text-muted);
}

/* شريط الفلاتر */
.filters-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    background: var(--wc-white);
    padding: 12px 18px;
    border-radius: var(--wc-radius);
    border: 1px solid var(--wc-border);
    margin-bottom: 24px;
}

.search-input-wrap {
    position: relative;
    flex: 1;
    min-width: 260px;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    right: 12px;
    color: var(--wc-text-muted);
    pointer-events: none;
}

.search-input {
    width: 100%;
    padding: 9px 36px 9px 32px;
    border-radius: 8px;
    border: 1px solid var(--wc-border);
    font-size: 13px;
    background: var(--wc-gray-bg);
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: var(--wc-green-bright);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(34, 168, 62, 0.15);
}

.clear-search-btn {
    position: absolute;
    left: 10px;
    background: none;
    border: none;
    color: #999;
    font-size: 14px;
    padding: 0;
    cursor: pointer;
}

.filter-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-select {
    padding: 9px 14px;
    border-radius: 8px;
    border: 1px solid var(--wc-border);
    font-size: 13px;
    background: var(--wc-gray-bg);
    color: var(--wc-text-dark);
    cursor: pointer;
}

/* التنبيهات */
.toast-alert {
    padding: 12px 18px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
}

.toast-success {
    background: #e8f5ea;
    color: var(--wc-green-dark);
    border: 1px solid #c2e7c9;
}

.toast-error {
    background: #fef2f2;
    color: var(--wc-danger);
    border: 1px solid #fecaca;
}

.toast-close {
    background: none;
    border: none;
    color: inherit;
    font-weight: bold;
    cursor: pointer;
}

/* حالة التحميل */
.loading-container {
    padding: 48px;
    text-align: center;
    color: var(--wc-text-muted);
}

.spinner {
    width: 36px;
    height: 36px;
    border: 3px solid rgba(23, 105, 46, 0.1);
    border-top-color: var(--wc-green-bright);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 12px auto;
}

/* لوحة البيانات والجدول */
.count-badge {
    font-size: 12px;
    font-weight: 600;
    background: var(--wc-green-light);
    color: var(--wc-green);
    padding: 4px 10px;
    border-radius: 20px;
    margin-right: 8px;
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    text-align: right;
}

.data-table th {
    background: #fafbfb;
    color: var(--wc-text-muted);
    font-size: 12px;
    font-weight: 700;
    padding: 12px 16px;
    border-bottom: 1px solid var(--wc-border);
}

.data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--wc-border);
    font-size: 13px;
    vertical-align: middle;
}

.data-table tr:hover td {
    background: #fafcfb;
}

.col-index {
    color: var(--wc-text-muted);
    font-weight: 600;
    font-size: 12px;
    width: 40px;
}

/* خلايا الجدول */
.user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    background: var(--wc-green-light);
    border: 1px solid var(--wc-border);
    flex-shrink: 0;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 700;
    color: var(--wc-text-dark);
}

.user-username {
    font-size: 11px;
    color: var(--wc-green);
    font-weight: 600;
}

.contact-info,
.location-info,
.date-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--wc-text-dark);
    font-size: 12px;
}

.contact-item.muted,
.muted {
    color: var(--wc-text-muted);
    font-size: 12px;
}

.region-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #f1f5f9;
    color: #334155;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 4px;
    width: fit-content;
}

.address-text {
    font-size: 11px;
    color: var(--wc-text-muted);
}

.date-main {
    font-size: 12px;
    font-weight: 600;
    color: var(--wc-text-dark);
}

.date-human {
    font-size: 11px;
    color: var(--wc-text-muted);
}

.actions-cell {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--wc-border);
    background: #fff;
    cursor: pointer;
    transition: all 0.15s;
}

.view-btn {
    color: #0284c7;
}

.view-btn:hover {
    background: #f0f9ff;
    border-color: #bae6fd;
}

.edit-btn {
    color: var(--wc-green);
}

.edit-btn:hover {
    background: #e8f5ea;
    border-color: #c2e7c9;
}

.delete-btn {
    color: var(--wc-danger);
}

.delete-btn:hover {
    background: #fee2e2;
    border-color: #fca5a5;
}

/* حالة الفراغ */
.empty-state {
    padding: 48px 24px;
    text-align: center;
}

.empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #f1f5f9;
    color: var(--wc-text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px auto;
}

.empty-state h3 {
    font-size: 16px;
    color: var(--wc-text-dark);
    margin: 0 0 6px 0;
}

.empty-state p {
    font-size: 13px;
    color: var(--wc-text-muted);
    margin: 0;
}

.btn-clear-filters {
    margin-top: 14px;
    padding: 8px 18px;
    background: var(--wc-gray-bg);
    border: 1px solid var(--wc-border);
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

/* Modals */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal-card {
    background: var(--wc-white);
    border-radius: var(--wc-radius);
    width: 100%;
    max-width: 580px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid var(--wc-border);
    background: #fafbfb;
}

.modal-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--wc-green-dark);
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-close {
    background: none;
    border: none;
    font-size: 18px;
    color: var(--wc-text-muted);
    cursor: pointer;
}

.modal-body {
    padding: 22px;
    overflow-y: auto;
}

.modal-error {
    background: #fef2f2;
    color: var(--wc-danger);
    border: 1px solid #fee2e2;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-size: 13px;
    font-weight: 600;
    color: var(--wc-text-dark);
}

.req {
    color: var(--wc-danger);
}

.hint-text {
    font-size: 11px;
    color: var(--wc-text-muted);
    font-weight: normal;
}

.input-field {
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid var(--wc-border);
    background: #fafbfb;
    font-size: 13px;
}

.input-field:focus {
    outline: none;
    border-color: var(--wc-green-bright);
    background: #fff;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    margin-top: 24px;
    padding-top: 16px;
    border-top: 1px solid var(--wc-border);
}

.btn-cancel {
    padding: 10px 20px;
    border-radius: 8px;
    border: 1px solid var(--wc-border);
    background: var(--wc-gray-bg);
    color: var(--wc-text-dark);
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
}

.btn-save {
    padding: 10px 24px;
    border-radius: 8px;
    border: none;
    background: var(--wc-green);
    color: #fff;
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
}

.btn-save:hover:not(:disabled) {
    background: var(--wc-green-dark);
}

.btn-save:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Details Modal Specifics */
.details-top {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid var(--wc-border);
}

.details-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--wc-green-bright);
}

.details-heading h2 {
    margin: 0 0 4px 0;
    font-size: 20px;
    color: var(--wc-text-dark);
}

.details-role-badge {
    background: var(--wc-green-light);
    color: var(--wc-green);
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
}

.details-username {
    font-size: 12px;
    color: var(--wc-text-muted);
    margin-right: 8px;
}

.details-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.detail-item {
    background: #fafbfb;
    border: 1px solid var(--wc-border);
    border-radius: 8px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.detail-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--wc-text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
}

.detail-val {
    font-size: 13px;
    font-weight: 600;
    color: var(--wc-text-dark);
}

/* Delete Modal */
.delete-card {
    max-width: 440px;
    padding: 32px 24px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.delete-icon-wrap {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #fee2e2;
    color: var(--wc-danger);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.delete-card h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    color: var(--wc-text-dark);
}

.delete-card p {
    margin: 0 0 20px 0;
    font-size: 13px;
    color: var(--wc-text-muted);
    line-height: 1.6;
}

.delete-actions {
    display: flex;
    gap: 12px;
    width: 100%;
}

.delete-actions button {
    flex: 1;
}

.btn-danger-confirm {
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    background: var(--wc-danger);
    color: #fff;
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
}

.btn-danger-confirm:hover:not(:disabled) {
    background: #dc2626;
}

.btn-danger-confirm:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>

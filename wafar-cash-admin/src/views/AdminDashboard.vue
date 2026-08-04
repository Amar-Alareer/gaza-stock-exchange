<template>
    <div class="dashboard-page">
        <AdminSidebar />

        <div class="dashboard-main">
            <AdminHeader @logout="handleLogout" />

            <div class="dashboard-content">
                <div
                    v-if="isLoading"
                    class="loading-overlay"
                    style="
                        text-align: center;
                        padding: 20px;
                        color: var(--wc-green-bright);
                        font-weight: bold;
                    "
                ></div>

                <template v-else>
                    <section class="stats-grid">
                        <div
                            class="stat-card"
                            v-for="stat in stats"
                            :key="stat.label"
                        >
                            <div
                                class="stat-card__icon"
                                :style="{
                                    background: stat.color,
                                    color: 'var(--wc-green-bright)',
                                }"
                            >
                                <component :is="stat.icon" :size="20" />
                            </div>
                            <div class="stat-card__value">{{ stat.value }}</div>
                            <div class="stat-card__label">{{ stat.label }}</div>
                        </div>
                    </section>

                    <!-- جدول أحدث المنتجات -->
                    <section class="panel">
                        <div class="panel__header">
                            <h2>أحدث المنتجات والأسعار المضافة</h2>
                        </div>

                        <table class="products-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" /></th>
                                    <th>اسم المنتج</th>
                                    <th>الفئة</th>
                                    <th>المتجر</th>
                                    <th>السعر الحالي</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="product in products"
                                    :key="product.id"
                                >
                                    <td><input type="checkbox" /></td>
                                    <td class="products-table__name">
                                        <span
                                            class="products-table__thumb"
                                            style="
                                                display: inline-flex;
                                                align-items: center;
                                                justify-content: center;
                                                background: #e8f5ea;
                                                width: 28px;
                                                height: 28px;
                                                border-radius: 6px;
                                                color: var(--wc-green-bright);
                                                margin-left: 8px;
                                            "
                                        >
                                            <PackageIcon :size="16" />
                                        </span>
                                        <span>{{ product.item_name }}</span>
                                    </td>
                                    <td>{{ product.category }}</td>
                                    <td>{{ product.store_name }}</td>
                                    <td>{{ product.price }} شيكل</td>
                                    <td>{{ product.date }}</td>
                                    <td class="products-table__actions">
                                        <button
                                            class="icon-btn icon-btn--edit"
                                            @click="handleEditProduct(product)"
                                            title="تعديل"
                                        >
                                            <EditIcon :size="14" />
                                        </button>

                                        <button
                                            class="icon-btn icon-btn--delete"
                                            @click="
                                                confirmDeleteProduct(product)
                                            "
                                            title="حذف"
                                        >
                                            <TrashIcon :size="14" />
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="products.length === 0">
                                    <td
                                        colspan="7"
                                        style="
                                            text-align: center;
                                            padding: 20px;
                                            color: #888;
                                        "
                                    >
                                        لا توجد منتجات مضافة في قاعدة البيانات
                                        حتى الآن.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </section>

                    <section class="panel">
                        <div class="panel__header">
                            <h2>المتاجر المسجلة حديثاً</h2>
                        </div>

                        <div class="shops-grid">
                            <div
                                class="shop-card"
                                v-for="shop in shops"
                                :key="shop.id"
                            >
                                <div
                                    class="shop-card__icon"
                                    style="
                                        color: var(--wc-green-bright);
                                        display: inline-flex;
                                        align-items: center;
                                        justify-content: center;
                                        background: #e8f5ea;
                                        width: 40px;
                                        height: 40px;
                                        border-radius: 10px;
                                        font-size: 0;
                                    "
                                >
                                    <StoreIcon :size="20" />
                                </div>
                                <div class="shop-card__info">
                                    <div
                                        class="shop-card__name-row"
                                        style="
                                            display: flex;
                                            align-items: center;
                                            gap: 6px;
                                        "
                                    >
                                        <span class="shop-card__name">{{
                                            shop.name
                                        }}</span>
                                        <span
                                            class="shop-card__check"
                                            style="
                                                display: inline-flex;
                                                align-items: center;
                                                color: var(--wc-green-bright);
                                                margin-left: 0;
                                            "
                                        >
                                            <CheckIcon
                                                :size="14"
                                                stroke-width="3"
                                            />
                                        </span>
                                    </div>
                                    <span class="shop-card__type"
                                        >الهاتف:
                                        {{ shop.phone || "غير مدرج" }}</span
                                    >
                                    <span class="shop-card__date"
                                        >المنطقة: {{ shop.region }}</span
                                    >
                                </div>
                            </div>
                            <div
                                v-if="shops.length === 0"
                                style="
                                    grid-column: 1/-1;
                                    text-align: center;
                                    color: #888;
                                    padding: 10px;
                                "
                            >
                                لا توجد متاجر مسجلة حالياً.
                            </div>
                        </div>
                    </section>
                </template>
            </div>
        </div>

        <!-- ══════════════════════════════════════════
         Modal: تعديل المنتج
    ══════════════════════════════════════════ -->
        <div
            v-if="showEditModal"
            class="modal-overlay"
            @click.self="closeEditModal"
        >
            <div class="modal" role="dialog" aria-modal="true">
                <div class="modal__header">
                    <h3
                        class="modal__title"
                        style="display: flex; align-items: center; gap: 8px"
                    >
                        <EditIcon
                            :size="18"
                            style="color: var(--wc-green-bright)"
                        />
                        <span>تعديل المنتج</span>
                    </h3>
                    <button class="modal__close" @click="closeEditModal">
                        ✕
                    </button>
                </div>

                <div class="modal__body">
                    <!-- حقل الاسم -->
                    <div class="form-group">
                        <label class="form-label"
                            >اسم المنتج <span class="required">*</span></label
                        >
                        <input
                            v-model="productForm.name"
                            type="text"
                            class="form-input"
                        />
                    </div>

                    <!-- حقل السعر -->
                    <div class="form-group">
                        <label class="form-label"
                            >السعر الحالي (شيكل)
                            <span class="required">*</span></label
                        >
                        <input
                            v-model.number="productForm.price"
                            type="number"
                            class="form-input"
                        />
                    </div>

                    <!-- رسالة خطأ إن وجدت -->
                    <div
                        v-if="productError"
                        class="error-alert"
                        style="margin-top: 10px"
                    >
                        <span>{{ productError }}</span>
                    </div>
                </div>

                <div class="modal__footer">
                    <button class="btn-cancel" @click="closeEditModal">
                        إلغاء
                    </button>
                    <button
                        class="btn-save"
                        @click="saveProductUpdate"
                        :disabled="isSavingProduct"
                    >
                        <span v-if="isSavingProduct" class="btn-spinner"></span>
                        {{ isSavingProduct ? "جارٍ الحفظ..." : "تحديث المنتج" }}
                    </button>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════
         Modal: تأكيد حذف المنتج
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
                        <span>تأكيد حذف المنتج</span>
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
                        هل أنت متأكد من حذف المنتج:
                        <strong>{{ productToDelete?.item_name }}</strong
                        >؟
                    </p>
                    <div
                        v-if="productError"
                        class="error-alert"
                        style="margin-top: 10px"
                    >
                        <span>{{ productError }}</span>
                    </div>
                </div>

                <div class="modal__footer">
                    <button class="btn-cancel" @click="showDeleteModal = false">
                        إلغاء
                    </button>
                    <button
                        class="btn-delete"
                        @click="deleteProductConfirm"
                        :disabled="isDeletingProduct"
                    >
                        <span
                            v-if="isDeletingProduct"
                            class="btn-spinner"
                        ></span>
                        {{ isDeletingProduct ? "نعم، احذف" : "حذف" }}
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
import {
    Package as PackageIcon,
    Store as StoreIcon,
    GitCompare as GitCompareIcon,
    Bell as BellIcon,
    Newspaper as NewspaperIcon,
    Edit as EditIcon,
    Trash2 as TrashIcon,
    Check as CheckIcon,
} from "@lucide/vue";

export default {
    name: "AdminDashboard",
    components: {
        AdminSidebar,
        AdminHeader,
        PackageIcon,
        StoreIcon,
        GitCompareIcon,
        BellIcon,
        NewspaperIcon,
        EditIcon,
        TrashIcon,
        CheckIcon,
    },
    data() {
        return {
            isLoading: true,
            // هيكل الكروت الإحصائية بقيم افتراضية مرنة
            stats: [
                {
                    key: "items_count",
                    label: "المنتجات المسجلة",
                    value: "0",
                    icon: "PackageIcon",
                    color: "#e8f5ea",
                },
                {
                    key: "stores_count",
                    label: "المتاجر المفعلة",
                    value: "0",
                    icon: "StoreIcon",
                    color: "#e8f5ea",
                },
                {
                    key: "comparisons_this_month",
                    label: "عدد المقارنات هذا الشهر",
                    value: "0",
                    icon: "GitCompareIcon",
                    color: "#e8f5ea",
                },
                {
                    key: "active_complaints_count",
                    label: "التنبيهات النشطة",
                    value: "0",
                    icon: "BellIcon",
                    color: "#e8f5ea",
                },
                {
                    key: "articles_count",
                    label: "المقالات المنشورة",
                    value: "0",
                    icon: "NewspaperIcon",
                    color: "#e8f5ea",
                },
            ],
            products: [],
            shops: [],

            // حالات نوافذ المنتجات المنبثقة
            showEditModal: false,
            showDeleteModal: false,
            isSavingProduct: false,
            isDeletingProduct: false,
            productError: "",

            // بيانات تعديل المنتج وحذفه
            productToDelete: null,
            productForm: {
                id: null,
                name: "",
                price: null,
            },
        };
    },
    mounted() {
        this.fetchDashboardData();
    },
    methods: {
        handleEditProduct(product) {
            this.productError = "";
            this.productForm = {
                id: product.id,
                name: product.item_name, // استخدام item_name ليتطابق مع الحقل المرتجع من الـ API
                price: product.price,
            };
            this.showEditModal = true;
        },

        closeEditModal() {
            this.showEditModal = false;
            this.productForm = { id: null, name: "", price: null };
        },

        // حفظ التعديلات وإرسالها للسيرفر
        async saveProductUpdate() {
            if (!this.productForm.name || !this.productForm.price) {
                this.productError = "يرجى ملء جميع الحقول المطلوبة";
                return;
            }

            this.isSavingProduct = true;
            this.productError = "";
            try {
                // يتم الإرسال إلى رابط تعديل المنتج الخاص بك في Laravel
                // استبدل السطر القديم بهذا السطر داخل دالة saveProductUpdate():
                // جرب هذا الحل إذا لم ينجح الاحتمال الأول:
                // استبدل سطر apiClient.put القديم بهذا السطر:
                await apiClient.put(`/admin/products/${this.productForm.id}`, {
                    item_name: this.productForm.name,
                    price: this.productForm.price,
                });
                this.closeEditModal();
                globalState.triggerNotificationRefresh();
                await this.fetchDashboardData(); // تحديث البيانات حياً بعد التعديل
            } catch (error) {
                this.productError =
                    "تعذر تعديل المنتج. يرجى التحقق من اتصال السيرفر.";
                console.error(error);
            } finally {
                this.isSavingProduct = false;
            }
        },

        // فتح نافذة تأكيد الحذف
        confirmDeleteProduct(product) {
            this.productError = "";
            this.productToDelete = product;
            this.showDeleteModal = true;
        },

        // إرسال طلب الحذف للسيرفر
        async deleteProductConfirm() {
            if (!this.productToDelete) return;

            this.isDeletingProduct = true;
            this.productError = "";
            try {
                await apiClient.delete(
                    `/admin/products/${this.productToDelete.id}`,
                );
                this.showDeleteModal = false;
                this.productToDelete = null;
                globalState.triggerNotificationRefresh();
                await this.fetchDashboardData();
            } catch (error) {
                this.productError = "تعذر حذف المنتج. يرجى المحاولة لاحقاً.";
                console.error(error);
            } finally {
                this.isDeletingProduct = false;
            }
        },

        /**
         * fetchDashboardData - جلب البيانات المتكاملة من الـ API الحقيقي الخاص بـ Laravel
         */
        async fetchDashboardData() {
            try {
                this.isLoading = true;
                const response = await apiClient.get("/admin/dashboard-data");

                if (response.data && response.data.status === "success") {
                    const serverStats = response.data.stats;

                    // 1. تحديث قيم الكروت الإحصائية بناءً على مفاتيح الخادم المرتجعة
                    this.stats.forEach((stat) => {
                        if (serverStats[stat.key] !== undefined) {
                            stat.value = Number(
                                serverStats[stat.key],
                            ).toLocaleString();
                        }
                    });

                    // 2. تحديث جدول المنتجات الأحدث القادم من قاعدة البيانات
                    this.products = response.data.latest_products || [];

                    // 3. تحديث المتاجر
                    this.shops = response.data.latest_stores || [];
                }
            } catch (error) {
                console.error(
                    "خطأ في الاتصال بالخادم وجلب البيانات الحية:",
                    error,
                );
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * handleLogout - يحذف التوكن من الـ localStorage ويوجه للوجن
         */
        handleLogout() {
            localStorage.removeItem("wafar_token");
            localStorage.removeItem("wafar_user");
            this.$router.push({ name: "AdminLogin" });
        },
    },
};
</script>

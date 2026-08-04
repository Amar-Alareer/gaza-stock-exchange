<template>
    <!-- صفحة إدارة المقالات: عرض + إضافة + تعديل + حذف -->
    <div class="dashboard-page">
        <AdminSidebar />

        <div class="dashboard-main">
            <AdminHeader @logout="handleLogout" />

            <div class="dashboard-content">
                <!-- ترويسة الصفحة + زر إضافة مقال -->
                <div class="articles-header">
                    <div>
                        <h1 class="articles-title">إدارة المقالات</h1>
                        <p class="articles-subtitle">
                            إضافة وتعديل وحذف المقالات المنشورة على المنصة
                        </p>
                    </div>
                    <button
                        class="btn-add"
                        @click="openModal('create')"
                        id="btn-add-article"
                        style="
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                        "
                    >
                        <PlusIcon :size="16" />
                        <span>مقال جديد</span>
                    </button>
                </div>

                <!-- حالة التحميل -->
                <div v-if="isLoading" class="loading-state">
                    <div class="loading-spinner"></div>
                    <span>جاري تحميل المقالات...</span>
                </div>

                <!-- حالة الخطأ -->
                <div v-else-if="fetchError" class="error-banner">
                    <AlertTriangleIcon :size="16" />
                    <span>{{ fetchError }}</span>
                    <button @click="fetchArticles" class="retry-btn">
                        إعادة المحاولة
                    </button>
                </div>

                <!-- جدول المقالات -->
                <section v-else class="panel">
                    <div class="panel__header">
                        <h2>
                            قائمة المقالات
                            <span class="articles-count">{{
                                articles.length
                            }}</span>
                        </h2>
                        <!-- شريط البحث -->
                        <div class="search-box">
                            <span
                                class="search-icon"
                                style="
                                    display: inline-flex;
                                    align-items: center;
                                "
                            >
                                <SearchIcon :size="16" />
                            </span>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="ابحث في المقالات..."
                                id="search-articles"
                            />
                        </div>
                    </div>

                    <!-- الجدول -->
                    <div class="table-wrapper">
                        <table
                            class="articles-table"
                            v-if="filteredArticles.length > 0"
                        >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>عنوان المقال</th>
                                    <th>مقتطف المحتوى</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(article, index) in filteredArticles"
                                    :key="article.id"
                                    class="table-row"
                                >
                                    <td class="td-num">{{ index + 1 }}</td>
                                    <td class="td-title">
                                        <span
                                            class="article-icon"
                                            style="
                                                display: inline-flex;
                                                align-items: center;
                                                margin-left: 8px;
                                                vertical-align: middle;
                                                color: var(--wc-green-bright);
                                            "
                                        >
                                            <NewspaperIcon :size="16" />
                                        </span>
                                        {{ article.title }}
                                    </td>
                                    <td class="td-excerpt">
                                        {{ truncate(article.content, 60) }}
                                    </td>
                                    <td class="td-date">
                                        {{ formatDate(article.created_at) }}
                                    </td>
                                    <td class="td-actions">
                                        <button
                                            class="icon-btn icon-btn--view"
                                            @click="openModal('view', article)"
                                            :id="`btn-view-${article.id}`"
                                            title="عرض"
                                            style="
                                                display: inline-flex;
                                                align-items: center;
                                                justify-content: center;
                                                padding: 6px;
                                                margin-left: 4px;
                                            "
                                        >
                                            <EyeIcon :size="14" />
                                        </button>
                                        <button
                                            class="icon-btn icon-btn--edit"
                                            @click="openModal('edit', article)"
                                            :id="`btn-edit-${article.id}`"
                                            title="تعديل"
                                            style="
                                                display: inline-flex;
                                                align-items: center;
                                                justify-content: center;
                                                padding: 6px;
                                                margin-left: 4px;
                                            "
                                        >
                                            <EditIcon :size="14" />
                                        </button>
                                        <button
                                            class="icon-btn icon-btn--delete"
                                            @click="confirmDelete(article)"
                                            :id="`btn-delete-${article.id}`"
                                            title="حذف"
                                            style="
                                                display: inline-flex;
                                                align-items: center;
                                                justify-content: center;
                                                padding: 6px;
                                            "
                                        >
                                            <TrashIcon :size="14" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- حالة عدم وجود نتائج -->
                        <div
                            v-else
                            class="empty-state"
                            style="
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                padding: 40px 20px;
                            "
                        >
                            <InboxIcon
                                :size="48"
                                style="
                                    color: var(--wc-text-muted);
                                    margin-bottom: 16px;
                                "
                            />
                            <p>
                                {{
                                    searchQuery
                                        ? "لا توجد مقالات تطابق بحثك"
                                        : "لا توجد مقالات بعد. ابدأ بإضافة مقالك الأول!"
                                }}
                            </p>
                            <button
                                v-if="!searchQuery"
                                class="btn-add"
                                @click="openModal('create')"
                                style="
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 6px;
                                    margin-top: 12px;
                                "
                            >
                                <PlusIcon :size="16" />
                                <span>أضف مقالك الأول</span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
            <!-- end dashboard-content -->
        </div>
        <!-- end dashboard-main -->

        <!-- ══════════════════════════════════════════
         Modal: إضافة / تعديل / عرض مقال
    ══════════════════════════════════════════ -->
        <div
            v-if="showModal"
            class="modal-overlay"
            @click.self="closeModal"
            id="article-modal-overlay"
        >
            <div class="modal" role="dialog" aria-modal="true">
                <!-- ترويسة الـ Modal -->
                <div class="modal__header">
                    <h3
                        class="modal__title"
                        style="display: flex; align-items: center; gap: 8px"
                    >
                        <template v-if="modalMode === 'create'">
                            <FileTextIcon
                                :size="18"
                                style="color: var(--wc-green-bright)"
                            />
                            <span>إضافة مقال جديد</span>
                        </template>
                        <template v-else-if="modalMode === 'edit'">
                            <EditIcon
                                :size="18"
                                style="color: var(--wc-green-bright)"
                            />
                            <span>تعديل المقال</span>
                        </template>
                        <template v-else>
                            <EyeIcon
                                :size="18"
                                style="color: var(--wc-green-bright)"
                            />
                            <span>عرض المقال</span>
                        </template>
                    </h3>
                    <button
                        class="modal__close"
                        @click="closeModal"
                        id="btn-close-modal"
                    >
                        ✕
                    </button>
                </div>

                <!-- محتوى الـ Modal -->
                <div class="modal__body">
                    <!-- حالة العرض فقط -->
                    <template v-if="modalMode === 'view'">
                        <div class="view-field">
                            <label>العنوان</label>
                            <div class="view-value">{{ form.title }}</div>
                        </div>
                        <div class="view-field">
                            <label>المحتوى</label>
                            <div class="view-value view-value--content">
                                {{ form.content }}
                            </div>
                        </div>
                    </template>

                    <!-- حالة الإضافة / التعديل -->
                    <template v-else>
                        <div class="form-group">
                            <label class="form-label" for="field-title"
                                >عنوان المقال
                                <span class="required">*</span></label
                            >
                            <input
                                id="field-title"
                                v-model="form.title"
                                type="text"
                                class="form-input"
                                placeholder="أدخل عنوان المقال..."
                                :class="{
                                    'form-input--error': formErrors.title,
                                }"
                            />
                            <span v-if="formErrors.title" class="field-error">{{
                                formErrors.title
                            }}</span>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="field-content"
                                >محتوى المقال
                                <span class="required">*</span></label
                            >
                            <textarea
                                id="field-content"
                                v-model="form.content"
                                class="form-textarea"
                                placeholder="اكتب محتوى المقال هنا..."
                                rows="7"
                                :class="{
                                    'form-input--error': formErrors.content,
                                }"
                            ></textarea>
                            <span
                                v-if="formErrors.content"
                                class="field-error"
                                >{{ formErrors.content }}</span
                            >
                        </div>

                        <!-- رسالة خطأ API -->
                        <div v-if="saveError" class="error-alert">
                            <AlertTriangleIcon :size="16" />
                            <span>{{ saveError }}</span>
                        </div>
                    </template>
                </div>

                <!-- أزرار الـ Modal -->
                <div class="modal__footer">
                    <button
                        class="btn-cancel"
                        @click="closeModal"
                        id="btn-modal-cancel"
                    >
                        إلغاء
                    </button>
                    <button
                        v-if="modalMode !== 'view'"
                        class="btn-save"
                        @click="saveArticle"
                        :disabled="isSaving"
                        id="btn-modal-save"
                    >
                        <span v-if="isSaving" class="btn-spinner"></span>
                        {{
                            isSaving
                                ? "جارٍ الحفظ..."
                                : modalMode === "edit"
                                  ? "تحديث المقال"
                                  : "نشر المقال"
                        }}
                    </button>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════
         Modal: تأكيد الحذف
    ══════════════════════════════════════════ -->
        <div
            v-if="showDeleteConfirm"
            class="modal-overlay"
            @click.self="showDeleteConfirm = false"
            id="delete-modal-overlay"
        >
            <div class="modal modal--sm" role="dialog">
                <div class="modal__header">
                    <h3
                        class="modal__title modal__title--danger"
                        style="display: flex; align-items: center; gap: 8px"
                    >
                        <TrashIcon :size="18" />
                        <span>تأكيد الحذف</span>
                    </h3>
                    <button
                        class="modal__close"
                        @click="showDeleteConfirm = false"
                    >
                        ✕
                    </button>
                </div>
                <div class="modal__body">
                    <p class="delete-warning">
                        هل أنت متأكد من حذف المقال:
                        <strong>{{ articleToDelete?.title }}</strong
                        >؟
                    </p>
                    <p
                        class="delete-note"
                        style="
                            display: flex;
                            align-items: center;
                            gap: 6px;
                            color: var(--wc-danger);
                        "
                    >
                        <AlertTriangleIcon :size="14" />
                        <span>هذا الإجراء لا يمكن التراجع عنه.</span>
                    </p>
                    <div v-if="deleteError" class="error-alert">
                        <AlertTriangleIcon :size="16" />
                        <span>{{ deleteError }}</span>
                    </div>
                </div>
                <div class="modal__footer">
                    <button
                        class="btn-cancel"
                        @click="showDeleteConfirm = false"
                        id="btn-cancel-delete"
                    >
                        إلغاء
                    </button>
                    <button
                        class="btn-delete"
                        @click="deleteArticle"
                        :disabled="isDeleting"
                        id="btn-confirm-delete"
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
import {
    Plus as PlusIcon,
    AlertTriangle as AlertTriangleIcon,
    Search as SearchIcon,
    Newspaper as NewspaperIcon,
    Eye as EyeIcon,
    Edit as EditIcon,
    Trash2 as TrashIcon,
    Inbox as InboxIcon,
    FileText as FileTextIcon,
} from "@lucide/vue";

export default {
    name: "AdminArticles",
    components: {
        AdminSidebar,
        AdminHeader,
        PlusIcon,
        AlertTriangleIcon,
        SearchIcon,
        NewspaperIcon,
        EyeIcon,
        EditIcon,
        TrashIcon,
        InboxIcon,
        FileTextIcon,
    },

    data() {
        return {
            // ——— البيانات الحقيقية فقط ———
            articles: [], // قائمة المقالات القادمة من قاعدة البيانات
            searchQuery: "", // نص البحث

            // ——— حالات التحميل ———
            isLoading: false,
            isSaving: false,
            isDeleting: false,

            // ——— رسائل الخطأ ———
            fetchError: "",
            saveError: "",
            deleteError: "",

            // ——— Modal ———
            showModal: false,
            modalMode: "create",
            currentArticleId: null,

            // ——— نموذج المقال ———
            form: { title: "", content: "" },
            formErrors: { title: "", content: "" },

            // ——— حذف ———
            showDeleteConfirm: false,
            articleToDelete: null,
        };
    },

    computed: {
        filteredArticles() {
            if (!this.searchQuery.trim()) return this.articles;
            const q = this.searchQuery.toLowerCase();
            return this.articles.filter(
                (a) =>
                    a.title.toLowerCase().includes(q) ||
                    (a.content && a.content.toLowerCase().includes(q)),
            );
        },
    },

    mounted() {
        // جلب المقالات الحقيقية فور فتح الصفحة
        this.fetchArticles();
    },

    methods: {
        async fetchArticles() {
            this.isLoading = true;
            this.fetchError = "";
            try {
                const response = await apiClient.get("/articles");
                this.articles = Array.isArray(response.data)
                    ? response.data
                    : response.data.data || [];
            } catch (error) {
                this.fetchError =
                    "تعذّر تحميل المقالات من الخادم. تأكد من عمل السيرفر وصحة المسارات.";
                console.error("خطأ جلب المقالات:", error);
            } finally {
                this.isLoading = false;
            }
        },

        async saveArticle() {
            if (!this.validateForm()) return;

            this.isSaving = true;
            this.saveError = "";
            try {
                if (this.modalMode === "edit") {
                    await apiClient.put(`/articles/${this.currentArticleId}`, {
                        title: this.form.title,
                        content: this.form.content,
                    });
                } else {
                    await apiClient.post("/articles", {
                        title: this.form.title,
                        content: this.form.content,
                    });
                }
                this.closeModal();
                globalState.triggerNotificationRefresh();
                await this.fetchArticles();
            } catch (error) {
                this.saveError =
                    "فشل حفظ البيانات على السيرفر. تحقق من حالة الاتصال بالخادم.";
                console.error("خطأ حفظ المقال:", error);
            } finally {
                this.isSaving = false;
            }
        },

        async deleteArticle() {
            if (!this.articleToDelete) return;
            this.isDeleting = true;
            this.deleteError = "";
            try {
                await apiClient.delete(`/articles/${this.articleToDelete.id}`);
                this.showDeleteConfirm = false;
                this.articleToDelete = null;
                globalState.triggerNotificationRefresh();
                // إعادة تحميل القائمة للتأكد من الحذف التام
                await this.fetchArticles();
            } catch (error) {
                this.deleteError = "فشل حذف المقال من السيرفر. حاول مرة أخرى.";
                console.error("خطأ حذف المقال:", error);
            } finally {
                this.isDeleting = false;
            }
        },

        openModal(mode, article = null) {
            this.modalMode = mode;
            this.saveError = "";
            this.formErrors = { title: "", content: "" };

            if (mode === "create") {
                this.form = { title: "", content: "" };
                this.currentArticleId = null;
            } else {
                // ملء النموذج ببيانات المقال المختار
                this.form = {
                    title: article.title,
                    content: article.content || "",
                };
                this.currentArticleId = article.id;
            }
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.currentArticleId = null;
            this.saveError = "";
        },

        confirmDelete(article) {
            this.articleToDelete = article;
            this.deleteError = "";
            this.showDeleteConfirm = true;
        },

        validateForm() {
            this.formErrors = { title: "", content: "" };
            let valid = true;
            if (!this.form.title.trim()) {
                this.formErrors.title = "عنوان المقال مطلوب.";
                valid = false;
            } else if (this.form.title.trim().length < 3) {
                this.formErrors.title = "العنوان يجب أن يكون 3 أحرف على الأقل.";
                valid = false;
            }
            if (!this.form.content.trim()) {
                this.formErrors.content = "محتوى المقال مطلوب.";
                valid = false;
            } else if (this.form.content.trim().length < 10) {
                this.formErrors.content =
                    "المحتوى يجب أن يكون 10 أحرف على الأقل.";
                valid = false;
            }
            return valid;
        },

        truncate(text, max) {
            if (!text) return "—";
            return text.length > max ? text.substring(0, max) + "..." : text;
        },

        formatDate(dateStr) {
            if (!dateStr) return "—";
            try {
                return new Date(dateStr).toLocaleDateString("ar-EG", {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                });
            } catch {
                return dateStr;
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

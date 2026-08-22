<template>
    <!-- صفحة الإعدادات الشاملة لمنصة وفر كاش -->
    <div class="dashboard-page">
        <AdminSidebar />

        <div class="dashboard-main">
            <AdminHeader @logout="handleLogout" />

            <div class="dashboard-content">
                <!-- الترويسة الرئيسية -->
                <div class="settings-header">
                    <div>
                        <h1 class="settings-title">
                            <SettingsIcon :size="26" class="header-icon" />
                            إعدادات النظام والمنصة
                        </h1>
                        <p class="settings-subtitle">
                            تحكم بالإعدادات العامة للمنصة، تفضيلات المقارنة والأسعار، الحماية، والإشعارات
                        </p>
                    </div>

                    <button class="btn-save-all" @click="saveAllSettings" :disabled="isSaving">
                        <SaveIcon :size="16" />
                        <span v-if="isSaving">جاري الحفظ...</span>
                        <span v-else>حفظ التغييرات</span>
                    </button>
                </div>

                <!-- إشعار النجاح / الخطأ المؤقت -->
                <transition name="fade">
                    <div v-if="toastMessage" class="toast-alert" :class="toastType">
                        <CheckCircleIcon v-if="toastType === 'toast-success'" :size="18" />
                        <AlertTriangleIcon v-else :size="18" />
                        <span>{{ toastMessage }}</span>
                        <button @click="toastMessage = ''" class="toast-close">✕</button>
                    </div>
                </transition>

                <!-- الحاوية الرئيسية للإعدادات: شريط التبويبات + محتوى التبويب -->
                <div class="settings-container">
                    <!-- قائمة التبويبات الجانبية / الأفقية -->
                    <div class="settings-nav">
                        <button
                            v-for="tab in tabs"
                            :key="tab.id"
                            class="tab-btn"
                            :class="{ 'tab-btn--active': activeTab === tab.id }"
                            @click="activeTab = tab.id"
                        >
                            <component :is="tab.icon" :size="18" class="tab-icon" />
                            <span>{{ tab.label }}</span>
                        </button>
                    </div>

                    <!-- محتوى التبويبات -->
                    <div class="settings-body">
                        <!-- ══════════════════════════════════════════════════════
                             1. الإعدادات العامة للمنصة (General Settings)
                        ══════════════════════════════════════════════════════ -->
                        <div v-if="activeTab === 'general'" class="tab-pane">
                            <div class="pane-header">
                                <h2>الإعدادات العامة للمنصة</h2>
                                <p>المعلومات الأساسية وهوية المنصة التي تظهر للجمهور والمستخدمين</p>
                            </div>

                            <div class="form-section">
                                <div class="form-row-grid">
                                    <div class="form-field">
                                        <label>اسم التطبيق / المنصة</label>
                                        <input type="text" v-model="general.appName" class="input-control" />
                                    </div>

                                    <div class="form-field">
                                        <label>العملة الافتراضية</label>
                                        <select v-model="general.currency" class="input-control">
                                            <option value="ILS">شيكل إسرائيلي (₪ - ILS)</option>
                                            <option value="JOD">دينار أردني (JD - JOD)</option>
                                            <option value="USD">دولار أمريكي ($ - USD)</option>
                                        </select>
                                    </div>

                                    <div class="form-field full-width">
                                        <label>وصف المنصة والشعار النصي (Slogan)</label>
                                        <input type="text" v-model="general.slogan" class="input-control" />
                                    </div>

                                    <div class="form-field">
                                        <label>المنطقة الجغرافية الافتراضية</label>
                                        <select v-model="general.defaultRegion" class="input-control">
                                            <option value="غزة - الرمال">غزة - الرمال</option>
                                            <option value="خانيونس - البلد">خانيونس - البلد</option>
                                            <option value="دير البلح">دير البلح</option>
                                            <option value="شمال غزة">شمال غزة</option>
                                            <option value="رفح">رفح</option>
                                            <option value="نابلس">نابلس</option>
                                            <option value="رام الله">رام الله</option>
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label>حالة المنصة</label>
                                        <select v-model="general.siteStatus" class="input-control">
                                            <option value="active">متاحة وتعمل بشكل طبيعي</option>
                                            <option value="maintenance">وضع الصيانة المؤقتة</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="media-upload-grid">
                                    <div class="media-card">
                                        <span class="media-title">شعار المنصة الرسمي (Logo)</span>
                                        <div class="logo-preview-box">
                                            <img v-if="general.logoUrl" :src="general.logoUrl" alt="Logo Preview" style="max-height: 40px; max-width: 100%; object-fit: contain;" />
                                            <WafarLogo v-else :dark="true" />
                                        </div>
                                        <input
                                            type="file"
                                            ref="logoInput"
                                            @change="onLogoChange"
                                            style="display: none"
                                            accept="image/*"
                                        />
                                        <button class="btn-subtle" @click="$refs.logoInput.click()">تغيير الشعار</button>
                                    </div>

                                    <div class="media-card">
                                        <span class="media-title">أيقونة المتصفح (Favicon)</span>
                                        <div class="favicon-preview-box">
                                            <img :src="general.faviconUrl || '/wafer-cash.svg'" alt="Favicon Preview" class="favicon-img" />
                                        </div>
                                        <input
                                            type="file"
                                            ref="faviconInput"
                                            @change="onFaviconChange"
                                            style="display: none"
                                            accept="image/*"
                                        />
                                        <button class="btn-subtle" @click="$refs.faviconInput.click()">تغيير الأيقونة</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════════════════════════════════════════════════
                             2. إعدادات الحساب الشخصي (Profile Settings)
                        ══════════════════════════════════════════════════════ -->
                        <div v-if="activeTab === 'profile'" class="tab-pane">
                            <div class="pane-header">
                                <h2>إعدادات الحساب الشخصي</h2>
                                <p>بيانات حساب مدير النظام الحالي وتغيير بيانات الدخول</p>
                            </div>

                            <!-- بطاقة الصورة الشخصية -->
                            <div class="profile-avatar-row">
                                <img
                                    :src="profile.profile_picture_url || defaultAvatar"
                                    class="avatar-large"
                                    alt="Admin Avatar"
                                />
                                <div class="avatar-info">
                                    <h3>{{ profile.name || 'مدير النظام' }}</h3>
                                    <span class="role-pill">مسؤول رئيسي (Super Admin)</span>
                                    <div class="avatar-actions">
                                        <input
                                            type="file"
                                            ref="avatarInput"
                                            @change="onAvatarFileChange"
                                            style="display: none"
                                            accept="image/*"
                                        />
                                        <button class="btn-subtle" @click="$refs.avatarInput.click()">
                                            <CameraIcon :size="14" />
                                            <span>تغيير الصورة</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3 class="section-title">المعلومات الشخصية</h3>
                                <div class="form-row-grid">
                                    <div class="form-field">
                                        <label>الاسم الكامل</label>
                                        <input type="text" v-model="profile.name" class="input-control" />
                                    </div>

                                    <div class="form-field">
                                        <label>اسم المستخدم</label>
                                        <input type="text" v-model="profile.username" class="input-control" dir="ltr" />
                                    </div>

                                    <div class="form-field">
                                        <label>البريد الإلكتروني</label>
                                        <input type="email" v-model="profile.email" class="input-control" dir="ltr" />
                                    </div>

                                    <div class="form-field">
                                        <label>رقم الهاتف</label>
                                        <input type="tel" v-model="profile.phone" class="input-control" dir="ltr" />
                                    </div>

                                    <div class="form-field full-width">
                                        <label>العنوان الشخصي</label>
                                        <input type="text" v-model="profile.address" class="input-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3 class="section-title">تغيير كلمة المرور</h3>
                                <div class="form-row-grid">
                                    <div class="form-field">
                                        <label>كلمة المرور الحالية</label>
                                        <input type="password" v-model="passwordForm.current" class="input-control" placeholder="••••••••" />
                                    </div>

                                    <div class="form-field">
                                        <label>كلمة المرور الجديدة</label>
                                        <input type="password" v-model="passwordForm.newPassword" class="input-control" placeholder="••••••••" />
                                    </div>

                                    <div class="form-field">
                                        <label>تأكيد كلمة المرور الجديدة</label>
                                        <input type="password" v-model="passwordForm.confirmPassword" class="input-control" placeholder="••••••••" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════════════════════════════════════════════════
                             3. إعدادات المقارنة والأسعار (Comparison & Pricing)
                        ══════════════════════════════════════════════════════ -->
                        <div v-if="activeTab === 'pricing'" class="tab-pane">
                            <div class="pane-header">
                                <h2>إعدادات المقارنة والأسعار</h2>
                                <p>قواعد وخوارزميات مقارنة الأسعار وهوامش التوفير المقترحة</p>
                            </div>

                            <div class="form-section">
                                <div class="form-row-grid">
                                    <div class="form-field">
                                        <label>تكرار تذكير المتاجر بتحديث الأسعار</label>
                                        <select v-model="pricing.updateFrequency" class="input-control">
                                            <option value="daily">يومياً (موصى به للسلع الاستهلاكية)</option>
                                            <option value="3days">كل 3 أيام</option>
                                            <option value="weekly">أسبوعياً</option>
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label>هامش التوفير المميز (نسبة مئوية %)</label>
                                        <div class="input-with-addon">
                                            <input type="number" v-model="pricing.savingMargin" min="1" max="100" class="input-control" />
                                            <span class="addon">%</span>
                                        </div>
                                        <small class="field-hint">النسبة التي يعتبرها النظام "توفيراً استثنائياً" ويبرزها بالأخضر للعملاء.</small>
                                    </div>
                                </div>

                                <div class="switches-list">
                                    <div class="switch-item">
                                        <div class="switch-info">
                                            <h4>تفعيل خوارزمية المقارنة الذكية</h4>
                                            <p>السماح للنظام باقتراح سلال بديلة أقل سعراً وتوزيع السلة بين أقرب المتاجر تلقائياً.</p>
                                        </div>
                                        <label class="switch-toggle">
                                            <input type="checkbox" v-model="pricing.smartComparison" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>

                                    <div class="switch-item">
                                        <div class="switch-info">
                                            <h4>تنبيه الفروقات الشاذة في الأسعار</h4>
                                            <p>إرسال تنبيه للإدارة عند وجود فرق سعر يتجاوز 40% لنفس المنتج للتحقق من دقته.</p>
                                        </div>
                                        <label class="switch-toggle">
                                            <input type="checkbox" v-model="pricing.anomalyAlert" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>

                                    <div class="switch-item">
                                        <div class="switch-info">
                                            <h4>إظهار تاريخ تغير السعر للمستهلك</h4>
                                            <p>عرض رسم بياني يوضح مسار تغير سعر السلعة صعوداً وهبوطاً خلال آخر 30 يوماً.</p>
                                        </div>
                                        <label class="switch-toggle">
                                            <input type="checkbox" v-model="pricing.showPriceHistory" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════════════════════════════════════════════════
                             4. إدارة المتاجر والمنتجات (Stores Logic)
                        ══════════════════════════════════════════════════════ -->
                        <div v-if="activeTab === 'stores'" class="tab-pane">
                            <div class="pane-header">
                                <h2>إدارة المتاجر ورفع المنتجات</h2>
                                <p>شروط إدراج المتاجر، معايير الترتيب، وقيود رفع الصور والمنتجات</p>
                            </div>

                            <div class="form-section">
                                <div class="form-row-grid">
                                    <div class="form-field">
                                        <label>طريقة الترتيب الافتراضية للمتاجر</label>
                                        <select v-model="storesConfig.sortOrder" class="input-control">
                                            <option value="rating">الأعلى تقييماً</option>
                                            <option value="nearest">الأقرب جغرافياً للمستخدم</option>
                                            <option value="latest">الأحدث انضماماً للمنصة</option>
                                            <option value="products_count">الأكثر وفرة بالمنتجات</option>
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label>الحد الأقصى لحجم صور المنتجات المرفوعة</label>
                                        <select v-model="storesConfig.maxImageSize" class="input-control">
                                            <option value="2">2 ميجابايت (MB)</option>
                                            <option value="5">5 ميجابايت (MB) - موصى به</option>
                                            <option value="10">10 ميجابايت (MB)</option>
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label>الحد الأقصى للمنتجات المرفوعة في الدفعة الواحدة (Excel/CSV)</label>
                                        <input type="number" v-model="storesConfig.maxBatchUpload" class="input-control" />
                                    </div>
                                </div>

                                <div class="switches-list">
                                    <div class="switch-item">
                                        <div class="switch-info">
                                            <h4>الموافقة التلقائية على انضمام المتاجر</h4>
                                            <p>تفعيل حساب المتجر فور تسجيله دون الحاجة لمراجعة الإدارة أولاً.</p>
                                        </div>
                                        <label class="switch-toggle">
                                            <input type="checkbox" v-model="storesConfig.autoApproveStores" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>

                                    <div class="switch-item">
                                        <div class="switch-info">
                                            <h4>إلزامية رقم الهاتف المعتمد</h4>
                                            <p>منع المتاجر من إضافة منتجات قبل التحقق من رقم الهاتف والموقع الجغرافي.</p>
                                        </div>
                                        <label class="switch-toggle">
                                            <input type="checkbox" v-model="storesConfig.requireVerifiedPhone" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════════════════════════════════════════════════
                             5. الإشعارات والرسائل (Notifications & Messaging)
                        ══════════════════════════════════════════════════════ -->
                        <div v-if="activeTab === 'notifications'" class="tab-pane">
                            <div class="pane-header">
                                <h2>الإشعارات والرسائل</h2>
                                <p>التحكم بنوعية وتوقيت التنبيهات الموجهة للإدارة، المتاجر، والعملاء</p>
                            </div>

                            <div class="form-section">
                                <h3 class="section-title">إشعارات لوحة الإدارة (Admin Alerts)</h3>
                                <div class="switches-list">
                                    <div class="switch-item">
                                        <div class="switch-info">
                                            <h4>تنبيه عند تسجيل متجر جديد</h4>
                                            <p>إرسال إشعار فوري للوحة التحكم عند تسجيل متجر جديد في المنصة.</p>
                                        </div>
                                        <label class="switch-toggle">
                                            <input type="checkbox" v-model="notifs.adminNewStore" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>

                                    <div class="switch-item">
                                        <div class="switch-info">
                                            <h4>تنبيه عند ورود شكوى أو بلاغ سعر خاطئ</h4>
                                            <p>تنبيه المسؤول مباشرة عند تقديم عميل شكوى على تباين الأسعار في متجر.</p>
                                        </div>
                                        <label class="switch-toggle">
                                            <input type="checkbox" v-model="notifs.adminComplaints" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <h3 class="section-title" style="margin-top: 24px;">إشعارات المتاجر والعملاء</h3>
                                <div class="switches-list">
                                    <div class="switch-item">
                                        <div class="switch-info">
                                            <h4>إرسال تذكيرات آلية لتحديث الأسعار للمتاجر</h4>
                                            <p>إرسال رسائل تذكير للمتاجر التي لم تحدث أسعارها وفق المدة المحددة.</p>
                                        </div>
                                        <label class="switch-toggle">
                                            <input type="checkbox" v-model="notifs.storeReminders" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>

                                    <div class="switch-item">
                                        <div class="switch-info">
                                            <h4>تنبيهات عروض التوفير للعملاء</h4>
                                            <p>إشعار العملاء بالعروض الترويجية وتخفيضات الأسعار القوية في مناطقهم.</p>
                                        </div>
                                        <label class="switch-toggle">
                                            <input type="checkbox" v-model="notifs.customerDeals" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════════════════════════════════════════════════
                             6. الحماية والصلاحيات (Security & Permissions)
                        ══════════════════════════════════════════════════════ -->
                        <div v-if="activeTab === 'security'" class="tab-pane">
                            <div class="pane-header">
                                <h2>الحماية والصلاحيات</h2>
                                <p>إدارة مسؤولي النظام، التحقق الثنائي، وسجل تدقيق العمليات (Audit Log)</p>
                            </div>

                            <div class="form-section">
                                <div class="security-card">
                                    <div class="security-card__icon">
                                        <ShieldCheckIcon :size="28" />
                                    </div>
                                    <div class="security-card__info">
                                        <h4>التحقق بخطوتين (Two-Factor Authentication - 2FA)</h4>
                                        <p>حماية حسابات المشرفين عبر طلب رمز تأكيد عند تسجيل الدخول من أجهزة جديدة.</p>
                                    </div>
                                    <label class="switch-toggle">
                                        <input type="checkbox" v-model="security.twoFactorAuth" />
                                        <span class="slider"></span>
                                    </label>
                                </div>

                                <div class="sub-admins-header">
                                    <h3 class="section-title" style="margin: 0;">فريق المشرفين والمسؤولين</h3>
                                    <button class="btn-subtle" @click="showAddAdminModal = true">
                                        <PlusIcon :size="14" />
                                        <span>إضافة مسؤول جديد</span>
                                    </button>
                                </div>

                                <div class="admins-table-wrap">
                                    <table class="simple-table">
                                        <thead>
                                            <tr>
                                                <th>المسؤول</th>
                                                <th>البريد الإلكتروني</th>
                                                <th>الدور / الصلاحية</th>
                                                <th>الحالة</th>
                                                <th>الإجراء</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="adm in security.adminsList" :key="adm.id">
                                                <td style="font-weight: 700;">{{ adm.name }}</td>
                                                <td dir="ltr" style="text-align: right;">{{ adm.email }}</td>
                                                <td><span class="role-tag">{{ adm.role }}</span></td>
                                                <td><span class="status-dot-active">نشط</span></td>
                                                <td>
                                                    <button class="btn-icon-danger" @click="removeAdmin(adm.id)" :disabled="adm.isSuper" title="حذف">
                                                        <Trash2Icon :size="14" />
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h3 class="section-title" style="margin-top: 28px;">سجل العمليات الأخير (Audit Log)</h3>
                                <div class="audit-log-box">
                                    <div class="audit-item" v-for="log in security.auditLogs" :key="log.id">
                                        <div class="audit-dot"></div>
                                        <div class="audit-content">
                                            <span class="audit-action">{{ log.action }}</span>
                                            <span class="audit-meta">بواسطة {{ log.user }} • {{ log.time }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════════════════════════════════════════════════
                             7. التواصل والدعم والشروط (Support & Legal)
                        ══════════════════════════════════════════════════════ -->
                        <div v-if="activeTab === 'support'" class="tab-pane">
                            <div class="pane-header">
                                <h2>بيانات التواصل والدعم والشروط القانونية</h2>
                                <p>أرقام خدمة العملاء، منصات التواصل الاجتماعي، وسياسة الاستخدام</p>
                            </div>

                            <div class="form-section">
                                <h3 class="section-title">بيانات الدعم الفني</h3>
                                <div class="form-row-grid">
                                    <div class="form-field">
                                        <label>رقم هاتف الدعم الفني</label>
                                        <input type="tel" v-model="support.phone" class="input-control" dir="ltr" placeholder="+970 59-xxxxxxx" />
                                    </div>

                                    <div class="form-field">
                                        <label>بريد الدعم الفني الرسمي</label>
                                        <input type="email" v-model="support.email" class="input-control" dir="ltr" placeholder="support@wafarcash.com" />
                                    </div>

                                    <div class="form-field">
                                        <label>رابط واتساب الدعم الفني</label>
                                        <input type="text" v-model="support.whatsapp" class="input-control" dir="ltr" placeholder="https://wa.me/..." />
                                    </div>
                                </div>

                                <h3 class="section-title" style="margin-top: 24px;">روابط التواصل الاجتماعي الرسمية</h3>
                                <div class="form-row-grid">
                                    <div class="form-field">
                                        <label>صفحة فيسبوك (Facebook)</label>
                                        <input type="text" v-model="support.facebook" class="input-control" dir="ltr" placeholder="https://facebook.com/..." />
                                    </div>

                                    <div class="form-field">
                                        <label>حساب انستغرام (Instagram)</label>
                                        <input type="text" v-model="support.instagram" class="input-control" dir="ltr" placeholder="https://instagram.com/..." />
                                    </div>

                                    <div class="form-field">
                                        <label>قناة تيليجرام (Telegram)</label>
                                        <input type="text" v-model="support.telegram" class="input-control" dir="ltr" placeholder="https://t.me/..." />
                                    </div>
                                </div>

                                <h3 class="section-title" style="margin-top: 24px;">النصوص القانونية وسياسة الاستخدام</h3>
                                <div class="form-row-grid">
                                    <div class="form-field full-width">
                                        <label>شروط وأحكام الاستخدام (Terms of Service)</label>
                                        <textarea v-model="support.terms" rows="4" class="input-control textarea-field" placeholder="اكتب شروط الاستخدام هنا..."></textarea>
                                    </div>

                                    <div class="form-field full-width">
                                        <label>سياسة الخصوصية وحماية البيانات (Privacy Policy)</label>
                                        <textarea v-model="support.privacy" rows="4" class="input-control textarea-field" placeholder="اكتب بنود سياسة الخصوصية هنا..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal إضافة مسؤول فرعي -->
        <div v-if="showAddAdminModal" class="modal-overlay" @click.self="showAddAdminModal = false">
            <div class="modal-card">
                <div class="modal-header">
                    <h3>إضافة مشرف جديد</h3>
                    <button class="modal-close" @click="showAddAdminModal = false">✕</button>
                </div>
                <div class="modal-body">
                    <div class="form-field" style="margin-bottom: 12px;">
                        <label>اسم المشرف</label>
                        <input type="text" v-model="newAdminForm.name" class="input-control" placeholder="الاسم الكامل" />
                    </div>
                    <div class="form-field" style="margin-bottom: 12px;">
                        <label>البريد الإلكتروني</label>
                        <input type="email" v-model="newAdminForm.email" class="input-control" dir="ltr" placeholder="admin@example.com" />
                    </div>
                    <div class="form-field" style="margin-bottom: 12px;">
                        <label>الصلاحية / الدور</label>
                        <select v-model="newAdminForm.role" class="input-control">
                            <option value="مدير متاجر ومنتجات">مدير متاجر ومنتجات</option>
                            <option value="مدير محتوى ومقالات">مدير محتوى ومقالات</option>
                            <option value="دعم فني وخدمة عملاء">دعم فني وخدمة عملاء</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-cancel" @click="showAddAdminModal = false">إلغاء</button>
                        <button class="btn-save" @click="addAdmin">إضافة المشرف</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminSidebar from "../components/AdminSidebar.vue";
import AdminHeader from "../components/AdminHeader.vue";
import WafarLogo from "../components/WafarLogo.vue";
import apiClient from "../api/axios.js";
import {
    Settings as SettingsIcon,
    Sliders as SlidersIcon,
    User as UserIcon,
    BarChart2 as BarChart2Icon,
    Store as StoreIcon,
    Bell as BellIcon,
    Shield as ShieldIcon,
    ShieldCheck as ShieldCheckIcon,
    HelpCircle as HelpCircleIcon,
    Save as SaveIcon,
    Camera as CameraIcon,
    CheckCircle as CheckCircleIcon,
    AlertTriangle as AlertTriangleIcon,
    Plus as PlusIcon,
    Trash2 as Trash2Icon,
    Globe as GlobeIcon,
} from "@lucide/vue";
import { globalState } from "../state.js";

const SETTINGS_STORAGE_KEY = "wafar_app_settings_config";

export default {
    name: "ProfileSettings",
    components: {
        AdminSidebar,
        AdminHeader,
        WafarLogo,
        SettingsIcon,
        SlidersIcon,
        UserIcon,
        BarChart2Icon,
        StoreIcon,
        BellIcon,
        ShieldIcon,
        ShieldCheckIcon,
        HelpCircleIcon,
        SaveIcon,
        CameraIcon,
        CheckCircleIcon,
        AlertTriangleIcon,
        PlusIcon,
        Trash2Icon,
        GlobeIcon,
    },
    data() {
        return {
            activeTab: "general",
            isSaving: false,
            toastMessage: "",
            toastType: "toast-success",
            showAddAdminModal: false,
            avatarFile: null,

            tabs: [
                { id: "general", label: "الإعدادات العامة", icon: SlidersIcon },
                { id: "profile", label: "الحساب الشخصي", icon: UserIcon },
                { id: "pricing", label: "المقارنة والأسعار", icon: BarChart2Icon },
                { id: "stores", label: "المتاجر والمنتجات", icon: StoreIcon },
                { id: "notifications", label: "الإشعارات والرسائل", icon: BellIcon },
                { id: "security", label: "الحماية والصلاحيات", icon: ShieldIcon },
                { id: "support", label: "التواصل والدعم", icon: HelpCircleIcon },
            ],

            // 1. General
            general: {
                appName: "وفر كاش - Wafar Cash",
                currency: "ILS",
                slogan: "دليلك الأسرع والأذكى للتوفير ومقارنة الأسعار في قطاع غزة وفلسطين",
                defaultRegion: "غزة - الرمال",
                siteStatus: "active",
                logoUrl: "",
                faviconUrl: "/wafer-cash.svg",
            },

            // 2. Profile
            profile: {
                name: "",
                username: "",
                email: "",
                phone: "",
                address: "",
                profile_picture_url: "",
            },
            passwordForm: {
                current: "",
                newPassword: "",
                confirmPassword: "",
            },
            defaultAvatar: "https://api.dicebear.com/7.x/initials/svg?seed=Admin&backgroundColor=17692e",

            // 3. Pricing
            pricing: {
                updateFrequency: "daily",
                savingMargin: 10,
                smartComparison: true,
                anomalyAlert: true,
                showPriceHistory: true,
            },

            // 4. Stores Logic
            storesConfig: {
                sortOrder: "rating",
                maxImageSize: "5",
                maxBatchUpload: 200,
                autoApproveStores: false,
                requireVerifiedPhone: true,
            },

            // 5. Notifications
            notifs: {
                adminNewStore: true,
                adminComplaints: true,
                storeReminders: true,
                customerDeals: true,
            },

            // 6. Security
            security: {
                twoFactorAuth: false,
                adminsList: [
                    { id: 1, name: "محمد محمود الحويطي", email: "admin@wafarcash.com", role: "مسؤول رئيسي (Super Admin)", isSuper: true },
                    { id: 2, name: "أحمد النجار", email: "a.najjar@wafarcash.com", role: "مدير متاجر ومنتجات", isSuper: false },
                ],
                auditLogs: [
                    { id: 1, action: "تعديل إعدادات هامش التوفير للمقارنات الذكية", user: "محمد الحويطي", time: "اليوم 10:45 ص" },
                    { id: 2, action: "الموافقة على متجر 'هايبر ماركت الأندلس'", user: "أحمد النجار", time: "أمس 04:20 م" },
                    { id: 3, action: "تحديث شروط الاستخدام وسياسة الخصوصية", user: "محمد الحويطي", time: "منذ 3 أيام" },
                ],
            },
            newAdminForm: {
                name: "",
                email: "",
                role: "مدير متاجر ومنتجات",
            },

            // 7. Support & Legal
            support: {
                phone: "+970 59-9000000",
                email: "support@wafarcash.com",
                whatsapp: "https://wa.me/970599000000",
                facebook: "https://facebook.com/wafarcash",
                instagram: "https://instagram.com/wafarcash",
                telegram: "https://t.me/wafarcash",
                terms: "أهلاً بك في منصة وفر كاش. باستخدامك لهذه المنصة، فإنك توافق على الالتزام بكافة الشروط والأحكام الخاصة بمقارنة الأسعار وعروض المتاجر التابعة للمنصة...",
                privacy: "نحن في وفر كاش نلتزم بحماية خصوصية بيانات المستخدمين والمتاجر المشتركة وعدم مشاركتها مع أي أطراف ثالثة دون إذن مسبق...",
            },
        };
    },
    created() {
        this.loadProfileFromUser();
        this.loadSettings();
    },
    methods: {
        async loadProfileFromUser() {
            if (globalState.currentUser) {
                this.profile.name = globalState.currentUser.name || "محمد محمود الحويطي";
                this.profile.email = globalState.currentUser.email || "admin@wafarcash.com";
                this.profile.username = globalState.currentUser.username || "mohammed_admin";
                this.profile.phone = globalState.currentUser.phone || "0599000000";
                this.profile.address = globalState.currentUser.address || "غزة - الرمال";
                this.profile.profile_picture_url = globalState.currentUser.profile_picture_url || "";
            } else {
                const rawUser = localStorage.getItem("wafar_user");
                if (rawUser) {
                    try {
                        const u = JSON.parse(rawUser);
                        this.profile.name = u.name || "محمد محمود الحويطي";
                        this.profile.email = u.email || "admin@wafarcash.com";
                        this.profile.username = u.username || "mohammed_admin";
                        this.profile.phone = u.phone || "0599000000";
                        this.profile.address = u.address || "غزة - الرمال";
                        this.profile.profile_picture_url = u.profile_picture_url || "";
                        globalState.setCurrentUser(u);
                    } catch (e) {
                        console.error("Error parsing user from localStorage", e);
                    }
                }
            }

            // محاولة جلب أحدث بيانات من الباك إند
            try {
                const res = await apiClient.get("/admin/profile");
                if (res.data && res.data.status === "success" && res.data.user) {
                    const u = res.data.user;
                    this.profile.name = u.name || this.profile.name;
                    this.profile.email = u.email || this.profile.email;
                    this.profile.username = u.username || this.profile.username;
                    this.profile.phone = u.phone || this.profile.phone;
                    this.profile.address = u.address || this.profile.address;
                    if (u.profile_picture_url) {
                        this.profile.profile_picture_url = u.profile_picture_url;
                    }
                    globalState.setCurrentUser(u);
                }
            } catch (err) {
                // local fallback
            }
        },

        loadSettings() {
            const saved = localStorage.getItem(SETTINGS_STORAGE_KEY);
            if (saved) {
                try {
                    const parsed = JSON.parse(saved);
                    if (parsed.general) this.general = { ...this.general, ...parsed.general };
                    if (parsed.pricing) this.pricing = { ...this.pricing, ...parsed.pricing };
                    if (parsed.storesConfig) this.storesConfig = { ...this.storesConfig, ...parsed.storesConfig };
                    if (parsed.notifs) this.notifs = { ...this.notifs, ...parsed.notifs };
                    if (parsed.security) this.security = { ...this.security, ...parsed.security };
                    if (parsed.support) this.support = { ...this.support, ...parsed.support };
                    if (this.general.faviconUrl) {
                        this.updateBrowserFavicon(this.general.faviconUrl);
                    }
                } catch (e) {
                    console.error("Error parsing saved settings", e);
                }
            } else {
                this.updateBrowserFavicon(this.general.faviconUrl);
            }
        },

        async saveAllSettings() {
            this.isSaving = true;

            // 1. حفظ في localStorage
            const settingsData = {
                general: this.general,
                pricing: this.pricing,
                storesConfig: this.storesConfig,
                notifs: this.notifs,
                security: this.security,
                support: this.support,
            };
            localStorage.setItem(SETTINGS_STORAGE_KEY, JSON.stringify(settingsData));

            // 2. تحديث الحساب محلياً في globalState والهيدر
            globalState.setCurrentUser({
                name: this.profile.name,
                email: this.profile.email,
                username: this.profile.username,
                phone: this.profile.phone,
                address: this.profile.address,
                profile_picture_url: this.profile.profile_picture_url,
            });

            // 3. إرسال الطلب للسيرفر عبر FormData لدعم رفع الصورة إن وجدت
            try {
                const formData = new FormData();
                formData.append("name", this.profile.name || "");
                formData.append("email", this.profile.email || "");
                if (this.profile.username) formData.append("username", this.profile.username);
                if (this.profile.phone) formData.append("phone", this.profile.phone);
                if (this.profile.address) formData.append("address", this.profile.address);
                if (this.avatarFile) formData.append("profile_picture", this.avatarFile);
                if (this.passwordForm.current) formData.append("current_password", this.passwordForm.current);
                if (this.passwordForm.newPassword) formData.append("new_password", this.passwordForm.newPassword);

                const response = await apiClient.post("/admin/profile", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                if (response.data && response.data.status === "success" && response.data.user) {
                    if (response.data.user.profile_picture_url) {
                        this.profile.profile_picture_url = response.data.user.profile_picture_url;
                    }
                    globalState.setCurrentUser(response.data.user);
                }
            } catch (err) {
                console.log("Profile updated in local state", err);
            }

            // إضافة سجل في سجل العمليات
            this.security.auditLogs.unshift({
                id: Date.now(),
                action: "حفظ وتحديث إعدادات المنصة الشاملة",
                user: this.profile.name || "الآدمن",
                time: "الآن",
            });
            localStorage.setItem(SETTINGS_STORAGE_KEY, JSON.stringify(settingsData));

            this.isSaving = false;
            this.showToast("تم حفظ جميع التغييرات وتحديث الصورة والبيانات بنجاح!", "toast-success");
        },

        addAdmin() {
            if (!this.newAdminForm.name || !this.newAdminForm.email) {
                this.showToast("يرجى إدخال اسم وبريد المشرف الجديد", "toast-error");
                return;
            }
            this.security.adminsList.push({
                id: Date.now(),
                name: this.newAdminForm.name,
                email: this.newAdminForm.email,
                role: this.newAdminForm.role,
                isSuper: false,
            });
            this.newAdminForm = { name: "", email: "", role: "مدير متاجر ومنتجات" };
            this.showAddAdminModal = false;
            this.saveAllSettings();
        },

        removeAdmin(id) {
            this.security.adminsList = this.security.adminsList.filter((a) => a.id !== id);
            this.saveAllSettings();
        },

        onLogoChange(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.general.logoUrl = event.target.result;
                    this.saveAllSettings();
                    this.showToast("تم تحديث شعار المنصة بنجاح", "toast-success");
                };
                reader.readAsDataURL(file);
            }
        },

        onFaviconChange(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.general.faviconUrl = event.target.result;
                    this.updateBrowserFavicon(event.target.result);
                    this.saveAllSettings();
                    this.showToast("تم تحديث أيقونة المتصفح (Favicon) بنجاح", "toast-success");
                };
                reader.readAsDataURL(file);
            }
        },

        updateBrowserFavicon(url) {
            if (!url) return;
            let link = document.querySelector("link[rel~='icon']");
            if (!link) {
                link = document.createElement("link");
                link.rel = "icon";
                document.getElementsByTagName("head")[0].appendChild(link);
            }
            link.href = url;
        },

        onAvatarFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.avatarFile = file;
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.profile.profile_picture_url = event.target.result;
                    // تحديث فوري ولحظي للهيدر العلوي والتطبيق بالكامل
                    globalState.setCurrentUser({
                        profile_picture_url: event.target.result,
                        name: this.profile.name,
                    });
                    this.showToast("تم تحديث صورة الملف الشخصي بنجاح", "toast-success");
                };
                reader.readAsDataURL(file);
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
.settings-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.settings-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--wc-green-dark);
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-icon {
    color: var(--wc-green-bright);
}

.settings-subtitle {
    font-size: 14px;
    color: var(--wc-text-muted);
    margin: 0;
}

.btn-save-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--wc-green);
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    padding: 11px 24px;
    border-radius: var(--wc-radius);
    border: none;
    transition: background 0.2s, transform 0.15s;
    box-shadow: 0 4px 12px rgba(23, 105, 46, 0.2);
}

.btn-save-all:hover {
    background: var(--wc-green-dark);
    transform: translateY(-1px);
}

/* Toast */
.toast-alert {
    padding: 12px 18px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 12px;
}

.toast-success {
    background: #e8f5ea;
    color: var(--wc-green-dark);
    border: 1px solid #c2e7c9;
}

.toast-error {
    background: #fee2e2;
    color: var(--wc-danger);
    border: 1px solid #fecaca;
}

.toast-close {
    margin-right: auto;
    background: none;
    border: none;
    color: inherit;
    font-weight: bold;
}

/* حاوية الإعدادات والتبويبات */
.settings-container {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 24px;
    align-items: start;
}

/* قائمة التبويبات */
.settings-nav {
    background: var(--wc-white);
    border-radius: var(--wc-radius);
    border: 1px solid var(--wc-border);
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    box-shadow: var(--wc-shadow);
}

.tab-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    border: none;
    background: none;
    font-size: 14px;
    font-weight: 600;
    color: var(--wc-text-muted);
    transition: all 0.2s;
    text-align: right;
    width: 100%;
}

.tab-btn:hover {
    background: var(--wc-gray-bg);
    color: var(--wc-text-dark);
}

.tab-btn--active {
    background: var(--wc-green-light) !important;
    color: var(--wc-green) !important;
    font-weight: 700;
}

.tab-icon {
    flex-shrink: 0;
}

/* محتوى التبويب */
.settings-body {
    background: var(--wc-white);
    border-radius: var(--wc-radius);
    border: 1px solid var(--wc-border);
    padding: 28px;
    box-shadow: var(--wc-shadow);
}

.pane-header {
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--wc-border);
}

.pane-header h2 {
    font-size: 20px;
    font-weight: 800;
    color: var(--wc-green-dark);
    margin: 0 0 4px 0;
}

.pane-header p {
    font-size: 13px;
    color: var(--wc-text-muted);
    margin: 0;
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.section-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--wc-text-dark);
    margin: 0;
}

.form-row-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-field.full-width {
    grid-column: 1 / -1;
}

.form-field label {
    font-size: 13px;
    font-weight: 600;
    color: var(--wc-text-dark);
}

.input-control {
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid var(--wc-border);
    background: var(--wc-gray-bg);
    font-size: 13px;
    color: var(--wc-text-dark);
    transition: all 0.2s;
}

.input-control:focus {
    outline: none;
    border-color: var(--wc-green-bright);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(34, 168, 62, 0.15);
}

.textarea-field {
    resize: vertical;
    line-height: 1.6;
}

.input-with-addon {
    position: relative;
    display: flex;
    align-items: center;
}

.input-with-addon .addon {
    position: absolute;
    left: 12px;
    color: var(--wc-text-muted);
    font-weight: 700;
}

.field-hint {
    font-size: 11px;
    color: var(--wc-text-muted);
}

/* Media Cards */
.media-upload-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-top: 10px;
}

.media-card {
    background: #fafbfb;
    border: 1px dashed var(--wc-border);
    border-radius: 8px;
    padding: 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    text-align: center;
}

.media-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--wc-text-dark);
}

.logo-preview-box {
    background: var(--wc-green-dark);
    padding: 12px 24px;
    border-radius: 8px;
}

.favicon-preview-box {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    background: #fff;
    border: 1px solid var(--wc-border);
    display: flex;
    align-items: center;
    justify-content: center;
}

.favicon-img {
    width: 28px;
    height: 28px;
}

.btn-subtle {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--wc-white);
    border: 1px solid var(--wc-border);
    color: var(--wc-text-dark);
    padding: 7px 16px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-subtle:hover {
    background: var(--wc-gray-bg);
    border-color: #cbd5e1;
}

/* Profile Avatar Row */
.profile-avatar-row {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--wc-border);
    margin-bottom: 20px;
}

.avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--wc-green-bright);
}

.avatar-info h3 {
    margin: 0 0 6px 0;
    font-size: 18px;
    color: var(--wc-text-dark);
}

.role-pill {
    background: var(--wc-green-light);
    color: var(--wc-green);
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 10px;
}

/* Switches List */
.switches-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.switch-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: #fafbfb;
    border: 1px solid var(--wc-border);
    border-radius: 8px;
    padding: 14px 18px;
}

.switch-info h4 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 700;
    color: var(--wc-text-dark);
}

.switch-info p {
    margin: 0;
    font-size: 12px;
    color: var(--wc-text-muted);
}

/* Switch Toggle CSS */
.switch-toggle {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}

.switch-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.3s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    right: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: var(--wc-green-bright);
}

input:checked + .slider:before {
    transform: translateX(-20px);
}

/* Security Card */
.security-card {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.security-card__icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: var(--wc-green-light);
    color: var(--wc-green);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.security-card__info {
    flex: 1;
}

.security-card__info h4 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 700;
    color: var(--wc-green-dark);
}

.security-card__info p {
    margin: 0;
    font-size: 12px;
    color: var(--wc-text-muted);
}

/* Admins Table */
.sub-admins-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
}

.admins-table-wrap {
    border: 1px solid var(--wc-border);
    border-radius: 8px;
    overflow: hidden;
}

.simple-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.simple-table th {
    background: #fafbfb;
    padding: 10px 14px;
    text-align: right;
    color: var(--wc-text-muted);
    font-size: 12px;
    border-bottom: 1px solid var(--wc-border);
}

.simple-table td {
    padding: 12px 14px;
    border-bottom: 1px solid var(--wc-border);
}

.role-tag {
    background: #f1f5f9;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.status-dot-active {
    color: var(--wc-green);
    font-weight: 700;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-dot-active::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--wc-green-bright);
}

.btn-icon-danger {
    background: none;
    border: none;
    color: var(--wc-danger);
    cursor: pointer;
    padding: 4px;
}

.btn-icon-danger:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

/* Audit Log */
.audit-log-box {
    background: #fafbfb;
    border: 1px solid var(--wc-border);
    border-radius: 8px;
    padding: 14px 18px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.audit-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.audit-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--wc-green);
    margin-top: 5px;
    flex-shrink: 0;
}

.audit-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.audit-action {
    font-size: 13px;
    font-weight: 600;
    color: var(--wc-text-dark);
}

.audit-meta {
    font-size: 11px;
    color: var(--wc-text-muted);
}

/* Modal */
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
    max-width: 480px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid var(--wc-border);
    background: #fafbfb;
}

.modal-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--wc-green-dark);
}

.modal-close {
    background: none;
    border: none;
    font-size: 18px;
    color: var(--wc-text-muted);
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

.btn-cancel {
    padding: 8px 16px;
    border-radius: 6px;
    border: 1px solid var(--wc-border);
    background: var(--wc-gray-bg);
    font-size: 13px;
}

.btn-save {
    padding: 8px 20px;
    border-radius: 6px;
    border: none;
    background: var(--wc-green);
    color: #fff;
    font-weight: 700;
    font-size: 13px;
}

@media (max-width: 900px) {
    .settings-container {
        grid-template-columns: 1fr;
    }
    .form-row-grid,
    .media-upload-grid {
        grid-template-columns: 1fr;
    }
}
</style>

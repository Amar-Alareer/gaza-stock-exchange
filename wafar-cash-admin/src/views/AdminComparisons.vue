<template>
    <!-- صفحة إدارة المقارنات - قيد الإنشاء -->
    <div class="dashboard-page">
        <AdminSidebar />

        <div class="dashboard-main">
            <AdminHeader @logout="handleLogout" />

            <div class="dashboard-content">
                <div class="construction-container">
                    <div class="construction-card">
                        <div class="icon-wrapper">
                            <ClockIcon :size="44" />
                        </div>
                        <h1 class="title">الصفحة قيد الإنشاء</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminSidebar from "../components/AdminSidebar.vue";
import AdminHeader from "../components/AdminHeader.vue";
import { Clock as ClockIcon } from "@lucide/vue";

export default {
    name: "AdminComparisons",
    components: {
        AdminSidebar,
        AdminHeader,
        ClockIcon,
    },
    methods: {
        handleLogout() {
            localStorage.removeItem("wafar_token");
            localStorage.removeItem("wafar_user");
            this.$router.push({ name: "AdminLogin" });
        },
    },
};
</script>

<style scoped>
.construction-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
}

.construction-card {
    background: var(--wc-white);
    border: 1px solid var(--wc-border);
    border-radius: var(--wc-radius);
    padding: 60px 40px;
    box-shadow: var(--wc-shadow);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    width: 100%;
    max-width: 500px;
    text-align: center;
}

.icon-wrapper {
    width: 84px;
    height: 84px;
    border-radius: 50%;
    background: var(--wc-green-light);
    color: var(--wc-green);
    display: flex;
    align-items: center;
    justify-content: center;
}

.title {
    font-size: 24px;
    font-weight: 700;
    color: var(--wc-text-dark);
    margin: 0;
}
</style>

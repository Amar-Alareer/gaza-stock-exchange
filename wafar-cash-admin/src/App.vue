<template>
    <!-- منفذ العرض الرئيسي مع انتقال حركي ناعم بين الصفحات -->
    <router-view v-slot="{ Component }">
        <transition name="admin-page-transition" mode="out-in">
            <component :is="Component" />
        </transition>
    </router-view>
</template>

<script>
export default {
    name: "App",
};
</script>

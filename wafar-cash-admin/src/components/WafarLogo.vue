<template>
    <!-- شعار وفر كاش: اللوجو الجديد -->
    <div class="wc-logo" :class="{ 'wc-logo--dark': dark }">
        <img src="/wafer-cash.svg" class="wc-logo__icon" alt="وفر كاش" />
        <div class="wc-logo__text">
            <span class="wc-logo__name">وفر كاش</span>
            <span class="wc-logo__sub">CASH WAFAR</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "WafarLogo",
    props: {
        dark: {
            type: Boolean,
            default: false,
        },
    },
};
</script>

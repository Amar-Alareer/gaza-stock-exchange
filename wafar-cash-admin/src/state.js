import { reactive } from "vue";

export const globalState = reactive({
    isMobileSidebarOpen: false,
    currentUser: null,
    notifications: [],
    unreadCount: 0,
    notificationRefreshCounter: 0,
    triggerNotificationRefresh() {
        this.notificationRefreshCounter++;
    }
});

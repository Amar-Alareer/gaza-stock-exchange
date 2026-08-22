import { reactive } from "vue";

const savedUser = localStorage.getItem("wafar_user");
let initialUser = null;
if (savedUser) {
    try {
        initialUser = JSON.parse(savedUser);
    } catch (e) {
        console.error("Error parsing user from localStorage:", e);
    }
}

export const globalState = reactive({
    isMobileSidebarOpen: false,
    currentUser: initialUser,
    notifications: [],
    unreadCount: 0,
    notificationRefreshCounter: 0,
    triggerNotificationRefresh() {
        this.notificationRefreshCounter++;
    },
    setCurrentUser(user) {
        this.currentUser = { ...(this.currentUser || {}), ...user };
        localStorage.setItem("wafar_user", JSON.stringify(this.currentUser));
    },
});

import axios from "axios";
import router from "../router/router.js";

const apiClient = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
    timeout: 15000,
});

apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("wafar_token");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    },
);

apiClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response && error.response.status === 401) {
            localStorage.removeItem("wafar_token");
            localStorage.removeItem("wafar_user");
            router.push({ name: "AdminLogin" });
        }
        return Promise.reject(error);
    },
);

export default apiClient;

import axios from "axios";
import router from "@/router"; // Import router to handle redirects

const api = axios.create({
  baseURL: "http://localhost:8000/api",
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Request Interceptor (Already exists, keep this)
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("auth_token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// [NEW] Response Interceptor to handle invalid tokens globally
api.interceptors.response.use(
  (response) => response,
  (error) => {
    // If the server says "Unauthorized" (401)
    if (error.response && error.response.status === 401) {
      // 1. Clear the invalid token
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user");

      // 2. Redirect to login
      router.push("/login");
    }
    return Promise.reject(error);
  }
);

export default api;

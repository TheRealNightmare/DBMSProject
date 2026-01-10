import { createRouter, createWebHistory } from "vue-router";
import LoginView from "../views/auth/LoginView.vue";
import RegisterView from "../views/auth/RegisterView.vue"; // Ensure this file exists
import HomeView from "../views/HomeView.vue";
import ProfileView from "../views/ProfileView.vue";
import HistoryView from "../views/HistoryView.vue";
import ForgotPassword from "../views/auth/ForgotPassword.vue";
import VerifyCode from "../views/auth/VerifyCode.vue";
import ResetPassword from "../views/auth/ResetPassword.vue";

// Lazy load these to avoid circular dependency issues if files are missing
const BookDetailView = () => import("../views/BookDetailView.vue");
const ReaderView = () => import("../views/ReaderView.vue");

const routes = [
  // Auth Pages
  { path: "/login", component: LoginView },
  { path: "/register", component: RegisterView },
  { path: "/forgot-password", component: ForgotPassword },
  { path: "/verify-code", component: VerifyCode },
  { path: "/reset-password", component: ResetPassword },

  // App Pages (Protected)
  { path: "/", component: HomeView },
  { path: "/book/:id", component: BookDetailView },
  { path: "/read/:id", component: ReaderView },
  { path: "/profile", component: ProfileView },
  { path: "/history", component: HistoryView },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

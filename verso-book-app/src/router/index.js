import { createRouter, createWebHistory } from "vue-router";
import LoginView from "../views/auth/LoginView.vue";
import RegisterView from "../views/auth/RegisterView.vue";
import HomeView from "../views/HomeView.vue";
import BookDetailView from "../views/BookDetailView.vue";
import ReaderView from "../views/ReaderView.vue";
import ProfileView from "../views/ProfileView.vue";

const routes = [
  // Auth Pages
  { path: "/login", component: LoginView },
  { path: "/register", component: RegisterView }, // [cite: 9]
  {
    path: "/forgot-password",
    component: () => import("../views/auth/ForgotPassword.vue"),
  },

  // App Pages (Protected)
  { path: "/", component: HomeView }, // [cite: 20]
  { path: "/book/:id", component: BookDetailView }, // [cite: 102]
  { path: "/read/:id", component: ReaderView }, // [cite: 124]
  { path: "/profile", component: ProfileView }, // [cite: 221]
  { path: "/history", component: () => import("../views/HistoryView.vue") },
  { path: "/history", component: HistoryView },
  { path: "/profile", component: ProfileView },
  { path: "/forgot-password", component: ForgotPassword },
  { path: "/verify-code", component: VerifyCode },
  { path: "/reset-password", component: ResetPassword }, // [cite: 173]
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

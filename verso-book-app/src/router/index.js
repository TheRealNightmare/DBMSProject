import { createRouter, createWebHistory } from "vue-router";
import LoginView from "../views/auth/LoginView.vue";
import RegisterView from "../views/auth/RegisterView.vue";
import HomeView from "../views/HomeView.vue";
import ProfileView from "../views/ProfileView.vue";
import HistoryView from "../views/HistoryView.vue";
import ForgotPassword from "../views/auth/ForgotPassword.vue";
import VerifyCode from "../views/auth/VerifyCode.vue";
import ResetPassword from "../views/auth/ResetPassword.vue";

// Lazy load views
const CommunityView = () => import("../views/CommunityView.vue");
const ViewAllBooks = () => import("../views/ViewAllBooks.vue");
const DashboardView = () => import("../views/DashboardView.vue");
const EventView = () => import("../views/EventView.vue");
const BookDetailView = () => import("../views/BookDetailView.vue");
const ReaderView = () => import("../views/ReaderView.vue");
const StorageView = () => import("../views/StorageView.vue");

const routes = [
  // --- Public Routes ---
  { path: "/", redirect: "/login" },
  {
    path: "/login",
    component: LoginView,
    meta: { guest: true },
  },
  {
    path: "/register",
    component: RegisterView,
    meta: { guest: true },
  },
  {
    path: "/forgot-password",
    component: ForgotPassword,
    meta: { guest: true },
  },
  {
    path: "/verify-code",
    component: VerifyCode,
    meta: { guest: true },
  },
  {
    path: "/reset-password",
    component: ResetPassword,
    meta: { guest: true },
  },

  // --- Protected App Pages ---
  {
    path: "/home",
    component: HomeView,
    meta: { requiresAuth: true },
  },
  {
    path: "/dashboard",
    component: DashboardView,
    meta: { requiresAuth: true },
  },
  {
    path: "/events",
    component: EventView,
    meta: { requiresAuth: true },
  },
  {
    path: "/book/:id",
    component: BookDetailView,
    meta: { requiresAuth: true },
  },
  {
    path: "/read/:id",
    component: ReaderView,
    meta: { requiresAuth: true },
  },
  {
    path: "/profile",
    component: ProfileView,
    meta: { requiresAuth: true },
  },
  {
    path: "/history",
    component: HistoryView,
    meta: { requiresAuth: true },
  },
  {
    path: "/storage",
    component: StorageView,
    meta: { requiresAuth: true },
  },
  {
    path: "/community",
    component: CommunityView,
    meta: { requiresAuth: true },
  },
  {
    path: "/category/:category",
    component: ViewAllBooks,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation Guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("auth_token");

  // 1. If route requires auth and user has no token -> Login
  if (to.meta.requiresAuth && !token) {
    next("/login");
  }
  // 2. If route is for guests (login/register) and user HAS token -> Home
  else if (to.meta.guest && token) {
    next("/home");
  }
  // 3. Otherwise proceed
  else {
    next();
  }
});

export default router;

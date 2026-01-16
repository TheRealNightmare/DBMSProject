import { createRouter, createWebHistory } from "vue-router";
import LoginView from "../views/auth/LoginView.vue";
import RegisterView from "../views/auth/RegisterView.vue";
import HomeView from "../views/HomeView.vue";
import ProfileView from "../views/ProfileView.vue";
import HistoryView from "../views/HistoryView.vue";
import ForgotPassword from "../views/auth/ForgotPassword.vue";
import VerifyCode from "../views/auth/VerifyCode.vue";
import ResetPassword from "../views/auth/ResetPassword.vue";
const CommunityView = () => import("../views/CommunityView.vue");
const ViewAllBooks = () => import("../views/ViewAllBooks.vue");
const DashboardView = () => import("../views/DashboardView.vue");
const EventView = () => import("../views/EventView.vue");

// Lazy load views
const BookDetailView = () => import("../views/BookDetailView.vue");
const ReaderView = () => import("../views/ReaderView.vue");
const StorageView = () => import("../views/StorageView.vue");

const routes = [
  // Auth Pages
  // Redirect root to login
  { path: "/", redirect: "/login" },
  { path: "/login", component: LoginView },
  { path: "/register", component: RegisterView },
  { path: "/forgot-password", component: ForgotPassword },
  { path: "/verify-code", component: VerifyCode },
  { path: "/reset-password", component: ResetPassword },

  // App Pages (Protected)
  // Renamed or moved HomeView if needed, or just keep it accessible via /home if you want to preserve it
  { path: "/home", component: HomeView },
  { path: "/dashboard", component: DashboardView },
  { path: "/events", component: EventView },
  { path: "/book/:id", component: BookDetailView },
  { path: "/read/:id", component: ReaderView },
  { path: "/profile", component: ProfileView },
  { path: "/history", component: HistoryView },
  { path: "/storage", component: StorageView },
  { path: "/community", component: CommunityView },
  { path: "/category/:category", component: ViewAllBooks },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import LoginView from "../views/auth/LoginView.vue";
import RegisterView from "../views/auth/RegisterView.vue";
import DashboardView from "../views/DashboardView.vue";
import ViewAllBooks from "../views/ViewAllBooks.vue";
import BookDetailView from "../views/BookDetailView.vue";
import ProfileView from "../views/ProfileView.vue";
import ReaderView from "../views/ReaderView.vue";
import StorageView from "../views/StorageView.vue";
import HistoryView from "../views/HistoryView.vue";
import EventView from "../views/EventView.vue";
import CreateEventView from "../views/CreateEventView.vue";
import CommunityView from "../views/CommunityView.vue";

// [NEW] Import the User Profile View
import UserProfileView from "../views/UserProfileView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/home",
      name: "home-redirect",
      component: HomeView,
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
    },
    {
      path: "/register",
      name: "register",
      component: RegisterView,
    },
    {
      path: "/dashboard",
      name: "dashboard",
      component: DashboardView,
      meta: { requiresAuth: true },
    },
    {
      path: "/books",
      name: "books",
      component: ViewAllBooks,
    },
    {
      path: "/books/:id",
      name: "book-detail",
      component: BookDetailView,
      props: true,
    },
    {
      path: "/profile",
      name: "profile",
      component: ProfileView,
      meta: { requiresAuth: true },
    },
    // [NEW] Added route for public user profiles
    {
      path: "/users/:id",
      name: "user-profile",
      component: UserProfileView,
      props: true,
    },
    {
      path: "/read/:id",
      name: "reader",
      component: ReaderView,
      meta: { requiresAuth: true },
    },
    {
      path: "/storage",
      name: "storage",
      component: StorageView,
      meta: { requiresAuth: true },
    },
    {
      path: "/history",
      name: "history",
      component: HistoryView,
      meta: { requiresAuth: true },
    },
    {
      path: "/events",
      name: "events",
      component: EventView,
    },
    {
      path: "/events/create",
      name: "create-event",
      component: CreateEventView,
      meta: { requiresAuth: true },
    },
    {
      path: "/community",
      name: "community",
      component: CommunityView,
      meta: { requiresAuth: true },
    },
  ],
});

// Navigation Guard
router.beforeEach((to, from, next) => {
  const publicPages = ["/login", "/register", "/"];
  const authRequired = !publicPages.includes(to.path);
  const loggedIn = localStorage.getItem("auth_token");

  // If auth is required and user is not logged in, redirect to login
  if (to.meta.requiresAuth && !loggedIn) {
    return next("/login");
  }

  next();
});

export default router;

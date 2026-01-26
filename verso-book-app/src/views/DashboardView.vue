<template>
  <div
    class="min-h-screen bg-verso-cream font-sans flex flex-col md:flex-row overflow-hidden"
  >
    <Sidebar />

    <div
      class="flex-1 flex flex-col md:flex-row md:ml-20 h-screen overflow-hidden"
    >
      <main class="flex-1 flex flex-col p-6 md:p-8 overflow-y-auto bg-gradient-to-br from-verso-cream to-amber-50/30">
        <!-- Header Section -->
        <header class="mb-8">
          <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
            <div>
              <h1
                class="text-3xl md:text-4xl font-bold text-verso-dark flex items-center gap-3 mb-2"
              >
                Hello, {{ user.username || "Reader" }}
                <span class="text-3xl animate-wave">👋</span>
              </h1>
              <p class="text-sm md:text-base text-gray-600">
                Let's read something new today! Keep up the great work.
              </p>
            </div>

            <div class="flex items-center gap-4">
              <div class="relative group">
                <Search
                  class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 group-focus-within:text-verso-blue transition"
                />
                <input
                  type="text"
                  placeholder="Search books..."
                  class="bg-white pl-12 pr-4 py-3 text-sm text-verso-dark rounded-xl border-2 border-gray-200 focus:outline-none focus:border-verso-blue placeholder-gray-400 w-full md:w-64 transition-all shadow-sm hover:shadow-md"
                />
              </div>
              <button class="relative p-3 bg-white rounded-xl border-2 border-gray-200 hover:border-verso-blue hover:bg-blue-50 transition-all group shadow-sm hover:shadow-md">
                <Bell class="w-5 h-5 text-gray-600 group-hover:text-verso-blue transition" />
                <div
                  class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"
                ></div>
              </button>
            </div>
          </div>
        </header>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 bg-blue-50 rounded-xl group-hover:bg-verso-blue transition-colors">
                <Book class="w-6 h-6 text-verso-blue group-hover:text-white transition" />
              </div>
              <div class="text-right">
                <p class="text-2xl md:text-3xl font-bold text-verso-dark">{{ stats.total_books }}</p>
                <p class="text-xs md:text-sm text-gray-500 font-medium mt-1">Total Books</p>
              </div>
            </div>
            <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
              <div class="h-full bg-verso-blue rounded-full" style="width: 65%"></div>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 bg-green-50 rounded-xl group-hover:bg-green-500 transition-colors">
                <CheckCircle class="w-6 h-6 text-green-500 group-hover:text-white transition" />
              </div>
              <div class="text-right">
                <p class="text-2xl md:text-3xl font-bold text-verso-dark">{{ stats.completed }}</p>
                <p class="text-xs md:text-sm text-gray-500 font-medium mt-1">Completed</p>
              </div>
            </div>
            <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
              <div class="h-full bg-green-500 rounded-full" style="width: 85%"></div>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 bg-purple-50 rounded-xl group-hover:bg-purple-500 transition-colors">
                <Timer class="w-6 h-6 text-purple-500 group-hover:text-white transition" />
              </div>
              <div class="text-right">
                <p class="text-2xl md:text-3xl font-bold text-verso-dark">{{ stats.quiz_score }}%</p>
                <p class="text-xs md:text-sm text-gray-500 font-medium mt-1">Quiz Score</p>
              </div>
            </div>
            <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
              <div class="h-full bg-purple-500 rounded-full" :style="`width: ${stats.quiz_score}%`"></div>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 bg-orange-50 rounded-xl group-hover:bg-orange-500 transition-colors">
                <Clock class="w-6 h-6 text-orange-500 group-hover:text-white transition" />
              </div>
              <div class="text-right">
                <p class="text-2xl md:text-3xl font-bold text-verso-dark">{{ stats.hours_spent }}</p>
                <p class="text-xs md:text-sm text-gray-500 font-medium mt-1">Hours Spent</p>
              </div>
            </div>
            <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
              <div class="h-full bg-orange-500 rounded-full" style="width: 70%"></div>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
          <div class="lg:col-span-5 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-verso-dark mb-4 flex items-center gap-2 text-lg">
              <TrendingUp class="w-5 h-5 text-verso-blue" />
              Reading Hours
            </h3>
            <HoursChart />
          </div>

          <div class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-verso-dark mb-4 flex items-center gap-2 text-lg">
              <Target class="w-5 h-5 text-green-500" />
              Performance
            </h3>
            <PerformanceGauge />
          </div>

          <div class="lg:col-span-3 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center">
            <h3 class="font-bold text-verso-dark mb-4 text-center text-lg">Progress</h3>
            <WormProgress />
          </div>
        </div>

        <!-- Leaderboard Section -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-verso-dark mb-4 flex items-center gap-2 text-xl">
            <Trophy class="w-6 h-6 text-yellow-500" />
            Leaderboard
          </h3>
          <LeaderboardTable />
        </div>
      </main>

      <DashboardRightPanel />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api"; // [CHANGED] Import API service

import Sidebar from "@/components/layout/Sidebar.vue";
import HoursChart from "@/components/dashboard/HoursChart.vue";
import PerformanceGauge from "@/components/dashboard/PerformanceGauge.vue";
import WormProgress from "@/components/dashboard/WormProgress.vue";
import LeaderboardTable from "@/components/dashboard/LeaderboardTable.vue";
import DashboardRightPanel from "@/components/dashboard/DashboardRightPanel.vue";

import { Search, Bell, Book, CheckCircle, Timer, Clock, TrendingUp, Target, Trophy } from "lucide-vue-next";

// [CHANGED] Reactive State for User and Stats
const user = ref({
  username: "Reader", // Default until loaded
});

const stats = ref({
  total_books: 0,
  completed: 0,
  quiz_score: 0,
  hours_spent: 0,
});

// [CHANGED] Fetch Data on Mount
onMounted(async () => {
  try {
    // 1. Fetch User Profile
    const profileRes = await api.get("/profile"); // Calls ProfileController@show
    if (profileRes.data) {
      user.value = profileRes.data;
    }

    // 2. Fetch Dashboard Stats
    const statsRes = await api.get("/dashboard/stats"); // Calls DashboardController@stats
    if (statsRes.data) {
      stats.value = statsRes.data;
    }
  } catch (error) {
    console.error("Failed to load dashboard data:", error);
    // Note: 401 Unauthorized errors are already handled by api.js interceptors (redirect to login)
  }
});
</script>

<style scoped>
@keyframes wave {
  0% { transform: rotate(0deg); }
  10% { transform: rotate(14deg); }
  20% { transform: rotate(-8deg); }
  30% { transform: rotate(14deg); }
  40% { transform: rotate(-4deg); }
  50% { transform: rotate(10deg); }
  60% { transform: rotate(0deg); }
  100% { transform: rotate(0deg); }
}

.animate-wave {
  animation: wave 2.5s infinite;
  transform-origin: 70% 70%;
  display: inline-block;
}
</style>

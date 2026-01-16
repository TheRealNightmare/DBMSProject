<template>
  <div
    class="min-h-screen bg-[#FDF6E9] font-sans flex flex-col md:flex-row overflow-hidden"
  >
    <Sidebar />

    <div
      class="flex-1 flex flex-col md:flex-row md:ml-20 h-screen overflow-hidden"
    >
      <main class="flex-1 flex flex-col p-8 overflow-y-auto">
        <header class="flex justify-between items-start mb-10">
          <div>
            <h1
              class="text-2xl font-bold text-verso-dark flex items-center gap-2"
            >
              Hello, {{ user.username || "Reader" }}
              <span class="text-2xl">👋</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">
              Let's read something new today!
            </p>
          </div>

          <div class="flex items-center gap-6">
            <div class="relative">
              <Search
                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
              />
              <input
                type="text"
                placeholder="Search from books..."
                class="bg-transparent pl-10 pr-4 py-2 text-sm text-verso-dark focus:outline-none placeholder-gray-400 w-48"
              />
            </div>
            <button class="relative">
              <Bell class="w-5 h-5 text-verso-dark" />
              <div
                class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border border-[#FDF6E9]"
              ></div>
            </button>
          </div>
        </header>

        <div class="flex gap-6 mb-10 overflow-x-auto pb-2">
          <StatCard
            title="Total Books"
            :value="stats.total_books"
            :icon="Book"
          />
          <StatCard
            title="Completed"
            :value="stats.completed"
            :icon="CheckCircle"
          />
          <StatCard
            title="Quiz Score"
            :value="stats.quiz_score"
            :icon="Timer"
          />
          <StatCard
            title="Hours Spent"
            :value="stats.hours_spent"
            :icon="Clock"
          />
        </div>

        <div class="grid grid-cols-12 gap-8 mb-10 h-64">
          <div class="col-span-5 h-full">
            <HoursChart />
          </div>

          <div class="col-span-4 h-full">
            <h3 class="font-bold text-verso-dark mb-4">Performance</h3>
            <PerformanceGauge />
          </div>

          <div class="col-span-3 h-full pt-8">
            <WormProgress />
          </div>
        </div>

        <div class="mt-auto">
          <LeaderboardTable />
        </div>
      </main>

      <DashboardRightPanel />
    </div>

    <div class="md:hidden"></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api"; // [CHANGED] Import API service

import Sidebar from "@/components/layout/Sidebar.vue";
import StatCard from "@/components/dashboard/StatCard.vue";
import HoursChart from "@/components/dashboard/HoursChart.vue";
import PerformanceGauge from "@/components/dashboard/PerformanceGauge.vue";
import WormProgress from "@/components/dashboard/WormProgress.vue";
import LeaderboardTable from "@/components/dashboard/LeaderboardTable.vue";
import DashboardRightPanel from "@/components/dashboard/DashboardRightPanel.vue";

import { Search, Bell, Book, CheckCircle, Timer, Clock } from "lucide-vue-next";

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

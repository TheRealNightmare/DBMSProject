<template>
  <div class="min-h-screen bg-[#FDF6E9] font-sans flex flex-col md:flex-row overflow-hidden">
    <Sidebar />

    <div class="flex-1 flex flex-col md:flex-row md:ml-20 h-screen overflow-hidden">
      
      <main class="flex-1 flex flex-col p-8 overflow-y-auto">
        
        <header class="flex justify-between items-start mb-10">
          <div>
            <h1 class="text-2xl font-bold text-verso-dark flex items-center gap-2">
              Hello, Peter <span class="text-2xl">👋</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Let's read something new today!</p>
          </div>

          <div class="flex items-center gap-6">
            <div class="relative">
               <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
               <input type="text" placeholder="Search from books..." class="bg-transparent pl-10 pr-4 py-2 text-sm text-verso-dark focus:outline-none placeholder-gray-400 w-48" />
            </div>
            <button class="relative">
              <Bell class="w-5 h-5 text-verso-dark" />
              <div class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border border-[#FDF6E9]"></div>
            </button>
          </div>
        </header>

        <div class="flex gap-6 mb-10 overflow-x-auto pb-2">
          <StatCard title="Total Book" value="5000" :icon="Book" />
          <StatCard title="Completed" value="50" :icon="CheckCircle" />
          <StatCard title="Quiz Score" value="50" :icon="Timer" />
          <StatCard title="Lesson" value="30" :icon="Clock" />
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

    <div class="md:hidden">
       </div>
  </div>
</template>

<script setup>
import Sidebar from "@/components/layout/Sidebar.vue";
import StatCard from "@/components/dashboard/StatCard.vue";
import HoursChart from "@/components/dashboard/HoursChart.vue";
import PerformanceGauge from "@/components/dashboard/PerformanceGauge.vue";
import WormProgress from "@/components/dashboard/WormProgress.vue";
import LeaderboardTable from "@/components/dashboard/LeaderboardTable.vue";
import DashboardRightPanel from "@/components/dashboard/DashboardRightPanel.vue";

import { Search, Bell, Book, CheckCircle, Timer, Clock } from 'lucide-vue-next';
</script>
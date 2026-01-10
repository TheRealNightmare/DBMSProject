<template>
  <aside
    class="fixed left-0 top-0 h-screen w-20 bg-white border-r border-gray-100 flex flex-col items-center py-6 z-40 hidden md:flex"
  >
    <div
      class="mb-12 cursor-pointer text-verso-dark"
      @click="$router.push('/')"
    >
      <BookOpen class="w-8 h-8" />
    </div>

    <div class="flex flex-col gap-8 w-full items-center">
      <router-link
        to="/"
        class="p-3 rounded-xl transition text-gray-400 hover:text-verso-blue hover:bg-verso-cream"
        active-class="text-verso-blue bg-verso-cream"
      >
        <Home class="w-6 h-6" />
      </router-link>

      <router-link
        to="/history"
        class="p-3 rounded-xl transition text-gray-400 hover:text-verso-blue hover:bg-verso-cream"
        active-class="text-verso-blue bg-verso-cream"
      >
        <Clock class="w-6 h-6" />
      </router-link>

      <router-link
        to="/storage"
        class="p-3 rounded-xl transition text-gray-400 hover:text-verso-blue hover:bg-verso-cream"
        active-class="text-verso-blue bg-verso-cream"
      >
        <Download class="w-6 h-6" />
      </router-link>

      <button
        class="p-3 rounded-xl transition text-gray-400 hover:text-verso-blue hover:bg-verso-cream"
      >
        <LayoutGrid class="w-6 h-6" />
      </button>

      <button
        class="p-3 rounded-xl transition text-gray-400 hover:text-verso-blue hover:bg-verso-cream"
      >
        <Calendar class="w-6 h-6" />
      </button>

      <button
        class="p-3 rounded-xl transition text-gray-400 hover:text-verso-blue hover:bg-verso-cream"
      >
        <Users class="w-6 h-6" />
      </button>
    </div>
  </aside>
</template>

<script setup>
import {
  BookOpen,
  Home,
  Clock,
  Download,
  LayoutGrid,
  Calendar,
  Users,
} from "lucide-vue-next";
</script>

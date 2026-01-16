<template>
  <aside
    class="fixed left-0 top-0 h-screen w-20 bg-verso-cream border-r border-gray-100 flex flex-col items-center py-6 z-40 hidden md:flex"
  >
    <div
      class="mb-12 cursor-pointer text-verso-dark"
      @click="$router.push('/home')"
    >
      <BookOpen class="w-8 h-8" />
    </div>

    <div class="flex flex-col gap-8 w-full items-center">
      <router-link
        to="/home"
        class="p-3 rounded-xl transition text-gray-400"
        active-class="text-white bg-verso-blue"
      >
        <Home class="w-6 h-6" />
      </router-link>

      <router-link
        to="/history"
        class="p-3 rounded-xl transition text-gray-400"
        active-class="text-white bg-verso-blue"
      >
        <Clock class="w-6 h-6" />
      </router-link>

      <router-link
        to="/storage"
        class="p-3 rounded-xl transition text-gray-400"
        active-class="text-white bg-verso-blue"
      >
        <Download class="w-6 h-6" />
      </router-link>

      <router-link
        to="/dashboard"
        class="p-3 rounded-xl transition text-gray-400"
        active-class="text-white bg-verso-blue"
      >
        <LayoutGrid class="w-6 h-6" />
      </router-link>

      <router-link
        to="/events"
        class="p-3 rounded-xl transition text-gray-400"
        active-class="text-white bg-verso-blue"
      >
        <Calendar class="w-6 h-6" />
      </router-link>

      <router-link
        to="/community"
        class="p-3 rounded-xl transition text-gray-400"
        active-class="text-white bg-verso-blue"
      >
        <Users class="w-6 h-6" />
      </router-link>
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
  Users,
  Calendar,
} from "lucide-vue-next";
</script>

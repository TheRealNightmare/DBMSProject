<template>
  <div
    class="fixed bottom-0 w-full bg-white border-t py-3 px-6 flex justify-between items-center z-20 text-gray-400"
  >
    <button
      @click="$router.push('/home')"
      :class="{ 'text-verso-blue': active === 'home' }"
    >
      <Home class="w-6 h-6" />
    </button>
    <button
      @click="$router.push('/history')"
      :class="{ 'text-verso-blue': active === 'history' }"
    >
      <Clock class="w-6 h-6" />
    </button>
    <button
      @click="$router.push('/profile')"
      :class="{ 'text-verso-blue': active === 'profile' }"
    >
      <User class="w-6 h-6" />
    </button>
  </div>
</template>

<script setup>
import { Home, Clock, User } from "lucide-vue-next";
defineProps(["active"]);
</script>

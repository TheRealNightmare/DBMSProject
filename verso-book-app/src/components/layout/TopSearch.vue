<template>
  <div class="p-4 bg-white sticky top-0 z-10 shadow-sm">
    <div class="relative bg-gray-100 rounded-full px-4 py-3 flex items-center">
      <Search class="w-4 h-4 text-gray-400 mr-2" />
      <input 
        type="text" 
        placeholder="Search book, name, author..." 
        class="bg-transparent w-full outline-none text-sm text-gray-700 placeholder-gray-400"
      />
    </div>
  </div>
</template>

<script setup>
import { Search } from 'lucide-vue-next';
</script>
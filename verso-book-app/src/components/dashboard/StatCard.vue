<template>
  <div
    class="bg-white/80 backdrop-blur-sm p-4 rounded-2xl shadow-sm flex flex-col justify-between h-28 min-w-[140px]"
  >
    <div class="text-gray-500 text-sm font-medium">{{ title }}</div>
    <div class="flex justify-between items-end">
      <span class="text-2xl font-bold text-verso-dark">{{ value }}</span>
      <div class="bg-verso-dark text-white p-1.5 rounded-lg">
        <component :is="icon" class="w-4 h-4" />
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps(["title", "value", "icon"]);
</script>

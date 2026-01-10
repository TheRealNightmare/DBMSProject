<template>
  <div class="flex justify-between items-end mb-3 px-4">
    <h2 class="text-lg font-bold text-verso-dark uppercase">{{ title }}</h2>
    <button v-if="hasLink" class="text-xs text-verso-blue font-medium" @click="$emit('viewAll')">
      (view all)
    </button>
  </div>
</template>

<script setup>
defineProps(['title', 'hasLink']);
</script>
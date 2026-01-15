<template>
  <div
    class="bg-[#CFE2F3] rounded-lg p-4 mb-4 flex justify-between items-end relative overflow-hidden"
  >
    <div class="z-10">
      <p class="text-[10px] text-[#5B7C99] font-medium mb-1">
        Hosted by: {{ host }}
      </p>
      <h3 class="text-verso-dark font-bold text-sm mb-1">{{ title }}</h3>
      <p class="text-[10px] text-verso-dark font-medium max-w-md">
        {{ description }}
      </p>
    </div>
    <div class="text-[10px] text-[#4A6FA5] font-bold z-10 whitespace-nowrap">
      On {{ date }}
    </div>
  </div>
</template>

<script setup>
defineProps({
  host: String,
  title: String,
  description: String,
  date: String,
});
</script>

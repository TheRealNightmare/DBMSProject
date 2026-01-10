<template>
  <div
    class="cursor-pointer group relative flex flex-col w-full"
    @click="$emit('click')"
  >
    <div
      class="relative overflow-hidden rounded-lg shadow-sm group-hover:shadow-md transition duration-300 aspect-[2/3] mb-3"
    >
      <img :src="image" class="w-full h-full object-cover" alt="Book cover" />
      <div
        class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition duration-300"
      ></div>
    </div>

    <div class="flex flex-col">
      <h3
        class="font-bold text-verso-dark text-sm leading-tight mb-1 line-clamp-2"
      >
        {{ title }}
      </h3>
      <p class="text-xs text-gray-500 font-medium truncate">
        {{ author }}
      </p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  author: String,
  image: String,
});
</script>

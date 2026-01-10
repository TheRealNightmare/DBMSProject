<template>
  <div
    class="cursor-pointer group relative"
    :class="
      layout === 'vertical'
        ? 'w-full'
        : 'flex gap-4 bg-white p-3 rounded-xl shadow-sm hover:shadow-md transition w-full'
    "
    @click="$emit('click')"
  >
    <div
      class="relative overflow-hidden rounded-xl"
      :class="
        layout === 'vertical'
          ? 'aspect-[2/3] mb-3 shadow-md'
          : 'w-20 h-28 flex-shrink-0'
      "
    >
      <img
        :src="image"
        class="w-full h-full object-cover transition duration-300 group-hover:scale-105 bg-gray-200"
      />
      <div
        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition duration-300 hidden md:block"
      ></div>
    </div>

    <div
      class="flex flex-col"
      :class="{ 'justify-center': layout === 'horizontal' }"
    >
      <h3
        class="font-bold text-verso-dark leading-tight group-hover:text-verso-blue transition"
        :class="
          layout === 'vertical' ? 'text-base line-clamp-2' : 'text-sm mb-1'
        "
      >
        {{ title }}
      </h3>
      <p
        class="text-sm text-gray-500"
        :class="{ 'mt-1': layout === 'vertical' }"
      >
        {{ author }}
      </p>

      <div v-if="rating" class="mt-2 text-yellow-400 text-xs flex gap-0.5">
        <span v-for="n in 5" :key="n">{{
          n <= Math.round(rating) ? "★" : "☆"
        }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  author: String,
  image: String,
  rating: Number,
  layout: { type: String, default: "vertical" }, // 'vertical' | 'horizontal'
});
</script>

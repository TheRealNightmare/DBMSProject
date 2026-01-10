<template>
  <div 
    class="cursor-pointer group"
    :class="layout === 'vertical' ? 'min-w-[120px] w-[120px]' : 'flex gap-3 bg-white p-2 rounded-lg shadow-sm w-full'"
    @click="$emit('click')"
  >
    <img 
      :src="image" 
      class="object-cover rounded-lg shadow-md bg-gray-200"
      :class="layout === 'vertical' ? 'w-full aspect-[2/3] mb-2' : 'w-16 h-24'"
    />
    
    <div class="flex flex-col justify-center">
      <h3 class="font-bold text-sm leading-tight text-verso-dark line-clamp-2">{{ title }}</h3>
      <p class="text-xs text-gray-500 mt-1">{{ author }}</p>
      
      <div v-if="layout === 'horizontal' && rating" class="mt-2 text-yellow-500 text-xs flex">
        <span v-for="n in 5" :key="n">{{ n <= rating ? '★' : '☆' }}</span>
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
  layout: { type: String, default: 'vertical' } // 'vertical' | 'horizontal'
});
</script>
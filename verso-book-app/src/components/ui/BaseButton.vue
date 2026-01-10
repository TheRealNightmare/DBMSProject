<template>
  <button 
    class="w-full py-3 rounded-xl font-bold transition shadow-md flex justify-center items-center gap-2"
    :class="variantClasses"
  >
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: { type: String, default: 'primary' } // 'primary' or 'outline'
});

const variantClasses = computed(() => {
  return props.variant === 'primary' 
    ? 'bg-verso-blue text-white hover:bg-opacity-90' 
    : 'border-2 border-verso-blue text-verso-blue bg-transparent';
});
</script>
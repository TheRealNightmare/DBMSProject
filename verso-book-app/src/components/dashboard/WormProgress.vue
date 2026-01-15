<template>
  <div class="flex flex-col h-full justify-center">
    <h3 class="font-bold text-verso-dark mb-6">Worm progress</h3>
    <div class="relative w-full h-2 bg-blue-100 rounded-full mt-4">
      <div class="absolute top-0 left-0 h-full bg-verso-dark rounded-full w-[30%]"></div>
      <div class="absolute top-1/2 -translate-y-[85%] left-[30%] -translate-x-1/2 text-verso-dark">
        <Snail class="w-6 h-6 fill-current" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { Snail } from 'lucide-vue-next';
</script>
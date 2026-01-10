<template>
  <div class="min-h-screen bg-verso-cream flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-sm text-center">
      <h1 class="text-2xl font-bold text-verso-dark mb-2">Verify your code</h1>
      <p class="text-gray-500 text-sm mb-8">Please enter the code sent to your email.</p>

      <div class="flex justify-center gap-3 mb-8">
        <input v-for="i in 5" :key="i" maxlength="1" 
          class="w-12 h-12 text-center text-xl font-bold rounded-lg border border-gray-300 focus:border-verso-blue outline-none" 
        />
      </div>

      <BaseButton @click="$router.push('/reset-password')">Verify</BaseButton>
      <p class="mt-6 text-sm text-gray-500">Didn't receive code? <span class="text-verso-blue font-bold cursor-pointer">Resend</span></p>
    </div>
  </div>
</template>

<script setup>
import BaseButton from '@/components/ui/BaseButton.vue';
</script>
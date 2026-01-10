<template>
  <div class="min-h-screen bg-verso-cream flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-sm">
      <button @click="$router.back()" class="mb-6 text-gray-500">← Back</button>
      <h1 class="text-2xl font-bold text-verso-dark mb-2">Forgot Password</h1>
      <p class="text-gray-500 text-sm mb-8">Enter the email address associated with your account.</p>

      <form @submit.prevent="$router.push('/verify-code')" class="space-y-6">
        <BaseInput label="Email or username" placeholder="Enter email" />
        <BaseButton>Send code</BaseButton>
      </form>
    </div>
  </div>
</template>

<script setup>
import BaseInput from '@/components/ui/BaseInput.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
</script>
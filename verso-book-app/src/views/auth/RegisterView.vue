<template>
  <div class="min-h-screen bg-verso-cream flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-sm">
      <h1 class="text-2xl font-bold text-verso-dark mb-6 text-center">Registration</h1>
      <form class="space-y-4">
        <BaseInput label="Username" placeholder="Enter username" />
        <BaseInput label="Email" placeholder="Enter email" />
        <BaseInput label="Password" type="password" />
        <BaseInput label="Confirm Password" type="password" />
        <BaseButton>Sign up</BaseButton>
      </form>
      <p class="mt-4 text-center text-sm text-gray-500">
        Already have an account? <router-link to="/login" class="text-verso-blue font-bold">Login</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import BaseInput from '@/components/ui/BaseInput.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
</script>
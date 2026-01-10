<template>
  <div class="min-h-screen bg-verso-cream flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-sm">
      <h1 class="text-2xl font-bold text-verso-dark mb-2">Create new password</h1>
      <p class="text-gray-500 text-sm mb-8">Your new password must be different from previous used password.</p>

      <form @submit.prevent="handleReset" class="space-y-5">
        <BaseInput label="New Password" type="password" />
        <BaseInput label="Confirm New Password" type="password" />
        <BaseButton>Reset Password</BaseButton>
      </form>
    </div>
  </div>
</template>

<script setup>
import BaseInput from '@/components/ui/BaseInput.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const handleReset = () => {
  alert("Password reset successfully!");
  router.push('/login');
};
</script>
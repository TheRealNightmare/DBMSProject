<template>
  <div class="min-h-screen bg-verso-cream flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-sm">
      <div class="text-center mb-10">
        <h1 class="text-2xl font-bold text-verso-dark">Welcome back worm</h1>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-5">
        <BaseInput v-model="form.email" label="Email" placeholder="Enter your email" />
        <BaseInput v-model="form.password" label="Password" type="password" />

        <div class="flex justify-between text-sm">
          <label class="flex items-center text-gray-600">
            <input type="checkbox" class="rounded border-gray-300 text-verso-blue mr-2"> Remember me
          </label>
          <router-link to="/forgot-password" class="text-verso-blue font-bold">Forgot password?</router-link>
        </div>

        <BaseButton>Start your Journey</BaseButton>
      </form>

      <p class="mt-8 text-center text-gray-500 text-sm">
        Don't have an account? 
        <router-link to="/register" class="text-verso-blue font-bold">Sign up</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import BaseInput from '@/components/ui/BaseInput.vue';
import BaseButton from '@/components/ui/BaseButton.vue';

const form = reactive({ email: '', password: '' });
const handleLogin = () => console.log('Logging in...', form);
</script>
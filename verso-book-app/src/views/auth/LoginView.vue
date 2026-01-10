<template>
  <div
    class="min-h-screen bg-verso-cream flex items-center justify-center p-6 relative overflow-hidden"
  >
    <div
      class="hidden md:block absolute top-0 left-0 w-64 h-64 bg-verso-blue opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2"
    ></div>
    <div
      class="hidden md:block absolute bottom-0 right-0 w-96 h-96 bg-verso-blue opacity-5 rounded-full translate-x-1/3 translate-y-1/3"
    ></div>

    <div
      class="w-full max-w-md bg-white md:p-10 p-6 rounded-2xl shadow-xl z-10"
    >
      <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-verso-dark mb-2">
          Welcome back worm
        </h1>
        <p class="text-gray-400 text-sm">Log in to continue your journey</p>
      </div>

      <div class="mb-6">
        <button
          class="w-full flex justify-center items-center gap-2 border border-gray-300 py-3 rounded-xl hover:bg-gray-50 transition font-medium text-gray-700"
        >
          <span class="text-lg">G</span> Log in using Google
        </button>
        <div class="relative my-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or</span>
          </div>
        </div>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <BaseInput
          v-model="form.email"
          label="Email"
          placeholder="Enter your email"
        />
        <BaseInput v-model="form.password" label="Password" type="password" />

        <div class="flex justify-between items-center text-sm">
          <label class="flex items-center text-gray-600 cursor-pointer">
            <input
              type="checkbox"
              class="rounded border-gray-300 text-verso-blue mr-2 focus:ring-verso-blue"
            />
            Remember me
          </label>
          <router-link
            to="/forgot-password"
            class="text-verso-blue font-bold hover:underline"
            >Forgot password?</router-link
          >
        </div>

        <BaseButton class="w-full shadow-lg shadow-verso-blue/30"
          >Start your Journey</BaseButton
        >
      </form>

      <p class="mt-8 text-center text-gray-500 text-sm">
        Don't have an account?
        <router-link
          to="/register"
          class="text-verso-blue font-bold hover:underline"
          >Sign up</router-link
        >
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import BaseInput from "@/components/ui/BaseInput.vue";
import BaseButton from "@/components/ui/BaseButton.vue";

const router = useRouter();
const form = reactive({ email: "", password: "" });

const handleLogin = () => {
  // Simulate login
  console.log("Logging in...", form);
  router.push("/");
};
</script>

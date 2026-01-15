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
      class="w-full max-w-md bg-white md:p-10 p-8 rounded-2xl shadow-xl z-10"
    >
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-verso-dark mb-2">
          Welcome back worm
        </h1>
      </div>

      <div class="mb-6">
        <button
          class="w-full flex justify-center items-center gap-2 border border-gray-300 py-3 rounded-xl hover:bg-gray-50 transition font-medium text-gray-700"
        >
          <span class="font-bold text-lg">G</span> Log in using Google
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

      <form @submit.prevent="handleLogin" class="space-y-5">
        <BaseInput v-model="form.email" label="Email" placeholder="" />
        <BaseInput v-model="form.password" label="Password" type="password" />

        <div class="flex justify-between items-center text-sm mt-2">
          <label
            class="flex items-center text-gray-600 cursor-pointer select-none"
          >
            <input
              type="checkbox"
              class="rounded border-gray-300 text-verso-blue mr-2 focus:ring-verso-blue h-4 w-4"
            />
            Remember me
          </label>
          <router-link
            to="/forgot-password"
            class="text-verso-blue font-bold hover:underline"
          >
            Forgot password?
          </router-link>
        </div>

        <div class="pt-2">
          <BaseButton
            class="w-full shadow-lg shadow-verso-blue/30 py-3.5 text-lg"
          >
            Start your Journey
          </BaseButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue"; // Added ref for error handling
import { useRouter } from "vue-router";
import BaseInput from "@/components/ui/BaseInput.vue";
import BaseButton from "@/components/ui/BaseButton.vue";
import api from "@/services/api"; // Import the API client

const router = useRouter();
const form = reactive({ email: "", password: "" });
const errorMessage = ref("");

const handleLogin = async () => {
  errorMessage.value = ""; // Reset error
  try {
    // Call the login endpoint defined in api.php
    const response = await api.post("/login", {
      email: form.email,
      password: form.password,
    });

    // Store the token from the response
    localStorage.setItem("auth_token", response.data.token);

    // Optional: Store user info
    localStorage.setItem("user", JSON.stringify(response.data.user));

    console.log("Login successful");
    router.push("/"); // Redirect to home/dashboard
  } catch (error) {
    console.error("Login failed:", error);
    errorMessage.value =
      error.response?.data?.message ||
      "Login failed. Please check your credentials.";
  }
};
</script>

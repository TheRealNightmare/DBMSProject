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
          Create an Account
        </h1>
        <p class="text-verso-blue font-medium">Be a worm</p>
      </div>

      <div class="mb-6">
        <button
          class="w-full flex justify-center items-center gap-2 border border-gray-300 py-3 rounded-xl hover:bg-gray-50 transition font-medium text-gray-700"
        >
          <span class="font-bold text-lg">G</span> Sign up using Google
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

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div
          v-if="errorMessage"
          class="p-3 text-sm text-red-600 bg-red-50 rounded-lg"
        >
          {{ errorMessage }}
        </div>

        <BaseInput
          v-model="form.username"
          label="Username"
          placeholder="Choose a username"
        />
        <BaseInput
          v-model="form.email"
          label="Email"
          placeholder="Enter your email"
          type="email"
        />
        <BaseInput v-model="form.password" label="Password" type="password" />
        <BaseInput
          v-model="form.confirmPassword"
          label="Confirm Password"
          type="password"
        />

        <div class="pt-4">
          <BaseButton
            :disabled="isLoading"
            class="w-full shadow-lg shadow-verso-blue/30 py-3.5 text-lg"
          >
            {{ isLoading ? "Creating Account..." : "Sign Up" }}
          </BaseButton>
        </div>

        <p class="text-center text-sm text-gray-600 mt-4">
          Already have an account?
          <router-link
            to="/login"
            class="text-verso-blue font-bold hover:underline"
            >Log in</router-link
          >
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import BaseInput from "@/components/ui/BaseInput.vue";
import BaseButton from "@/components/ui/BaseButton.vue";
import api from "@/services/api";

const router = useRouter();
const isLoading = ref(false);
const errorMessage = ref("");
const form = reactive({
  username: "",
  email: "",
  password: "",
  confirmPassword: "",
});

const handleRegister = async () => {
  errorMessage.value = "";
  isLoading.value = true;

  try {
    const response = await api.post("/register", {
      username: form.username,
      email: form.email,
      password: form.password,
      password_confirmation: form.confirmPassword, // Laravel requires this field name
    });

    // Store token and redirect
    localStorage.setItem("auth_token", response.data.token);
    localStorage.setItem("user", JSON.stringify(response.data.user));

    router.push("/");
  } catch (error) {
    if (error.response?.data?.errors) {
      // Join Laravel validation errors into a string
      errorMessage.value = Object.values(error.response.data.errors)
        .flat()
        .join(" ");
    } else {
      errorMessage.value =
        error.response?.data?.message || "Registration failed.";
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

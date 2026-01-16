<template>
  <nav class="bg-verso-cream sticky top-0 z-30 pt-4 pb-2 px-8">
    <div class="flex justify-between items-center">
      <div class="flex-1 max-w-xl">
        <div class="relative w-full">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
          >
            <Search class="h-4 w-4 text-verso-dark" />
          </div>
          <input
            type="text"
            class="block w-full pl-10 pr-3 py-2 border-none rounded-full leading-5 bg-transparent placeholder-gray-500 text-verso-dark focus:outline-none focus:ring-0 sm:text-sm"
            placeholder="Search book, name, author..."
          />
        </div>
      </div>

      <div class="flex items-center gap-6">
        <router-link to="/profile" class="flex items-center gap-3">
          <div class="relative">
            <img
              :src="profileImage"
              class="h-10 w-10 rounded-full object-cover border-2 border-green-700"
              :alt="username"
            />
            <div
              class="absolute bottom-0 right-0 h-3 w-3 bg-green-500 rounded-full border-2 border-verso-cream"
            ></div>
          </div>
          <span class="font-bold text-sm text-verso-dark hidden md:block">{{
            username
          }}</span>
        </router-link>

        <button class="text-gray-500 hover:text-verso-dark relative">
          <Bell class="w-5 h-5" />
          <span
            class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full border border-verso-cream"
          ></span>
        </button>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Search, Bell } from "lucide-vue-next";
import { ref, onMounted } from "vue";
import api from "@/services/api";

const username = ref("Mister User");
const profileImage = ref("https://placehold.co/100x100");

onMounted(async () => {
  // 1. Initial load from localStorage (fast)
  const userStr = localStorage.getItem("user");
  if (userStr) {
    try {
      const user = JSON.parse(userStr);
      username.value = user.username || user.name || "Mister User";
      if (user.profile_image) {
        // Handle case where local storage might already have the path
        profileImage.value = user.profile_image.startsWith("http")
          ? user.profile_image
          : `http://localhost:8000/storage/${user.profile_image}`;
      }
    } catch (e) {
      console.error("Error parsing user data");
    }
  }

  // 2. Fetch fresh data from Database
  try {
    const { data } = await api.get("/profile");

    username.value = data.username;

    if (data.profile_image) {
      profileImage.value = `http://localhost:8000/storage/${data.profile_image}`;
    }

    // Update localStorage to keep it fresh
    localStorage.setItem("user", JSON.stringify(data));
  } catch (e) {
    console.error("Failed to fetch fresh profile data", e);
  }
});
</script>

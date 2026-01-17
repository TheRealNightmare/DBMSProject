<template>
  <div class="p-4 bg-white sticky top-0 z-10 shadow-sm relative">
    <div
      class="relative bg-gray-100 rounded-full px-4 py-2 flex items-center justify-between gap-2"
    >
      <div class="flex items-center flex-1">
        <Search class="w-4 h-4 text-gray-400 mr-2" />
        <input
          type="text"
          v-model="searchQuery"
          @keyup.enter="executeSearch"
          placeholder="Search users..."
          class="bg-transparent w-full outline-none text-sm text-gray-700 placeholder-gray-400"
        />
      </div>

      <button
        @click="executeSearch"
        :disabled="isLoading || !searchQuery.trim()"
        class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-full hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
      >
        {{ isLoading ? "..." : "Search" }}
      </button>
    </div>

    <div
      v-if="showResults"
      class="absolute left-0 right-0 top-full mt-2 mx-4 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50"
    >
      <div
        class="flex justify-between items-center p-3 border-b border-gray-100 bg-gray-50"
      >
        <span class="text-xs font-bold text-gray-500 uppercase">Results</span>
        <button
          @click="clearSearch"
          class="text-xs text-blue-500 hover:text-blue-700 font-medium"
        >
          Close
        </button>
      </div>

      <div v-if="users.length > 0" class="max-h-64 overflow-y-auto">
        <div
          v-for="user in users"
          :key="user.user_id"
          @click="goToProfile(user.user_id)"
          class="flex items-center gap-3 p-3 hover:bg-gray-50 cursor-pointer transition border-b border-gray-50 last:border-none"
        >
          <img
            :src="getAvatarUrl(user.profile_image)"
            class="w-10 h-10 rounded-full object-cover bg-gray-200"
          />
          <div>
            <p class="font-bold text-sm text-gray-800">{{ user.username }}</p>
            <p class="text-xs text-gray-500">
              {{ user.followers_count }} followers
            </p>
          </div>
        </div>
      </div>

      <div v-else class="p-6 text-center text-gray-400 text-sm">
        <p>No users found for "{{ searchQuery }}"</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { Search } from "lucide-vue-next";
import api from "@/services/api"; //
import { useRouter } from "vue-router";

const router = useRouter();
const searchQuery = ref("");
const users = ref([]);
const isLoading = ref(false);
const showResults = ref(false);

// Helper for images
const getAvatarUrl = (path) => {
  if (!path) return "https://placehold.co/100x100";
  if (path.startsWith("http")) return path;
  return `http://localhost:8000/storage/${path}`;
};

// Search Logic (Only runs when button clicked)
const executeSearch = async () => {
  if (!searchQuery.value.trim()) return;

  isLoading.value = true;
  showResults.value = false; // Hide previous results while loading

  try {
    // Calls your backend
    const response = await api.get(`/users/search?query=${searchQuery.value}`);
    users.value = response.data;
    showResults.value = true; // Show results after fetch
  } catch (error) {
    console.error("Search failed:", error);
    alert("Something went wrong while searching.");
  } finally {
    isLoading.value = false;
  }
};

// Clear/Close
const clearSearch = () => {
  showResults.value = false;
  users.value = [];
  searchQuery.value = "";
};

// Navigation
const goToProfile = (userId) => {
  showResults.value = false; // Close dropdown
  router.push(`/users/${userId}`);
};
</script>

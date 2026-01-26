<template>
  <nav
    class="bg-verso-cream sticky top-0 z-30 py-4 px-6 md:px-8 border-b border-black/5"
  >
    <div class="flex justify-between items-center gap-4 max-w-7xl mx-auto">
      <div class="flex-1 max-w-2xl relative z-40">
        <div class="relative w-full flex items-center group">
          <div
            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10"
          >
            <Search
              class="h-5 w-5 text-gray-400 group-focus-within:text-verso-blue transition-colors"
            />
          </div>

          <input
            type="text"
            v-model="searchQuery"
            @keyup.enter="executeSearch"
            class="block w-full pl-11 pr-24 py-3 rounded-full border-none bg-white shadow-sm text-verso-dark placeholder-gray-400 focus:ring-2 focus:ring-verso-blue/20 focus:outline-none transition-all"
            placeholder="Search users and books..."
          />

          <button
            @click="executeSearch"
            :disabled="isLoading || !searchQuery.trim()"
            class="absolute right-1.5 top-1.5 bottom-1.5 px-5 bg-verso-blue text-white text-sm font-bold rounded-full hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm flex items-center justify-center active:scale-95"
          >
            <span
              v-if="isLoading"
              class="animate-spin h-4 w-4 border-2 border-white rounded-full border-t-transparent"
            ></span>
            <span v-else>Search</span>
          </button>
        </div>

        <div
          v-if="showResults"
          class="absolute left-0 right-0 top-full mt-3 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden ring-1 ring-black/5 origin-top animate-in fade-in zoom-in-95 duration-200"
        >
          <div
            class="flex justify-between items-center px-4 py-3 border-b border-gray-50 bg-gray-50/80 backdrop-blur-sm"
          >
            <span
              class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"
              >Search Results</span
            >
            <button
              @click="clearSearch"
              class="text-xs text-gray-400 hover:text-verso-dark font-medium transition-colors px-2 py-1 hover:bg-gray-200 rounded"
            >
              Close
            </button>
          </div>

          <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
            <!-- Users Section -->
            <div v-if="searchResults.length > 0" class="border-b border-gray-100">
              <div class="px-4 py-2 bg-gray-50">
                <h3 class="text-xs font-bold text-gray-600 uppercase">Users ({{ searchResults.length }})</h3>
              </div>
              <div
                v-for="user in searchResults"
                :key="user.user_id"
                @click="goToProfile(user.user_id)"
                class="flex items-center gap-4 p-4 hover:bg-gray-50 cursor-pointer transition border-b border-gray-50 last:border-none group"
              >
                <img
                  :src="getAvatarUrl(user.profile_image)"
                  class="w-12 h-12 rounded-full object-cover border border-gray-100 bg-gray-200 group-hover:border-verso-blue/50 transition-colors shadow-sm"
                />

                <div class="flex-1 min-w-0">
                  <div class="flex justify-between items-center">
                    <p
                      class="font-bold text-verso-dark group-hover:text-verso-blue transition-colors truncate text-sm md:text-base"
                    >
                      {{ user.username }}
                    </p>
                    <span
                      v-if="user.is_following"
                      class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium"
                      >Following</span
                    >
                  </div>
                  <p class="text-xs text-gray-500 mt-0.5">
                    {{ user.followers_count }} followers
                  </p>
                </div>
              </div>
            </div>

            <!-- Books Section -->
            <div v-if="bookResults.length > 0">
              <div class="px-4 py-2 bg-gray-50">
                <h3 class="text-xs font-bold text-gray-600 uppercase">Books ({{ bookResults.length }})</h3>
              </div>
              <div
                v-for="book in bookResults"
                :key="book.book_id"
                @click="goToBook(book.book_id)"
                class="flex items-center gap-4 p-4 hover:bg-gray-50 cursor-pointer transition border-b border-gray-50 last:border-none group"
              >
                <img
                  :src="book.cover_image"
                  class="w-12 h-16 rounded object-cover border border-gray-100 bg-gray-200 group-hover:border-verso-blue/50 transition-colors shadow-sm"
                />
                <div class="flex-1 min-w-0">
                  <p
                    class="font-bold text-verso-dark group-hover:text-verso-blue transition-colors truncate text-sm md:text-base"
                  >
                    {{ book.title }}
                  </p>
                  <p class="text-xs text-gray-500 mt-0.5 truncate">{{ book.authors }}</p>
                  <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-yellow-500">★ {{ book.rating_avg }}</span>
                    <span v-if="book.category" class="text-xs text-gray-400">{{ book.category }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- No Results -->
            <div
              v-if="searchResults.length === 0 && bookResults.length === 0"
              class="p-8 text-center flex flex-col items-center justify-center"
            >
              <div class="bg-gray-50 p-3 rounded-full mb-3">
                <Search class="h-6 w-6 text-gray-300" />
              </div>
              <p class="text-gray-500 text-sm">
                No users or books found for "<span class="font-semibold text-verso-dark">{{
                  searchQuery
                }}</span
                >"
              </p>
            </div>
          </div>
        </div>
      </div>
      <div
        class="flex items-center gap-4 md:gap-6 pl-4 border-l border-gray-200/50"
      >
        <button
          class="text-gray-500 hover:text-verso-dark relative p-2 hover:bg-white/50 rounded-full transition-colors"
        >
          <Bell class="w-5 h-5" />
          <span
            class="absolute top-1.5 right-1.5 h-2 w-2 bg-red-500 rounded-full border border-verso-cream"
          ></span>
        </button>

        <router-link to="/profile" class="flex items-center gap-3 group">
          <div class="relative">
            <img
              :src="profileImage"
              class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm group-hover:border-verso-blue transition-colors"
              :alt="username"
            />
            <div
              class="absolute bottom-0 right-0 h-3 w-3 bg-green-500 rounded-full border-2 border-white"
            ></div>
          </div>
          <div class="hidden md:block">
            <p
              class="font-bold text-sm text-verso-dark group-hover:text-verso-blue transition-colors leading-tight"
            >
              {{ username }}
            </p>
            <p class="text-[10px] text-gray-500 font-medium">View Profile</p>
          </div>
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Search, Bell } from "lucide-vue-next";
import { ref, onMounted } from "vue";
import api from "@/services/api";
import { useRouter } from "vue-router";

const router = useRouter();

// --- User Profile State ---
const username = ref("Mister User");
const profileImage = ref("https://placehold.co/100x100");

// --- Search State ---
const searchQuery = ref("");
const searchResults = ref([]); // users
const bookResults = ref([]); // books
const isLoading = ref(false);
const showResults = ref(false);

// --- Helpers ---
const getAvatarUrl = (path) => {
  if (!path) return "https://placehold.co/100x100";
  if (path.startsWith("http")) return path;
  return `http://localhost:8000/storage/${path}`;
};

// --- Actions ---
const executeSearch = async () => {
  if (!searchQuery.value.trim()) return;

  isLoading.value = true;
  showResults.value = false;

  try {
    const response = await api.get(`/search?query=${searchQuery.value}`);
    searchResults.value = response.data.users || [];
    bookResults.value = response.data.books || [];
    showResults.value = true;
  } catch (error) {
    console.error("Search failed", error);
  } finally {
    isLoading.value = false;
  }
};

const clearSearch = () => {
  showResults.value = false;
  searchResults.value = [];
  bookResults.value = [];
  searchQuery.value = "";
};

const goToProfile = (userId) => {
  clearSearch();
  router.push(`/users/${userId}`);
};

const goToBook = (bookId) => {
  clearSearch();
  router.push(`/books/${bookId}`);
};

// --- Lifecycle ---
onMounted(async () => {
  const userStr = localStorage.getItem("user");
  if (userStr) {
    try {
      const user = JSON.parse(userStr);
      username.value = user.username || "Mister User";
      profileImage.value = getAvatarUrl(user.profile_image);
    } catch (e) {
      console.error("Error parsing user data");
    }
  }

  try {
    const { data } = await api.get("/profile");
    username.value = data.username;
    profileImage.value = getAvatarUrl(data.profile_image);
    localStorage.setItem("user", JSON.stringify(data));
  } catch (e) {
    console.error("Failed to fetch fresh profile data", e);

    // [FIXED] Handle 401 Unauthorized errors by logging out
    if (e.response && e.response.status === 401) {
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user");
      router.push("/login");
    }
  }
});
</script>

<style scoped>
/* Custom Scrollbar for dropdown */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
</style>

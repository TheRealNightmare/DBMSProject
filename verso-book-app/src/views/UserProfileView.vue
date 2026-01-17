<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import api from "@/services/api";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import BaseButton from "@/components/ui/BaseButton.vue";

const route = useRoute();
const userId = route.params.id; // Get ID from URL

const user = ref(null);
const isFollowing = ref(false);
const loading = ref(true);
const followLoading = ref(false);

const profileImage = computed(() => {
  if (user.value && user.value.profile_image) {
    return `http://localhost:8000/storage/${user.value.profile_image}`;
  }
  return null;
});

const fetchUserProfile = async () => {
  try {
    const { data } = await api.get(`/users/${userId}`);
    user.value = data.user;
    isFollowing.value = data.is_following;
  } catch (e) {
    console.error("Error fetching user", e);
    alert("User not found");
  } finally {
    loading.value = false;
  }
};

const toggleFollow = async () => {
  followLoading.value = true;
  try {
    if (isFollowing.value) {
      await api.delete(`/users/${userId}/follow`);
      isFollowing.value = false;
      user.value.followers_count--;
    } else {
      await api.post(`/users/${userId}/follow`);
      isFollowing.value = true;
      user.value.followers_count++;
    }
  } catch (e) {
    console.error("Action failed", e);
    alert("Something went wrong.");
  } finally {
    followLoading.value = false;
  }
};

onMounted(() => {
  fetchUserProfile();
});
</script>

<template>
  <div class="flex h-screen bg-verso-cream">
    <Sidebar />
    <div class="flex-1 flex flex-col overflow-hidden relative">
      <Navbar />
      <main class="flex-1 overflow-x-hidden overflow-y-auto w-full p-6">
        <div v-if="loading" class="text-center mt-10 text-gray-500">
          Loading profile...
        </div>

        <div
          v-else-if="user"
          class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden"
        >
          <div class="bg-verso-blue h-32 w-full relative"></div>

          <div class="px-8 pb-8">
            <div class="flex justify-between items-end -mt-12 mb-6">
              <div
                class="w-32 h-32 rounded-full border-4 border-white bg-gray-200 overflow-hidden shadow-lg"
              >
                <img
                  v-if="profileImage"
                  :src="profileImage"
                  class="w-full h-full object-cover"
                />
                <div
                  v-else
                  class="w-full h-full flex items-center justify-center text-gray-400 text-3xl font-bold"
                >
                  {{ user.username.charAt(0).toUpperCase() }}
                </div>
              </div>

              <div class="mb-2 w-40">
                <BaseButton
                  :variant="isFollowing ? 'outline' : 'primary'"
                  @click="toggleFollow"
                  :disabled="followLoading"
                >
                  {{ isFollowing ? "Unfollow" : "Follow" }}
                </BaseButton>
              </div>
            </div>

            <div>
              <h1 class="text-3xl font-bold text-verso-dark">
                {{ user.username }}
              </h1>
              <p class="text-gray-500">
                @{{ user.username.toLowerCase().replace(/\s/g, "") }}
              </p>
            </div>

            <div class="flex space-x-8 my-6 border-y border-gray-100 py-4">
              <div class="text-center">
                <span class="block font-bold text-xl text-verso-dark">{{
                  user.followers_count
                }}</span>
                <span class="text-sm text-gray-500">Followers</span>
              </div>
              <div class="text-center">
                <span class="block font-bold text-xl text-verso-dark">{{
                  user.following_count
                }}</span>
                <span class="text-sm text-gray-500">Following</span>
              </div>
            </div>

            <div class="mb-6">
              <h3 class="font-bold text-lg mb-2 text-verso-dark">About</h3>
              <p class="text-gray-600 leading-relaxed">
                {{ user.bio || "This user hasn't written a bio yet." }}
              </p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

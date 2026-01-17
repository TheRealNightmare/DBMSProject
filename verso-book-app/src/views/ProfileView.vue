<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import BaseInput from "@/components/ui/BaseInput.vue";
import BaseButton from "@/components/ui/BaseButton.vue";

const loading = ref(false);
const previewImage = ref(null);
const file = ref(null);

// [NEW] Refs for stats
const stats = ref({
  followers: 0,
  following: 0,
});

const form = ref({
  username: "",
  email: "",
  bio: "",
});

// Load current profile data
onMounted(async () => {
  try {
    const { data } = await api.get("/profile");
    form.value.username = data.username;
    form.value.email = data.email;
    form.value.bio = data.bio;

    // [NEW] Set stats
    stats.value.followers = data.followers_count || 0;
    stats.value.following = data.following_count || 0;

    if (data.profile_image) {
      previewImage.value = `http://localhost:8000/storage/${data.profile_image}`;
    }
  } catch (e) {
    console.error("Failed to load profile", e);
  }
});

const handleFileChange = (event) => {
  const selected = event.target.files[0];
  if (selected) {
    file.value = selected;
    previewImage.value = URL.createObjectURL(selected);
  }
};

const handleUpdate = async () => {
  loading.value = true;
  try {
    const formData = new FormData();
    formData.append("username", form.value.username);
    formData.append("email", form.value.email);
    formData.append("bio", form.value.bio || "");

    if (file.value) {
      formData.append("profile_image", file.value);
    }

    await api.post("/profile", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    alert("Profile updated successfully!");
  } catch (e) {
    console.error("Failed to update profile", e);
    if (e.response && e.response.data.errors) {
      alert(Object.values(e.response.data.errors).flat().join("\n"));
    } else {
      alert("Error updating profile.");
    }
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="flex h-screen bg-verso-cream">
    <Sidebar />
    <div class="flex-1 flex flex-col overflow-hidden relative">
      <Navbar />
      <main class="flex-1 overflow-x-hidden overflow-y-auto w-full p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-8">
          <div
            class="flex justify-center space-x-12 mb-8 pb-8 border-b border-gray-100"
          >
            <div class="text-center">
              <span class="block font-bold text-2xl text-verso-dark">{{
                stats.followers
              }}</span>
              <span class="text-sm text-gray-500">Followers</span>
            </div>
            <div class="text-center">
              <span class="block font-bold text-2xl text-verso-dark">{{
                stats.following
              }}</span>
              <span class="text-sm text-gray-500">Following</span>
            </div>
          </div>

          <h1 class="text-2xl font-bold text-verso-dark mb-6">Edit Profile</h1>

          <form @submit.prevent="handleUpdate" class="space-y-6">
            <div class="flex flex-col items-center space-y-4">
              <div
                class="w-32 h-32 rounded-full overflow-hidden border-4 border-verso-cream bg-gray-100"
              >
                <img
                  v-if="previewImage"
                  :src="previewImage"
                  alt="Profile Preview"
                  class="w-full h-full object-cover"
                />
                <div
                  v-else
                  class="w-full h-full flex items-center justify-center text-gray-400"
                >
                  <span>No Img</span>
                </div>
              </div>

              <label
                class="cursor-pointer bg-verso-blue text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition"
              >
                <span>Upload New Picture</span>
                <input
                  type="file"
                  class="hidden"
                  @change="handleFileChange"
                  accept="image/*"
                />
              </label>
            </div>

            <BaseInput
              v-model="form.username"
              label="Username"
              placeholder="Your username"
              required
            />

            <BaseInput
              v-model="form.email"
              label="Email Address"
              type="email"
              placeholder="john@example.com"
              required
            />

            <div class="w-full">
              <label class="block text-sm font-medium text-gray-700 mb-1"
                >Bio</label
              >
              <textarea
                v-model="form.bio"
                class="w-full rounded-xl border border-gray-300 shadow-sm p-3 focus:ring-2 focus:ring-verso-blue focus:border-transparent outline-none transition"
                rows="3"
                placeholder="Tell us about yourself..."
              ></textarea>
            </div>

            <div class="pt-4">
              <BaseButton type="submit" :disabled="loading">
                {{ loading ? "Saving..." : "Save Changes" }}
              </BaseButton>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
</template>

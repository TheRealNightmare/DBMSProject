<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "@/services/api";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import BaseInput from "@/components/ui/BaseInput.vue";
import BaseButton from "@/components/ui/BaseButton.vue";
import { CalendarPlus, ArrowLeft } from "lucide-vue-next";

const router = useRouter();
const loading = ref(false);

const form = ref({
  title: "",
  host_name: "",
  category: "General",
  description: "",
  start_time: "",
  end_time: "",
});

const handleCreate = async () => {
  loading.value = true;
  try {
    // Ensure date format matches backend expectations (YYYY-MM-DD HH:mm:ss)
    // HTML datetime-local input returns 'YYYY-MM-DDTHH:mm'.
    // We replace 'T' with a space AND append ':00' for seconds.
    const payload = {
      ...form.value,
      start_time: form.value.start_time.replace("T", " ") + ":00",
      end_time: form.value.end_time
        ? form.value.end_time.replace("T", " ") + ":00"
        : null,
    };

    await api.post("/events", payload);
    router.push("/events");
  } catch (e) {
    console.error("Failed to create event", e);

    // [UPDATED] Better error handling for validation issues
    if (e.response && e.response.status === 422) {
      // Laravel sends validation errors in e.response.data.errors
      const errors = e.response.data.errors;
      // Flatten the errors object into a single string
      const errorMessage = Object.values(errors).flat().join("\n");
      alert("Validation Error:\n" + errorMessage);
    } else {
      alert("Error creating event. Please check console for details.");
    }
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="flex h-screen bg-gradient-to-br from-verso-cream via-amber-50/30 to-blue-50/20">
    <Sidebar />
    <div class="flex-1 flex flex-col overflow-hidden relative">
      <Navbar />
      <main class="flex-1 overflow-x-hidden overflow-y-auto w-full p-4 sm:p-6">
        <div class="max-w-3xl mx-auto">
          <!-- Back Button -->
          <button
            @click="router.push('/events')"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-verso-blue mb-6 transition-colors duration-200 group"
          >
            <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform duration-200" />
            <span class="text-sm font-medium">Back to Events</span>
          </button>

          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-verso-blue to-blue-600 px-8 py-6">
              <div class="flex items-center gap-4">
                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                  <CalendarPlus class="w-8 h-8 text-white" />
                </div>
                <div>
                  <h1 class="text-2xl sm:text-3xl font-bold text-white">
                    Create New Event
                  </h1>
                  <p class="text-blue-100 text-sm mt-1">
                    Share your event with the Verso community
                  </p>
                </div>
              </div>
            </div>

            <!-- Form -->
            <div class="p-6 sm:p-8">

            <form @submit.prevent="handleCreate" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <BaseInput
                  v-model="form.title"
                  label="Event Title"
                  placeholder="e.g. Book Club Meetup"
                  required
                />

                <BaseInput
                  v-model="form.host_name"
                  label="Host Name"
                  placeholder="e.g. Jane Doe"
                  required
                />
              </div>

              <BaseInput
                v-model="form.category"
                label="Category"
                placeholder="e.g. Fiction, Social, Workshop"
              />

              <div class="w-full">
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >Description</label
                >
                <textarea
                  v-model="form.description"
                  class="w-full rounded-xl border border-gray-200 shadow-sm p-4 focus:ring-2 focus:ring-verso-blue focus:border-transparent outline-none transition-all duration-200 hover:border-gray-300 resize-none"
                  rows="4"
                  placeholder="Describe your event in detail..."
                ></textarea>
              </div>

              <div class="bg-gradient-to-br from-blue-50 to-blue-100/30 rounded-xl p-6 border border-blue-200/50">
                <h3 class="text-sm font-semibold text-verso-dark mb-4 flex items-center gap-2">
                  <CalendarPlus class="w-4 h-4 text-verso-blue" />
                  Event Schedule
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                      >Start Time *</label
                    >
                    <input
                      v-model="form.start_time"
                      type="datetime-local"
                      required
                      class="w-full rounded-xl border border-gray-200 shadow-sm p-3 focus:ring-2 focus:ring-verso-blue focus:border-transparent outline-none transition-all duration-200 bg-white"
                    />
                  </div>
                  <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                      >End Time</label
                    >
                    <input
                      v-model="form.end_time"
                      type="datetime-local"
                      class="w-full rounded-xl border border-gray-200 shadow-sm p-3 focus:ring-2 focus:ring-verso-blue focus:border-transparent outline-none transition-all duration-200 bg-white"
                    />
                  </div>
                </div>
              </div>

              <div class="flex gap-3 pt-4">
                <button
                  type="button"
                  @click="router.push('/events')"
                  class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="loading"
                  class="flex-1 px-6 py-3 bg-gradient-to-r from-verso-blue to-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                >
                  {{ loading ? "Creating..." : "Create Event" }}
                </button>
              </div>
            </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

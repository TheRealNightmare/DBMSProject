<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "@/services/api";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import BaseInput from "@/components/ui/BaseInput.vue";
import BaseButton from "@/components/ui/BaseButton.vue";

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
    // HTML datetime-local input returns 'YYYY-MM-DDTHH:mm', we replace T with space
    const payload = {
      ...form.value,
      start_time: form.value.start_time.replace("T", " "),
      end_time: form.value.end_time
        ? form.value.end_time.replace("T", " ")
        : null,
    };

    await api.post("/events", payload);
    router.push("/events");
  } catch (e) {
    console.error("Failed to create event", e);
    alert("Error creating event. Please check your inputs.");
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
          <h1 class="text-2xl font-bold text-verso-dark mb-6">
            Create New Event
          </h1>

          <form @submit.prevent="handleCreate" class="space-y-4">
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

            <BaseInput
              v-model="form.category"
              label="Category"
              placeholder="e.g. Fiction, Social"
            />

            <div class="w-full">
              <label class="block text-sm font-medium text-gray-700 mb-1"
                >Description</label
              >
              <textarea
                v-model="form.description"
                class="w-full rounded-xl border border-gray-300 shadow-sm p-3 focus:ring-2 focus:ring-verso-blue focus:border-transparent outline-none transition"
                rows="3"
              ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="w-full">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Start Time</label
                >
                <input
                  v-model="form.start_time"
                  type="datetime-local"
                  required
                  class="w-full rounded-xl border border-gray-300 p-3"
                />
              </div>
              <div class="w-full">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >End Time</label
                >
                <input
                  v-model="form.end_time"
                  type="datetime-local"
                  class="w-full rounded-xl border border-gray-300 p-3"
                />
              </div>
            </div>

            <div class="pt-4">
              <BaseButton type="submit" :disabled="loading">
                {{ loading ? "Creating..." : "Create Event" }}
              </BaseButton>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
</template>

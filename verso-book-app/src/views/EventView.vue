<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import Sidebar from "@/components/layout/Sidebar.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import EventBanner from "@/components/event/EventBanner.vue";

const events = ref([]);

onMounted(async () => {
  try {
    const res = await api.get("/events");
    events.value = res.data;
  } catch (e) {
    console.error(e);
  }
});
</script>

<template>
  <main class="flex-1 px-8 py-8 max-w-6xl mx-auto w-full">
    <h1 class="text-base font-bold text-verso-dark mb-4">Upcoming events:</h1>

    <div class="bg-[#EFE8D8] rounded-xl p-6 mb-8">
      <div v-if="events.length === 0">No upcoming events.</div>

      <EventBanner
        v-for="event in events"
        :key="event.event_id"
        :host="event.host_name"
        :title="event.title"
        :description="event.description"
        :date="new Date(event.start_time).toLocaleDateString()"
      />
    </div>
  </main>
</template>

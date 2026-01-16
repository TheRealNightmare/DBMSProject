<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router"; // Import router
import api from "@/services/api";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import EventBanner from "@/components/event/EventBanner.vue";
import EventCalendar from "@/components/event/EventCalendar.vue";
import BaseButton from "@/components/ui/BaseButton.vue"; // Import Button

const router = useRouter(); // Initialize router
const events = ref([]);

// Filter for upcoming list and LIMIT TO 7
const upcomingEvents = computed(() => {
  const now = new Date();
  return events.value
    .filter((e) => {
      const eDate = new Date(e.start_time.replace(" ", "T"));
      return eDate > now;
    })
    .slice(0, 7); // Added slice(0, 7) to show only 7 events
});

onMounted(async () => {
  try {
    const res = await api.get("/events");
    events.value = res.data;
  } catch (e) {
    console.error("Error fetching events:", e);
  }
});
</script>

<template>
  <div class="flex h-screen bg-verso-cream">
    <Sidebar />

    <div class="flex-1 flex flex-col overflow-hidden relative">
      <Navbar />

      <main class="flex-1 overflow-x-hidden overflow-y-auto w-full">
        <div class="max-w-6xl mx-auto px-6 py-8">
          <h1 class="text-2xl font-bold text-verso-dark mb-6">Events</h1>
          <div class="w-40">
            <BaseButton @click="router.push('/events/create')">
              + Create Event
            </BaseButton>
          </div>

          <div class="mb-8">
            <EventCalendar :events="events" />
          </div>

          <h2 class="text-base font-bold text-verso-dark mb-4">
            Upcoming events list:
          </h2>
          <div class="bg-[#EFE8D8] rounded-xl p-6 mb-20">
            <div
              v-if="upcomingEvents.length === 0"
              class="text-gray-500 text-sm"
            >
              No upcoming events found.
            </div>

            <EventBanner
              v-for="event in upcomingEvents"
              :key="event.event_id"
              :host="event.host_name"
              :title="event.title"
              :description="event.description"
              :date="
                new Date(
                  event.start_time.replace(' ', 'T')
                ).toLocaleDateString()
              "
            />
          </div>
        </div>
      </main>

      <BottomNav class="md:hidden" />
    </div>
  </div>
</template>

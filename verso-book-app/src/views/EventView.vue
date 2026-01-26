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
import { CalendarDays, Plus, Sparkles } from "lucide-vue-next";

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
  <div class="flex h-screen bg-gradient-to-br from-verso-cream via-amber-50/30 to-blue-50/20">
    <Sidebar />

    <div class="flex-1 flex flex-col overflow-hidden relative">
      <Navbar />

      <main class="flex-1 overflow-x-hidden overflow-y-auto w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
          <!-- Header Section -->
          <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div class="flex items-center gap-3">
                <div class="p-3 bg-gradient-to-br from-verso-blue to-blue-600 rounded-2xl shadow-lg">
                  <CalendarDays class="w-8 h-8 text-white" />
                </div>
                <div>
                  <h1 class="text-3xl md:text-4xl font-bold text-verso-dark">
                    Events Calendar
                  </h1>
                  <p class="text-sm text-gray-600 mt-1">
                    Discover and join amazing book community events
                  </p>
                </div>
              </div>
              <button
                @click="router.push('/events/create')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-verso-blue to-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 group"
              >
                <Plus class="w-5 h-5 group-hover:rotate-90 transition-transform duration-200" />
                <span>Create Event</span>
              </button>
            </div>
          </div>

          <!-- Calendar Section -->
          <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
              <EventCalendar :events="events" />
            </div>
          </div>

          <!-- Upcoming Events Section -->
          <div class="mb-20">
            <div class="flex items-center gap-2 mb-6">
              <Sparkles class="w-5 h-5 text-verso-blue" />
              <h2 class="text-2xl font-bold text-verso-dark">
                Upcoming Events
              </h2>
              <span class="ml-2 px-3 py-1 bg-verso-blue/10 text-verso-blue text-sm font-semibold rounded-full">
                {{ upcomingEvents.length }}
              </span>
            </div>

            <div
              v-if="upcomingEvents.length === 0"
              class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center"
            >
              <div class="flex flex-col items-center gap-3">
                <div class="p-4 bg-gray-100 rounded-full">
                  <CalendarDays class="w-8 h-8 text-gray-400" />
                </div>
                <p class="text-gray-500 text-lg font-medium">
                  No upcoming events found
                </p>
                <p class="text-gray-400 text-sm">
                  Be the first to create an event for the community!
                </p>
              </div>
            </div>

            <div v-else class="grid gap-4">
              <EventBanner
                v-for="event in upcomingEvents"
                :key="event.event_id"
                :host="event.host_name"
                :title="event.title"
                :description="event.description"
                :category="event.category"
                :date="
                  new Date(
                    event.start_time.replace(' ', 'T')
                  ).toLocaleDateString()
                "
                :time="
                  new Date(
                    event.start_time.replace(' ', 'T')
                  ).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
                "
              />
            </div>
          </div>
        </div>
      </main>

      <BottomNav class="md:hidden" />
    </div>
  </div>
</template>

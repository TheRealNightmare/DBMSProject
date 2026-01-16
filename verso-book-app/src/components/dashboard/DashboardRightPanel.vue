<template>
  <aside
    class="w-80 bg-[#EFE8D8] h-screen p-6 flex flex-col border-l border-gray-200/50 sticky top-0 overflow-y-auto"
  >
    <div class="flex justify-between items-start mb-8">
      <h2 class="font-bold text-verso-dark text-lg">Profile</h2>
      <button class="text-gray-500 hover:text-verso-dark">
        <Edit2 class="w-4 h-4" />
      </button>
    </div>

    <div class="flex flex-col items-center mb-10 relative">
      <div
        class="w-24 h-24 rounded-full p-1 border-2 border-gray-300 relative mb-3"
      >
        <img
          :src="
            user.profile_image ||
            'https://i.pravatar.cc/150?u=a042581f4e29026024d'
          "
          class="w-full h-full rounded-full object-cover"
          alt="User Profile"
        />
        <div
          class="absolute top-0 right-0 w-full h-full border-t-4 border-r-4 border-verso-dark rounded-full rotate-45 pointer-events-none"
        ></div>
      </div>
      <h3 class="text-xl font-bold text-verso-dark">
        {{ user.username || "Reader" }}
      </h3>
      <p class="text-xs text-gray-500 font-medium">
        {{ user.bio || "Book Enthusiast" }}
      </p>

      <div
        class="absolute right-0 top-1/2 translate-x-4 w-1.5 h-1.5 bg-verso-dark rounded-full"
      ></div>
    </div>

    <div class="mb-10">
      <div class="flex justify-between items-center mb-4 px-2">
        <button
          @click="changeMonth(-1)"
          class="text-gray-400 hover:text-verso-dark"
        >
          <ChevronLeft class="w-4 h-4" />
        </button>
        <span class="font-bold text-sm text-verso-dark"
          >{{ currentMonthName }} {{ currentYear }}</span
        >
        <button
          @click="changeMonth(1)"
          class="text-gray-400 hover:text-verso-dark"
        >
          <ChevronRight class="w-4 h-4" />
        </button>
      </div>

      <div
        class="grid grid-cols-7 gap-y-3 text-center text-xs font-medium text-gray-400"
      >
        <div v-for="day in ['M', 'T', 'W', 'T', 'F', 'S', 'S']" :key="day">
          {{ day }}
        </div>

        <div
          v-for="n in startOfMonthOffset"
          :key="'empty-' + n"
          class="opacity-0"
        ></div>

        <div
          v-for="day in daysInMonth"
          :key="day"
          :class="{
            'bg-verso-dark text-white rounded-full w-6 h-6 flex items-center justify-center mx-auto shadow-md':
              isToday(day),
          }"
        >
          {{ day }}
        </div>
      </div>
    </div>

    <div class="flex-1">
      <h3 class="font-bold text-verso-dark mb-4 text-sm">Upcoming Events</h3>

      <div v-if="loading" class="text-xs text-gray-400 text-center">
        Loading events...
      </div>

      <div v-else class="space-y-6">
        <div
          v-for="event in upcomingEvents"
          :key="event.event_id"
          class="flex gap-3 group cursor-pointer"
        >
          <div class="mt-0.5">
            <div
              class="w-5 h-5 border-2 border-gray-400 rounded-lg group-hover:border-verso-blue transition flex items-center justify-center"
              :class="{ 'bg-verso-dark border-verso-dark': event.completed }"
              @click="toggleEvent(event)"
            >
              <Check v-if="event.completed" class="w-3 h-3 text-white" />
            </div>
          </div>
          <div @click="toggleEvent(event)">
            <p
              class="text-xs font-bold text-verso-dark leading-tight"
              :class="{ 'line-through opacity-60': event.completed }"
            >
              {{ event.title }}
            </p>
            <p class="text-[10px] text-gray-500 mt-1">
              <span class="font-medium text-verso-blue">{{
                event.category || "Event"
              }}</span>
              • {{ formatTime(event.start_time) }}
            </p>
          </div>
        </div>

        <div
          v-if="upcomingEvents.length === 0"
          class="text-xs text-gray-400 italic"
        >
          No upcoming events found.
        </div>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { Edit2, ChevronLeft, ChevronRight, Check } from "lucide-vue-next";
import api from "@/services/api";

// --- State ---
const user = ref({});
const events = ref([]);
const loading = ref(true);

// Calendar State
const today = new Date();
const currentCursor = ref(new Date()); // Tracks the month being viewed

// --- Computed Calendar Logic ---
const currentMonthName = computed(() => {
  return currentCursor.value.toLocaleString("default", { month: "short" });
});

const currentYear = computed(() => {
  return currentCursor.value.getFullYear();
});

const daysInMonth = computed(() => {
  const year = currentCursor.value.getFullYear();
  const month = currentCursor.value.getMonth();
  // Day 0 of next month is the last day of this month
  return new Date(year, month + 1, 0).getDate();
});

const startOfMonthOffset = computed(() => {
  const year = currentCursor.value.getFullYear();
  const month = currentCursor.value.getMonth();
  // getDay() returns 0 for Sunday, 1 for Monday.
  // We want Monday=0, Sunday=6 to match grid.
  let day = new Date(year, month, 1).getDay();
  return day === 0 ? 6 : day - 1;
});

// --- Actions ---
const changeMonth = (delta) => {
  const newDate = new Date(currentCursor.value);
  newDate.setMonth(newDate.getMonth() + delta);
  currentCursor.value = newDate;
};

const isToday = (day) => {
  return (
    day === today.getDate() &&
    currentCursor.value.getMonth() === today.getMonth() &&
    currentCursor.value.getFullYear() === today.getFullYear()
  );
};

const formatTime = (isoString) => {
  const date = new Date(isoString);
  return date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
};

const toggleEvent = (event) => {
  event.completed = !event.completed;
};

// Filter events for "Upcoming" (Future dates + Today)
const upcomingEvents = computed(() => {
  const now = new Date();
  now.setHours(0, 0, 0, 0); // Include events from start of today

  return events.value
    .filter((e) => new Date(e.start_time) >= now)
    .sort((a, b) => new Date(a.start_time) - new Date(b.start_time))
    .slice(0, 5); // Show top 5
});

// --- API Fetching ---
onMounted(async () => {
  try {
    // 1. Fetch Profile
    const profileRes = await api.get("/profile");
    user.value = profileRes.data;

    // 2. Fetch Events
    const eventsRes = await api.get("/events");
    // Add a local 'completed' state for UI toggling
    events.value = eventsRes.data.map((e) => ({ ...e, completed: false }));
  } catch (error) {
    console.error("Error loading right panel data:", error);
  } finally {
    loading.value = false;
  }
});
</script>

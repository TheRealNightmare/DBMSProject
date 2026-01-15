<template>
  <div class="w-full bg-[#EFE8D8]/30 rounded-lg">
    <div
      class="flex justify-between items-center px-4 py-3 border-b border-gray-400/30"
    >
      <button
        @click="changeMonth(-1)"
        class="p-1 hover:bg-black/5 rounded transition"
      >
        <ChevronLeft class="w-4 h-4 text-verso-dark" />
      </button>
      <span class="font-bold text-verso-dark text-lg"
        >{{ currentMonthName }} {{ currentYear }}</span
      >
      <button
        @click="changeMonth(1)"
        class="p-1 hover:bg-black/5 rounded transition"
      >
        <ChevronRight class="w-4 h-4 text-verso-dark" />
      </button>
    </div>

    <div class="grid grid-cols-7 border-b border-gray-400/30">
      <div
        v-for="day in days"
        :key="day"
        class="py-2 px-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center"
      >
        {{ day }}
      </div>
    </div>

    <div class="grid grid-cols-7 auto-rows-fr bg-[#EFE8D8]">
      <div
        v-for="(cell, index) in calendarGrid"
        :key="index"
        class="min-h-[100px] border-b border-r border-gray-400/30 p-2 relative flex flex-col transition hover:bg-white/10 group"
        :class="[
          cell.event ? cell.event.bgColor : '',
          !cell.isCurrentMonth ? 'bg-gray-100/50 opacity-60' : '',
          'first:border-l border-gray-400/30',
        ]"
      >
        <div class="flex justify-between items-start">
          <span
            class="text-xs font-medium mb-2 w-6 h-6 flex items-center justify-center rounded-full transition-all"
            :class="[
              cell.isToday
                ? 'bg-verso-dark text-white font-bold shadow-md'
                : cell.isCurrentMonth
                ? 'text-verso-dark'
                : 'text-gray-400',
            ]"
          >
            {{ cell.date }}
          </span>
        </div>

        <div
          v-if="cell.event"
          class="mt-auto opacity-90 group-hover:opacity-100 transition"
        >
          <p class="font-bold text-[10px] text-verso-dark leading-tight mb-1">
            {{ cell.event.title }}
          </p>
          <p class="text-[9px] text-verso-dark/70 font-medium">
            {{ cell.event.sub }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { ChevronLeft, ChevronRight } from "lucide-vue-next";

const days = ["Sun", "Mon", "Tue", "Wed", "Thur", "Fri", "Sat"];
const monthNames = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

// State for current view
const dateContext = ref(new Date());

const currentMonthName = computed(
  () => monthNames[dateContext.value.getMonth()]
);
const currentYear = computed(() => dateContext.value.getFullYear());

// Mock Events Map (Day of month -> Event Data)
// Events will appear on these specific dates of *any* month to demonstrate functionality
const mockEvents = {
  1: { title: "Know your Writer", sub: "Brian Cox", bgColor: "bg-[#C08581]" },
  4: { title: "Genre-mania", sub: "Jerome K.Jerome", bgColor: "bg-[#DCC46B]" },
  6: {
    title: "Movie screening",
    sub: "Dianne Russell",
    bgColor: "bg-[#70A0A0]",
  },
  8: {
    title: "Play on.........",
    sub: "Daniel Defoe",
    bgColor: "bg-[#B27BCB]",
  },
  14: {
    title: "Know your Writer",
    sub: "Kurt Vonnegut",
    bgColor: "bg-[#C08581]",
  },
  18: { title: "Genre-mania", sub: "Danai Gurira", bgColor: "bg-[#DCC46B]" },
  20: {
    title: "Movie screening",
    sub: "Gwyneth Paltrow",
    bgColor: "bg-[#70A0A0]",
  },
  23: {
    title: "Play on.........",
    sub: "Jane Austen",
    bgColor: "bg-[#B27BCB]",
  },
  26: { title: "Genre-mania", sub: "Mark Twain", bgColor: "bg-[#DCC46B]" },
  29: {
    title: "Know your Writer",
    sub: "Renée Zellweger",
    bgColor: "bg-[#C08581]",
  },
};

const calendarGrid = computed(() => {
  const year = dateContext.value.getFullYear();
  const month = dateContext.value.getMonth();

  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);

  const startingDayOfWeek = firstDay.getDay(); // 0 (Sun) to 6 (Sat)
  const totalDays = lastDay.getDate();

  const prevMonthLastDay = new Date(year, month, 0).getDate();

  const grid = [];

  // Padding days (Previous Month)
  for (let i = 0; i < startingDayOfWeek; i++) {
    grid.push({
      date: prevMonthLastDay - startingDayOfWeek + 1 + i,
      isCurrentMonth: false,
      isToday: false,
      event: null,
    });
  }

  // Actual days (Current Month)
  const today = new Date();
  const isCurrentMonthReal =
    today.getMonth() === month && today.getFullYear() === year;

  for (let i = 1; i <= totalDays; i++) {
    grid.push({
      date: i,
      isCurrentMonth: true,
      isToday: isCurrentMonthReal && i === today.getDate(),
      event: mockEvents[i] || null,
    });
  }

  // Padding days (Next Month) to fill grid (assuming 6 rows max 42 cells or just fill last row)
  const remainingCells = 42 - grid.length; // Ensure constant grid size usually
  for (let i = 1; i <= remainingCells; i++) {
    grid.push({
      date: i,
      isCurrentMonth: false,
      isToday: false,
      event: null,
    });
  }

  return grid;
});

function changeMonth(offset) {
  const newDate = new Date(dateContext.value);
  newDate.setMonth(newDate.getMonth() + offset);
  dateContext.value = newDate;
}
</script>

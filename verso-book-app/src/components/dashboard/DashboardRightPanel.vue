<template>
  <aside
    class="w-80 bg-[#EFE8D8] h-screen p-6 flex flex-col border-l border-gray-200/50 sticky top-0 overflow-y-auto"
  >
    <div class="flex justify-between items-start mb-8">
      <h2 class="font-bold text-verso-dark text-lg">Profile</h2>
      <router-link to="/profile" class="text-gray-500 hover:text-verso-dark">
        <Edit2 class="w-4 h-4" />
      </router-link>
    </div>

    <div class="flex flex-col items-center mb-10 relative">
      <div
        class="w-24 h-24 rounded-full p-1 border-2 border-gray-300 relative mb-3"
      >
        <img
          :src="profileImageUrl"
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
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-verso-dark text-sm">Todo List</h3>
        <span
          class="text-[10px] bg-verso-dark/10 text-verso-dark px-2 py-0.5 rounded-full font-bold"
        >
          {{ todos.filter((t) => !t.completed).length }}
        </span>
      </div>

      <div class="flex gap-2 mb-6">
        <input
          v-model="newTodo"
          @keyup.enter="addTodo"
          type="text"
          placeholder="Add a new task..."
          class="flex-1 bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-xs text-verso-dark placeholder:text-gray-400 focus:outline-none focus:border-verso-blue focus:ring-1 focus:ring-verso-blue transition"
        />
        <button
          @click="addTodo"
          class="bg-verso-dark text-white p-1.5 rounded-lg hover:bg-opacity-90 transition active:scale-95"
        >
          <Plus class="w-4 h-4" />
        </button>
      </div>

      <div class="space-y-4">
        <div
          v-for="(todo, index) in todos"
          :key="todo.id"
          class="flex items-start gap-3 group"
        >
          <button
            @click="toggleTodo(index)"
            class="mt-0.5 w-4 h-4 border-2 rounded transition flex items-center justify-center flex-shrink-0"
            :class="
              todo.completed
                ? 'bg-verso-dark border-verso-dark'
                : 'border-gray-300 hover:border-verso-blue'
            "
          >
            <Check v-if="todo.completed" class="w-2.5 h-2.5 text-white" />
          </button>

          <div class="flex-1 min-w-0">
            <p
              class="text-xs font-bold text-verso-dark leading-tight break-words"
              :class="{ 'line-through opacity-50': todo.completed }"
            >
              {{ todo.text }}
            </p>
            <p v-if="todo.date" class="text-[10px] text-gray-400 mt-0.5">
              {{ todo.date }}
            </p>
          </div>

          <button
            @click="removeTodo(index)"
            class="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition p-0.5"
          >
            <Trash2 class="w-3.5 h-3.5" />
          </button>
        </div>

        <div
          v-if="todos.length === 0"
          class="text-xs text-gray-400 italic text-center py-4"
        >
          No tasks yet. Add one above!
        </div>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import {
  Edit2,
  ChevronLeft,
  ChevronRight,
  Check,
  Plus,
  Trash2,
} from "lucide-vue-next";
import api from "@/services/api";

// --- State ---
const user = ref({});
const loading = ref(true);

// Todo State
const newTodo = ref("");
const todos = ref([
  { id: 1, text: "Finish 'The Stranger'", completed: false, date: "Today" },
  { id: 2, text: "Return library books", completed: true, date: "Yesterday" },
]);

// Calendar State
const today = new Date();
const currentCursor = ref(new Date());

// [FIX 2] Computed Property for Image URL
const profileImageUrl = computed(() => {
  // If user has a profile image, prepend the storage URL
  if (user.value.profile_image) {
    return `http://localhost:8000/storage/${user.value.profile_image}`;
  }
  // Fallback to placeholder
  return "https://i.pravatar.cc/150?u=a042581f4e29026024d";
});

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
  return new Date(year, month + 1, 0).getDate();
});

const startOfMonthOffset = computed(() => {
  const year = currentCursor.value.getFullYear();
  const month = currentCursor.value.getMonth();
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

// Todo Actions
const addTodo = () => {
  if (!newTodo.value.trim()) return;

  todos.value.unshift({
    id: Date.now(),
    text: newTodo.value,
    completed: false,
    date: "Just now",
  });

  newTodo.value = "";
};

const removeTodo = (index) => {
  todos.value.splice(index, 1);
};

const toggleTodo = (index) => {
  todos.value[index].completed = !todos.value[index].completed;
};

// --- API Fetching ---
onMounted(async () => {
  try {
    // 1. Fetch Profile
    const profileRes = await api.get("/profile");
    user.value = profileRes.data;
  } catch (error) {
    console.error("Error loading profile:", error);
  } finally {
    loading.value = false;
  }
});
</script>

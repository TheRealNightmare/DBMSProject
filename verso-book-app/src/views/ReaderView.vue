<template>
  <div
    class="h-screen w-full bg-verso-cream text-verso-dark font-sans flex overflow-hidden"
  >
    <Sidebar />

    <div
      class="flex-1 flex flex-col relative md:ml-20 transition-all duration-300"
    >
      <header
        class="flex items-center justify-between px-12 py-6 shrink-0 z-20 h-24 border-b border-gray-200/50"
      >
        <button
          @click="$router.back()"
          class="flex items-center text-verso-dark font-bold hover:text-verso-blue transition"
        >
          <ChevronLeft class="w-5 h-5 mr-2" />
          <span class="text-lg line-clamp-1 max-w-[150px]">{{
            book?.title || "Loading..."
          }}</span>
        </button>

        <div class="text-center absolute left-1/2 -translate-x-1/2">
          <h2
            class="text-xl font-bold text-gray-500 uppercase tracking-widest mb-1 hidden md:block"
          >
            Reading
          </h2>
          <h1
            class="text-xl md:text-3xl font-extrabold text-verso-dark truncate max-w-[300px] md:max-w-md"
          >
            {{ book?.title || "..." }}
          </h1>
        </div>

        <div v-if="!showAnnotations" class="flex gap-4">
          <button
            @click="toggleSidebar"
            class="p-2 hover:bg-gray-200 rounded-lg transition text-verso-dark"
          >
            <StickyNote class="w-6 h-6" />
          </button>
        </div>
      </header>

      <div
        class="flex-1 flex items-center relative px-4 md:px-16 overflow-hidden"
      >
        <button
          class="p-2 text-verso-dark hover:text-verso-blue transition hidden md:block"
        >
          <ChevronLeft class="w-10 h-10" />
        </button>

        <div v-if="loading" class="flex-1 flex justify-center items-center">
          <div
            class="animate-spin rounded-full h-12 w-12 border-b-2 border-verso-blue"
          ></div>
        </div>

        <div
          v-else
          class="flex-1 h-full max-w-6xl mx-auto overflow-y-auto no-scrollbar px-8 py-8"
        >
          <div
            class="text-justify text-verso-dark leading-loose text-lg font-serif h-full"
          >
            <div v-if="book && book.description">
              <p class="mb-8 whitespace-pre-line">{{ book.description }}</p>
              <p class="mb-8 text-gray-400 italic text-center text-sm">
                (Full book content not available in preview)
              </p>
            </div>
            <div v-else class="text-center text-gray-500 mt-20">
              Content not available.
            </div>
          </div>
        </div>

        <button
          class="p-2 text-verso-dark hover:text-verso-blue transition hidden md:block"
        >
          <ChevronRight class="w-10 h-10" />
        </button>
      </div>

      <footer
        class="h-16 flex items-center justify-center font-extrabold text-verso-dark text-lg"
      >
        1/1
      </footer>
    </div>

    <aside
      class="w-80 bg-[#CFE2F3] flex flex-col transition-all duration-300 relative border-l border-white/20 shrink-0"
      :class="{ 'mr-0': showAnnotations, '-mr-80': !showAnnotations }"
    >
      <div class="flex justify-between items-center px-8 py-8">
        <button
          @click="toggleSidebar"
          class="p-2 bg-verso-dark text-white rounded-lg transition shadow-md"
        >
          <StickyNote class="w-6 h-6" />
        </button>
        <button
          class="p-2 hover:bg-white/20 rounded-lg transition text-verso-dark"
        >
          <Bookmark class="w-6 h-6" />
        </button>
        <button
          class="p-2 hover:bg-white/20 rounded-lg transition text-verso-dark"
        >
          <Edit3 class="w-6 h-6" />
        </button>
      </div>

      <div class="flex-1 overflow-y-auto px-6">
        <h3 class="font-extrabold text-verso-dark mb-8 text-center text-lg">
          Annotations
        </h3>

        <div class="space-y-8">
          <div
            v-for="(note, i) in annotations"
            :key="i"
            class="group cursor-pointer"
          >
            <h4 class="font-bold text-sm text-verso-dark leading-snug">
              {{ note.chapter }}
            </h4>
            <p v-if="note.text" class="text-xs text-verso-dark/70 mt-1">
              {{ note.text }}
            </p>
          </div>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import bookService from "@/services/bookService";
import Sidebar from "@/components/layout/Sidebar.vue";
import {
  ChevronLeft,
  ChevronRight,
  StickyNote,
  Bookmark,
  Edit3,
} from "lucide-vue-next";

const route = useRoute();
const showAnnotations = ref(true);
const loading = ref(true);
const book = ref(null);

const toggleSidebar = () => {
  showAnnotations.value = !showAnnotations.value;
};

// Mock Annotations (Keep these for UI demo until backend supports them)
const annotations = ref([
  { chapter: "Note 1", text: "Interesting concept here." },
  { chapter: "Note 2", text: "Reference to earlier chapter." },
]);

onMounted(async () => {
  try {
    loading.value = true;
    const bookId = route.params.id;
    if (bookId) {
      book.value = await bookService.getBookDetails(bookId);
    }
  } catch (e) {
    console.error("Failed to load book content", e);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

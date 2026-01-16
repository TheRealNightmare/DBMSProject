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
            bookTitle || "Loading..."
          }}</span>
        </button>

        <div class="text-center absolute left-1/2 -translate-x-1/2">
          <h2
            class="text-xl font-bold text-gray-500 uppercase tracking-widest mb-1 hidden md:block"
          >
            {{ currentChapter?.title || "Reading" }}
          </h2>
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
          @click="prevChapter"
          :disabled="pageIndex <= 1"
          class="p-2 text-verso-dark hover:text-verso-blue transition hidden md:block disabled:opacity-30 disabled:cursor-not-allowed"
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
          class="flex-1 h-full max-w-4xl mx-auto overflow-y-auto px-8 py-8 bg-white/50 rounded-lg shadow-inner relative"
          id="reader-container"
        >
          <div
            class="text-justify text-verso-dark leading-loose text-lg font-serif h-full whitespace-pre-line selection:bg-yellow-200 selection:text-black"
          >
            {{ currentChapter?.content }}
          </div>
        </div>

        <button
          @click="nextChapter"
          :disabled="pageIndex >= totalChapters"
          class="p-2 text-verso-dark hover:text-verso-blue transition hidden md:block disabled:opacity-30 disabled:cursor-not-allowed"
        >
          <ChevronRight class="w-10 h-10" />
        </button>
      </div>

      <footer
        class="h-16 flex items-center justify-center font-extrabold text-verso-dark text-lg"
      >
        {{ pageIndex }} / {{ totalChapters }}
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
        <h3 class="font-extrabold text-verso-dark text-lg">Notes</h3>
      </div>

      <div class="px-6 mb-6">
        <textarea
          v-model="newNote"
          placeholder="Select text on the left, then type here to save a note..."
          class="w-full p-3 rounded-lg text-sm border-none focus:ring-2 focus:ring-verso-blue"
          rows="3"
        ></textarea>
        <button
          @click="saveNote"
          :disabled="!newNote"
          class="w-full mt-2 bg-verso-blue text-white py-2 rounded-lg text-sm font-bold hover:opacity-90 transition"
        >
          Save Note
        </button>
      </div>

      <div class="flex-1 overflow-y-auto px-6 pb-6">
        <div class="space-y-4">
          <div
            v-for="(note, i) in annotations"
            :key="i"
            class="bg-white/60 p-4 rounded-lg shadow-sm"
          >
            <p
              v-if="note.highlighted_text"
              class="text-xs text-gray-500 italic mb-2 border-l-2 border-yellow-400 pl-2"
            >
              "{{ note.highlighted_text }}"
            </p>
            <p class="text-sm text-verso-dark font-medium">{{ note.note }}</p>
            <span class="text-[10px] text-gray-400 mt-2 block">{{
              new Date(note.created_at).toLocaleDateString()
            }}</span>
          </div>
          <div
            v-if="annotations.length === 0"
            class="text-center text-gray-500 text-sm mt-10"
          >
            No notes for this chapter yet.
          </div>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import bookService from "@/services/bookService";
import Sidebar from "@/components/layout/Sidebar.vue";
import { ChevronLeft, ChevronRight, StickyNote } from "lucide-vue-next";

const route = useRoute();
const showAnnotations = ref(true);
const loading = ref(false);

// State
const bookTitle = ref("");
const currentChapter = ref(null);
const annotations = ref([]);
const pageIndex = ref(1);
const totalChapters = ref(1);
const newNote = ref("");
const bookId = route.params.id;

const toggleSidebar = () => {
  showAnnotations.value = !showAnnotations.value;
};

// Fetch Content
const loadContent = async () => {
  if (!bookId) return;
  loading.value = true;
  try {
    // API call matches the backend structure we created
    const data = await bookService.getChapterContent(bookId, pageIndex.value);
    currentChapter.value = data.chapter;
    bookTitle.value = data.chapter.title; // Or fetch book details separately
    annotations.value = data.annotations;
    totalChapters.value = data.total_chapters;
  } catch (e) {
    console.error("Error loading chapter", e);
  } finally {
    loading.value = false;
  }
};

// Pagination
const nextChapter = () => {
  if (pageIndex.value < totalChapters.value) {
    pageIndex.value++;
    loadContent();
  }
};

const prevChapter = () => {
  if (pageIndex.value > 1) {
    pageIndex.value--;
    loadContent();
  }
};

// Annotation Logic
const saveNote = async () => {
  if (!newNote.value) return;

  // Get selected text from the browser window
  const selection = window.getSelection().toString();

  try {
    const saved = await bookService.saveAnnotation(bookId, {
      chapter_id: currentChapter.value.chapter_id,
      note: newNote.value,
      highlighted_text: selection || null,
      color: "yellow",
    });

    // Add to local list immediately
    annotations.value.unshift(saved);
    newNote.value = ""; // Clear input
  } catch (e) {
    alert("Failed to save note");
  }
};

onMounted(() => {
  loadContent();
});
</script>

<style scoped>
/* Hides scrollbar for clean reading UI */
::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-track {
  background: transparent;
}
::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>

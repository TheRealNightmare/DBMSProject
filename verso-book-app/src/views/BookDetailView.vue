<template>
  <div class="min-h-screen bg-verso-cream font-sans flex flex-col">
    <Sidebar />

    <div
      class="md:ml-20 flex flex-col min-h-screen transition-all duration-300 relative"
    >
      <Navbar />

      <main class="flex-1 max-w-6xl mx-auto w-full px-8 py-8">
        <button
          @click="goBack"
          class="flex items-center text-verso-dark font-medium text-lg hover:text-verso-blue transition mb-8"
        >
          <ChevronLeft class="w-6 h-6 mr-1" />
          Back
        </button>

        <div v-if="loading" class="flex justify-center py-20">
          <div
            class="animate-spin rounded-full h-10 w-10 border-b-2 border-verso-blue"
          ></div>
        </div>

        <div
          v-else-if="book"
          class="flex flex-col md:flex-row gap-12 items-start"
        >
          <div class="w-full md:w-80 flex-shrink-0 relative">
            <div
              class="rounded-lg shadow-2xl overflow-hidden bg-gray-200 aspect-[2/3]"
            >
              <img
                :src="
                  book.image || 'https://placehold.co/300x450?text=No+Cover'
                "
                :alt="book.title"
                class="w-full h-full object-cover block"
              />
            </div>
            <div class="absolute top-10 -left-1 bg-blue-500 w-1 h-20"></div>
          </div>

          <div class="flex-1 flex flex-col w-full pt-2">
            <h1
              class="text-3xl font-bold text-verso-dark mb-2 font-serif tracking-wide"
            >
              {{ book.title }}
            </h1>

            <div class="flex items-center gap-2 mb-8">
              <span class="font-bold text-verso-dark text-lg">{{
                book.rating || 0
              }}</span>
              <div class="flex items-center gap-1">
                <Star
                  v-for="i in 5"
                  :key="i"
                  :class="[
                    'w-4 h-4',
                    i <= Math.round(book.rating)
                      ? 'fill-yellow-400 text-yellow-400'
                      : 'text-gray-400',
                  ]"
                />
              </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
              <div>
                <p class="text-gray-500 mb-1">Author</p>
                <p class="text-verso-dark font-bold italic">
                  {{ book.authors?.[0]?.name || book.author || "Unknown" }}
                </p>
              </div>
              <div v-if="book.category">
                <p class="text-gray-500 mb-1">Genre</p>
                <p class="text-verso-dark font-bold italic">
                  {{ book.category }}
                </p>
              </div>
            </div>

            <div class="w-full h-px bg-gray-300 mb-8"></div>

            <div class="flex items-center gap-6 mb-10">
              <button
                @click="startReading"
                class="bg-verso-blue text-white px-8 py-3 rounded-lg font-medium flex items-center gap-3 hover:opacity-90 transition shadow-md"
              >
                <BookOpen class="w-5 h-5" />
                Read
              </button>

              <button class="text-gray-500 hover:text-verso-blue transition">
                <Bookmark class="w-6 h-6" />
              </button>
              <button class="text-gray-500 hover:text-red-500 transition">
                <Heart class="w-6 h-6" />
              </button>
              <button class="text-gray-500 hover:text-verso-blue transition">
                <Share2 class="w-6 h-6" />
              </button>
            </div>

            <div
              class="mb-10 text-verso-dark font-medium leading-relaxed text-sm text-justify whitespace-pre-line"
            >
              {{ book.description }}
            </div>
          </div>
        </div>

        <div v-else class="text-center py-20 text-gray-500">
          Book not found.
        </div>
      </main>
    </div>
    <Footer class="relative z-50" />
    <div class="md:hidden">
      <BottomNav active="home" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import bookService from "@/services/bookService";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import Footer from "@/components/layout/Footer.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import {
  ChevronLeft,
  Star,
  BookOpen,
  Bookmark,
  Heart,
  Share2,
} from "lucide-vue-next";

const route = useRoute();
const router = useRouter();
const book = ref(null);
const loading = ref(true);

const goBack = () => {
  if (window.history.state && window.history.state.back) {
    router.back();
  } else {
    router.push("/");
  }
};

const startReading = () => {
  if (book.value) {
    // Assuming backend will eventually provide a 'content_path' or similar ID for the reader
    router.push(`/read/${book.value.id}`);
  }
};

onMounted(async () => {
  const bookId = route.params.id;
  try {
    loading.value = true;
    const fetchedBook = await bookService.getBookDetails(bookId);

    // Assign directly. No more mock data merging.
    if (fetchedBook) {
      book.value = fetchedBook;
    }
  } catch (e) {
    console.error("Failed to load book", e);
  } finally {
    loading.value = false;
  }
});
</script>

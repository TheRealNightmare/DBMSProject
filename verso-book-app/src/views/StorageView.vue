<template>
  <div class="min-h-screen bg-verso-cream font-sans flex flex-col">
    <Sidebar />

    <div
      class="md:ml-20 flex flex-col min-h-screen transition-all duration-300 relative"
    >
      <Navbar />

      <main class="flex-1 max-w-7xl mx-auto w-full px-12 py-8">
        <h1
          class="text-lg font-bold text-verso-dark uppercase mb-8 tracking-wide"
        >
          STORAGE
        </h1>

        <div v-if="loading" class="flex justify-center py-20">
          <div
            class="animate-spin rounded-full h-10 w-10 border-b-2 border-verso-blue"
          ></div>
        </div>

        <div
          v-else
          class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8"
        >
          <div
            v-for="book in storageBooks"
            :key="book.id"
            class="group cursor-pointer flex flex-col"
            @click="goToBook(book.id)"
          >
            <div
              class="aspect-[2/3] w-full rounded-lg shadow-md overflow-hidden mb-3 relative bg-gray-200"
            >
              <img
                :src="book.image"
                :alt="book.title"
                class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
              />
              <div
                class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition duration-300"
              ></div>
            </div>

            <h3
              class="font-bold text-verso-dark text-sm leading-tight mb-1 uppercase line-clamp-2"
            >
              {{ book.title }}
            </h3>

            <p class="text-xs text-verso-dark font-bold italic truncate">
              {{ book.author }}
            </p>
          </div>
        </div>

        <div
          v-if="!loading && storageBooks.length === 0"
          class="text-center py-20 text-gray-500"
        >
          Your storage is empty.
        </div>
      </main>
    </div>
    <Footer class="relative z-50" />
    <div class="md:hidden">
      <BottomNav active="storage" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import bookService from "@/services/bookService";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import Footer from "@/components/layout/Footer.vue";
import BottomNav from "@/components/layout/BottomNav.vue";

const router = useRouter();
const loading = ref(true);
const storageBooks = ref([]);

const goToBook = (id) => {
  router.push(`/book/${id}`);
};

onMounted(async () => {
  try {
    loading.value = true;
    // NOTE: Fetching 'business' or 'favorite' type books to simulate a saved library
    // Update "business" to whatever category you want to show here, or create a 'saved' endpoint later.
    storageBooks.value = await bookService.getBooks("business", 10);
  } catch (e) {
    console.error("Failed to load storage books", e);
  } finally {
    loading.value = false;
  }
});
</script>

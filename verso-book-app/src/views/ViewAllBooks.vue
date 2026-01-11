<template>
  <div class="min-h-screen bg-verso-cream font-sans flex flex-col">
    <Sidebar />

    <div
      class="md:ml-20 flex flex-col min-h-screen transition-all duration-300 relative"
    >
      <Navbar />

      <main class="flex-1 max-w-7xl mx-auto w-full px-8 py-8">
        <div class="flex items-center gap-4 mb-8">
          <button
            @click="$router.back()"
            class="p-2 hover:bg-gray-200 rounded-full transition text-verso-dark"
          >
            <ArrowLeft class="w-6 h-6" />
          </button>
          <h1
            class="text-2xl font-bold text-verso-dark uppercase tracking-wide"
          >
            {{ formatTitle(category) }}
          </h1>
        </div>

        <div v-if="loading" class="flex justify-center py-20">
          <div
            class="animate-spin rounded-full h-10 w-10 border-b-2 border-verso-blue"
          ></div>
        </div>

        <div
          v-else
          class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6"
        >
          <BookCard
            v-for="book in books"
            :key="book.id"
            v-bind="book"
            @click="goToBook(book.id)"
          />
        </div>

        <div
          v-if="!loading && books.length === 0"
          class="text-center py-20 text-gray-500"
        >
          No books found in this category.
        </div>
      </main>

      <Footer />
    </div>

    <div class="md:hidden">
      <BottomNav active="home" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import bookService from "@/services/bookService";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import Footer from "@/components/layout/Footer.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import BookCard from "@/components/book/BookCard.vue";
import { ArrowLeft } from "lucide-vue-next";

const route = useRoute();
const router = useRouter();
const loading = ref(true);
const books = ref([]);
const category = computed(() => route.params.category);

const formatTitle = (slug) => {
  if (!slug) return "Books";
  // Convert "highly-rated" to "Highly Rated"
  return slug
    .split("-")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(" ");
};

const goToBook = (id) => router.push(`/book/${id}`);

onMounted(async () => {
  loading.value = true;
  try {
    // Map URL friendly categories back to API query types if needed
    // This mapping aligns with what we used in HomeView
    const categoryMap = {
      latest: "latest",
      recommended: "classic",
      exclusive: "exclusive",
      "highly-rated": "rated",
      favorite: "business",
    };

    const queryType = categoryMap[category.value] || category.value;

    // Fetch a larger number of books for the "View All" page (e.g., 20)
    books.value = await bookService.getBooks(queryType, 20);
  } catch (e) {
    console.error("Failed to load books for category", category.value);
  } finally {
    loading.value = false;
  }
});
</script>

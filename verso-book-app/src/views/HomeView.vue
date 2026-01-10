<template>
  <div class="min-h-screen bg-verso-cream">
    <Navbar />

    <div class="md:hidden">
      <TopSearch />
    </div>

    <main
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24 md:pb-8 space-y-10"
    >
      <div v-if="loading" class="flex justify-center py-20">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-verso-blue"
        ></div>
      </div>

      <div v-else>
        <section>
          <SectionHeader title="Trending Now" :hasLink="true" />
          <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            <BookCard
              v-for="book in trendingBooks"
              :key="book.id"
              v-bind="book"
              layout="vertical"
              @click="goToBook(book.id)"
            />
          </div>
        </section>

        <section class="mt-8">
          <SectionHeader title="Timeless Classics" :hasLink="true" />
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <BookCard
              v-for="book in classicBooks"
              :key="book.id"
              v-bind="book"
              layout="horizontal"
              @click="goToBook(book.id)"
            />
          </div>
        </section>

        <section class="mt-8">
          <SectionHeader title="Popular Romance" :hasLink="true" />
          <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <BookCard
              v-for="book in romanceBooks"
              :key="book.id"
              v-bind="book"
              layout="vertical"
              @click="goToBook(book.id)"
            />
          </div>
        </section>
      </div>
    </main>

    <div class="md:hidden">
      <BottomNav active="home" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import bookService from "@/services/bookService";
import Navbar from "@/components/layout/Navbar.vue";
import TopSearch from "@/components/layout/TopSearch.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import SectionHeader from "@/components/book/SectionHeader.vue";
import BookCard from "@/components/book/BookCard.vue";

const router = useRouter();
const loading = ref(true);
const trendingBooks = ref([]);
const classicBooks = ref([]);
const romanceBooks = ref([]);

const goToBook = (id) => router.push(`/book/${id}`);

onMounted(async () => {
  try {
    // Parallel API requests for better performance
    const [trending, classics, romance] = await Promise.all([
      bookService.getBooks("trending", 5),
      bookService.getBooks("classic literature", 6),
      bookService.getBooks("romance", 6),
    ]);

    trendingBooks.value = trending;
    classicBooks.value = classics;
    romanceBooks.value = romance;
  } catch (e) {
    console.error("Failed to load books");
  } finally {
    loading.value = false;
  }
});
</script>

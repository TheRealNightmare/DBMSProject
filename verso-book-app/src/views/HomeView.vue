<template>
  <div class="min-h-screen bg-verso-cream pb-20 md:pb-0">
    <Navbar />

    <div class="md:hidden">
      <TopSearch />
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
      <div v-if="loading" class="flex justify-center py-20">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-verso-blue"
        ></div>
      </div>

      <div v-else class="space-y-8">
        <section>
          <SectionHeader title="LATESTS" :hasLink="true" />
          <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <BookCard
              v-for="book in latestBooks"
              :key="book.id"
              v-bind="book"
              layout="vertical"
              @click="goToBook(book.id)"
            />
          </div>
        </section>

        <section>
          <SectionHeader title="RECOMMENDED" :hasLink="false" />
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <BookCard
              v-for="book in recommendedBooks"
              :key="book.id"
              v-bind="book"
              layout="horizontal"
              @click="goToBook(book.id)"
            />
          </div>
        </section>

        <section>
          <SectionHeader title="EXCLUSIVE BOOKS" :hasLink="true" />
          <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <BookCard
              v-for="book in exclusiveBooks"
              :key="book.id"
              v-bind="book"
              layout="vertical"
              @click="goToBook(book.id)"
            />
          </div>
        </section>

        <section>
          <SectionHeader title="HIGHLY RATED BOOKS" :hasLink="true" />
          <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <BookCard
              v-for="book in highlyRatedBooks"
              :key="book.id"
              v-bind="book"
              layout="vertical"
              @click="goToBook(book.id)"
            />
          </div>
        </section>

        <section>
          <SectionHeader title="FAVORITE BOOKS" :hasLink="true" />
          <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <BookCard
              v-for="book in favoriteBooks"
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
const latestBooks = ref([]);
const recommendedBooks = ref([]);
const exclusiveBooks = ref([]);
const highlyRatedBooks = ref([]);
const favoriteBooks = ref([]);

const goToBook = (id) => router.push(`/book/${id}`);

onMounted(async () => {
  try {
    // Simulating the specific sections from the PDF
    const [latest, recommended, exclusive, highlyRated, favorites] =
      await Promise.all([
        bookService.getBooks("latest", 5),
        bookService.getBooks("recommended", 3),
        bookService.getBooks("exclusive", 6),
        bookService.getBooks("rated", 5),
        bookService.getBooks("favorite", 5),
      ]);

    latestBooks.value = latest;
    recommendedBooks.value = recommended;
    exclusiveBooks.value = exclusive;
    highlyRatedBooks.value = highlyRated;
    favoriteBooks.value = favorites;
  } catch (e) {
    console.error("Failed to load books");
  } finally {
    loading.value = false;
  }
});
</script>

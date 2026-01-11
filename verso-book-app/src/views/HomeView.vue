<template>
  <div class="min-h-screen bg-verso-cream font-sans">
    <Sidebar />

    <div
      class="md:ml-20 flex flex-col min-h-screen transition-all duration-300"
    >
      <Navbar />

      <main class="flex-1 px-8 py-4 space-y-10">
        <div v-if="loading" class="flex justify-center py-20">
          <div
            class="animate-spin rounded-full h-8 w-8 border-b-2 border-verso-blue"
          ></div>
        </div>

        <div v-else class="space-y-10">
          <section>
            <SectionHeader
              title="LATESTS"
              :hasLink="true"
              @viewAll="viewAll('latest')"
            />
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
              <BookCard
                v-for="book in latestBooks"
                :key="book.id"
                v-bind="book"
                @click="goToBook(book.id)"
              />
            </div>
          </section>

          <section>
            <SectionHeader
              title="RECOMMENDED BOOKS"
              :hasLink="true"
              @viewAll="viewAll('recommended')"
            />
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
              <BookCard
                v-for="book in recommendedBooks"
                :key="book.id"
                v-bind="book"
                @click="goToBook(book.id)"
              />
            </div>
          </section>

          <section>
            <SectionHeader
              title="EXCLUSIVE BOOKS"
              :hasLink="true"
              @viewAll="viewAll('exclusive')"
            />
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
              <BookCard
                v-for="book in exclusiveBooks.slice(0, 2)"
                :key="book.id"
                v-bind="book"
                @click="goToBook(book.id)"
              />
            </div>
          </section>

          <section>
            <SectionHeader
              title="HIGHLY RATED BOOKS"
              :hasLink="true"
              @viewAll="viewAll('highly-rated')"
            />
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
              <BookCard
                v-for="book in highlyRatedBooks.slice(0, 4)"
                :key="book.id"
                v-bind="book"
                @click="goToBook(book.id)"
              />
            </div>
          </section>

          <section>
            <SectionHeader
              title="FAVORITE BOOKS"
              :hasLink="true"
              @viewAll="viewAll('favorite')"
            />
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
              <BookCard
                v-for="book in favoriteBooks"
                :key="book.id"
                v-bind="book"
                @click="goToBook(book.id)"
              />
            </div>
          </section>
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
import { useRouter } from "vue-router";
import bookService from "@/services/bookService";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import Footer from "@/components/layout/Footer.vue";
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

// Navigation handler for View All
const viewAll = (category) => {
  router.push(`/category/${category}`);
};

onMounted(async () => {
  try {
    const [latest, recommended, exclusive, highlyRated, favorites] =
      await Promise.all([
        bookService.getBooks("latest", 6),
        bookService.getBooks("classic", 3),
        bookService.getBooks("exclusive", 2),
        bookService.getBooks("rated", 4),
        bookService.getBooks("business", 5),
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

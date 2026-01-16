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
          HISTORY
        </h1>

        <div v-if="loading" class="flex justify-center py-20">
          <div
            class="animate-spin rounded-full h-10 w-10 border-b-2 border-verso-blue"
          ></div>
        </div>

        <div v-else class="space-y-10">
          <div
            v-for="book in historyBooks"
            :key="book.id"
            class="flex flex-col md:flex-row gap-8 items-start relative group"
          >
            <div
              class="w-32 md:w-40 flex-shrink-0 shadow-lg rounded-md overflow-hidden bg-gray-200 aspect-[2/3]"
            >
              <img
                :src="book.image"
                :alt="book.title"
                class="w-full h-full object-cover"
              />
            </div>

            <div class="flex-1 min-w-0 pt-1">
              <h2
                class="text-xl font-extrabold text-verso-dark uppercase mb-1 tracking-tight"
              >
                {{ book.title }}
              </h2>

              <div class="flex items-center gap-2 mb-6">
                <span class="font-bold text-verso-dark text-lg">{{
                  book.rating
                }}</span>
                <div class="flex items-center gap-1 text-yellow-400">
                  <Star
                    v-for="i in 5"
                    :key="i"
                    :class="[
                      'w-4 h-4',
                      i <= Math.round(book.rating)
                        ? 'fill-current'
                        : 'text-gray-300 fill-none',
                    ]"
                  />
                </div>
              </div>

              <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6 max-w-3xl">
                <div>
                  <p class="text-xs text-gray-500 mb-1">Author</p>
                  <p class="text-verso-dark font-bold text-sm italic">
                    {{ book.author }}
                  </p>
                </div>
                <div>
                  <p class="text-xs text-gray-500 mb-1">Genre</p>
                  <p class="text-verso-dark font-bold text-sm italic">
                    {{ book.category || "General" }}
                  </p>
                </div>
                <div>
                  <p class="text-xs text-gray-500 mb-1">Status</p>
                  <p class="text-verso-dark font-bold text-sm italic">
                    Completed
                  </p>
                </div>
              </div>

              <button
                @click="startReading(book.id)"
                class="bg-verso-blue text-white px-10 py-2 rounded-md font-medium text-sm hover:opacity-90 transition shadow-sm mb-6"
              >
                Read Again
              </button>

              <p class="text-sm font-bold text-verso-dark italic">
                {{ book.statusLabel }}:
                <span class="text-gray-600">{{ book.date }}</span>
              </p>
            </div>

            <button
              class="absolute top-0 right-0 md:relative md:top-auto md:right-auto text-verso-dark hover:text-red-500 transition p-2"
            >
              <X class="w-8 h-8" />
            </button>
          </div>
        </div>

        <div
          v-if="!loading && historyBooks.length === 0"
          class="text-center py-20 text-gray-500"
        >
          No reading history found.
        </div>
      </main>
    </div>
    <Footer class="relative z-50" />
    <div class="md:hidden">
      <BottomNav active="history" />
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
import { Star, X } from "lucide-vue-next";

const router = useRouter();
const loading = ref(true);
const historyBooks = ref([]);

const startReading = (id) => {
  router.push(`/read/${id}`);
};

onMounted(async () => {
  try {
    loading.value = true;
    // NOTE: Since the backend doesn't have a specific 'history' endpoint yet,
    // we fetch the latest books to simulate the history view.
    const books = await bookService.getBooks("latest", 5);

    // Map API data to the History UI specific fields
    historyBooks.value = books.map((book) => ({
      ...book,
      statusLabel: "Read", // Mock status until backend supports user tracking
      date:
        new Date().toLocaleDateString() +
        " " +
        new Date().toLocaleTimeString([], {
          hour: "2-digit",
          minute: "2-digit",
        }),
    }));
  } catch (e) {
    console.error("Failed to load history", e);
  } finally {
    loading.value = false;
  }
});
</script>

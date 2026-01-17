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

            <div class="mb-6">
              <h3 class="text-gray-500 text-sm uppercase tracking-wider mb-3">
                Authors
              </h3>
              <div class="flex flex-col gap-3">
                <div
                  v-for="author in book.authors"
                  :key="author.id"
                  class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm border border-gray-100"
                >
                  <div class="flex items-center gap-3">
                    <img
                      :src="
                        author.image ||
                        'https://placehold.co/40?text=' + author.name.charAt(0)
                      "
                      class="w-10 h-10 rounded-full object-cover"
                    />
                    <span class="text-verso-dark font-bold">{{
                      author.name
                    }}</span>
                  </div>
                  <button
                    @click="handleFollow(author)"
                    :class="[
                      'px-4 py-1.5 text-xs font-semibold rounded-full transition border',
                      author.is_following
                        ? 'bg-gray-100 text-gray-600 border-gray-300 hover:bg-gray-200'
                        : 'bg-verso-blue text-white border-transparent hover:opacity-90',
                    ]"
                  >
                    {{ author.is_following ? "Following" : "Follow" }}
                  </button>
                </div>
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
            </div>

            <div
              class="mb-10 text-verso-dark font-medium leading-relaxed text-sm text-justify whitespace-pre-line"
            >
              {{ book.description }}
            </div>
          </div>
        </div>
      </main>
    </div>
    <Footer class="relative z-50" />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import bookService from "@/services/bookService";
import authorService from "@/services/authorService";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import Footer from "@/components/layout/Footer.vue";
import { ChevronLeft, Star, BookOpen } from "lucide-vue-next";

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
    router.push(`/read/${book.value.id}`);
  }
};

const handleFollow = async (author) => {
  try {
    const response = await authorService.toggleFollow(author.id);
    // Update local state immediately
    author.is_following = response.is_following;
  } catch (e) {
    alert("Please login to follow authors.");
  }
};

onMounted(async () => {
  const bookId = route.params.id;
  try {
    loading.value = true;
    const fetchedBook = await bookService.getBookDetails(bookId);
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

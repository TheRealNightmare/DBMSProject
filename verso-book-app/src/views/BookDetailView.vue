<template>
  <div class="min-h-screen bg-verso-cream font-sans flex flex-col">
    <Sidebar />

    <div
      class="md:ml-20 flex flex-col min-h-screen transition-all duration-300 relative"
    >
      <Navbar />

      <main class="flex-1 max-w-7xl mx-auto w-full px-4 md:px-8 py-8">
        <button
          @click="goBack"
          class="flex items-center text-gray-600 hover:text-verso-blue font-medium text-sm transition mb-6 group"
        >
          <ChevronLeft class="w-5 h-5 mr-1 group-hover:-translate-x-1 transition-transform" />
          Back
        </button>

        <div v-if="loading" class="flex justify-center py-20">
          <div
            class="animate-spin rounded-full h-12 w-12 border-4 border-verso-blue border-t-transparent"
          ></div>
        </div>

        <div
          v-else-if="book"
          class="grid grid-cols-1 lg:grid-cols-3 gap-8"
        >
          <!-- Left Column - Book Cover -->
          <div class="lg:col-span-1">
            <div class="sticky top-24">
              <div
                class="rounded-2xl shadow-2xl overflow-hidden bg-white aspect-[2/3] border-4 border-white group hover:shadow-3xl transition-shadow duration-300"
              >
                <img
                  :src="
                    book.image || 'https://placehold.co/400x600?text=No+Cover'
                  "
                  :alt="book.title"
                  class="w-full h-full object-cover block group-hover:scale-105 transition-transform duration-500"
                />
              </div>
              
              <!-- Read Button (Prominent) -->
              <button
                @click="startReading"
                class="w-full mt-6 bg-verso-blue text-white px-6 py-4 rounded-xl font-bold text-lg flex items-center justify-center gap-3 hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0"
              >
                <BookOpen class="w-6 h-6" />
                Start Reading
              </button>
            </div>
          </div>

          <!-- Right Column - Book Details -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Title and Rating Card -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
              <h1
                class="text-3xl md:text-4xl font-bold text-verso-dark mb-4 font-serif leading-tight"
              >
                {{ book.title }}
              </h1>

              <!-- Rating Display -->
              <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="flex items-center gap-2">
                  <span class="font-bold text-verso-dark text-2xl">{{
                    book.rating || 0
                  }}</span>
                  <div class="flex items-center gap-0.5">
                    <Star
                      v-for="i in 5"
                      :key="'display-' + i"
                      :class="[
                        'w-5 h-5',
                        i <= Math.round(book.rating)
                          ? 'fill-yellow-400 text-yellow-400'
                          : 'fill-gray-200 text-gray-200',
                      ]"
                    />
                  </div>
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <span class="text-gray-600 text-sm">{{ book.rating_count || 0 }} {{ book.rating_count === 1 ? 'Review' : 'Reviews' }}</span>
              </div>

              <!-- User Rating Section -->
              <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-100">
                <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                  <Star class="w-4 h-4 text-verso-blue" />
                  Rate this book
                </h3>
                <div class="flex items-center gap-3">
                  <button
                    v-for="i in 5"
                    :key="'rate-' + i"
                    @click="rateBook(i)"
                    class="transition-all duration-200 hover:scale-125 active:scale-110"
                  >
                    <Star
                      :class="[
                        'w-8 h-8 cursor-pointer transition-colors',
                        i <= (hoveredRating || userRating || 0)
                          ? 'fill-yellow-400 text-yellow-400'
                          : 'fill-gray-200 text-gray-300 hover:text-gray-400',
                      ]"
                      @mouseenter="hoveredRating = i"
                      @mouseleave="hoveredRating = 0"
                    />
                  </button>
                  <span v-if="userRating" class="text-sm font-medium text-verso-blue ml-2 bg-white px-3 py-1 rounded-full">Your rating: {{ userRating }}/5</span>
                </div>
              </div>

              <!-- Category Badge -->
              <div v-if="book.category" class="mt-4">
                <span class="inline-block bg-verso-blue/10 text-verso-blue px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider">
                  {{ book.category }}
                </span>
              </div>
            </div>

            <!-- Authors Card -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
              <h3 class="text-lg font-bold text-verso-dark mb-4 flex items-center gap-2">
                <User class="w-5 h-5 text-verso-blue" />
                Authors
              </h3>
              <div class="space-y-3">
                <div
                  v-for="author in book.authors"
                  :key="author.id"
                  class="flex items-center justify-between bg-gradient-to-r from-gray-50 to-transparent p-4 rounded-xl hover:from-blue-50 transition-all group border border-transparent hover:border-blue-100"
                >
                  <div class="flex items-center gap-4">
                    <div class="relative">
                      <img
                        :src="
                          author.image ||
                          'https://placehold.co/50?text=' + author.name.charAt(0)
                        "
                        class="w-12 h-12 rounded-full object-cover ring-2 ring-white shadow-md"
                      />
                      <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div>
                      <span class="text-verso-dark font-bold text-base block">{{
                        author.name
                      }}</span>
                      <span class="text-xs text-gray-500">Author</span>
                    </div>
                  </div>
                  <button
                    @click="handleFollow(author)"
                    :class="[
                      'px-5 py-2 text-sm font-semibold rounded-full transition-all border-2 shadow-sm hover:shadow-md',
                      author.is_following
                        ? 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'
                        : 'bg-verso-blue text-white border-verso-blue hover:bg-blue-700',
                    ]"
                  >
                    {{ author.is_following ? "Following" : "Follow" }}
                  </button>
                </div>
              </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
              <h3 class="text-lg font-bold text-verso-dark mb-4 flex items-center gap-2">
                <BookText class="w-5 h-5 text-verso-blue" />
                About This Book
              </h3>
              <div
                class="text-gray-700 leading-relaxed text-base whitespace-pre-line"
              >
                {{ book.description }}
              </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
              <h2 class="text-2xl font-bold text-verso-dark mb-6 flex items-center gap-2">
                <MessageCircle class="w-6 h-6 text-verso-blue" />
                Comments <span class="text-lg font-normal text-gray-500">({{ comments.length }})</span>
              </h2>
              
              <!-- Add Comment Form -->
              <div class="mb-8 bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100">
                <textarea
                  v-model="newComment"
                  placeholder="Share your thoughts about this book..."
                  rows="4"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-verso-blue/30 focus:border-verso-blue resize-none transition-all bg-white"
                ></textarea>
                <div class="flex justify-between items-center mt-4">
                  <span class="text-xs text-gray-500">{{ newComment.length }}/1000 characters</span>
                  <button
                    @click="submitComment"
                    :disabled="!newComment.trim()"
                    class="bg-verso-blue text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md flex items-center gap-2"
                  >
                    <Send class="w-4 h-4" />
                    Post Comment
                  </button>
                </div>
              </div>

              <!-- Comments List -->
              <div class="space-y-4">
                <div
                  v-for="comment in comments"
                  :key="comment.comment_id"
                  class="bg-gray-50 hover:bg-gray-100 p-5 rounded-xl transition-all border border-gray-100"
                >
                  <div class="flex items-start gap-4">
                    <img
                      :src="getAvatarUrl(comment.user.profile_image)"
                      class="w-12 h-12 rounded-full object-cover bg-gray-200 ring-2 ring-white shadow-md flex-shrink-0"
                    />
                    <div class="flex-1 min-w-0">
                      <div class="flex justify-between items-start mb-2 gap-2">
                        <h4 class="font-bold text-verso-dark">{{ comment.user.username }}</h4>
                        <span class="text-xs text-gray-500 whitespace-nowrap">{{ comment.created_at }}</span>
                      </div>
                      <p class="text-gray-700 text-sm leading-relaxed break-words">{{ comment.comment }}</p>
                    </div>
                  </div>
                </div>

                <div v-if="comments.length === 0" class="text-center py-12">
                  <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <MessageCircle class="w-8 h-8 text-gray-300" />
                  </div>
                  <p class="text-gray-400 text-sm">No comments yet. Be the first to share your thoughts!</p>
                </div>
              </div>
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
import api from "@/services/api";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import Footer from "@/components/layout/Footer.vue";
import { ChevronLeft, Star, BookOpen, User, BookText, MessageCircle, Send } from "lucide-vue-next";

const route = useRoute();
const router = useRouter();
const book = ref(null);
const loading = ref(true);
const comments = ref([]);
const newComment = ref("");
const userRating = ref(0);
const hoveredRating = ref(0);

const getAvatarUrl = (path) => {
  if (!path) return "https://placehold.co/100x100";
  if (path.startsWith("http")) return path;
  return `http://localhost:8000/storage/${path}`;
};

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

const rateBook = async (rating) => {
  try {
    const response = await api.post(`/books/${book.value.id}/rate`, { rating });
    userRating.value = rating;
    book.value.rating = response.data.avg_rating;
    book.value.rating_count = (book.value.rating_count || 0) + (userRating.value === rating ? 0 : 1);
  } catch (error) {
    console.error("Failed to rate book:", error);
    alert("Failed to submit rating. Please try again.");
  }
};

const loadComments = async () => {
  try {
    const response = await api.get(`/books/${route.params.id}/comments`);
    comments.value = response.data;
  } catch (error) {
    console.error("Failed to load comments:", error);
  }
};

const submitComment = async () => {
  if (!newComment.value.trim()) return;

  try {
    const response = await api.post(`/books/${book.value.id}/comments`, {
      comment: newComment.value
    });
    comments.value.unshift(response.data.comment);
    newComment.value = "";
  } catch (error) {
    console.error("Failed to submit comment:", error);
    alert("Failed to post comment. Please try again.");
  }
};

onMounted(async () => {
  const bookId = route.params.id;
  try {
    loading.value = true;
    const fetchedBook = await bookService.getBookDetails(bookId);
    if (fetchedBook) {
      book.value = fetchedBook;
      userRating.value = fetchedBook.user_rating || 0;
    }
    await loadComments();
  } catch (e) {
    console.error("Failed to load book", e);
  } finally {
    loading.value = false;
  }
});
</script>

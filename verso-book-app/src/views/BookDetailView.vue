<template>
  <div class="min-h-screen bg-verso-cream pb-20">
    <div v-if="loading" class="h-screen flex items-center justify-center">
      <div
        class="animate-spin rounded-full h-12 w-12 border-b-2 border-verso-blue"
      ></div>
    </div>

    <div v-else-if="book" class="relative">
      <button
        @click="$router.back()"
        class="absolute top-4 left-4 z-10 bg-white/80 p-2 rounded-full shadow-md hover:bg-white"
      >
        ← Back
      </button>

      <div class="bg-white shadow-sm pb-8">
        <div class="h-48 bg-verso-blue/20 w-full relative"></div>
        <div class="px-6 -mt-16 flex flex-col md:flex-row gap-6 items-start">
          <img
            :src="book.image"
            class="w-32 h-48 object-cover rounded-lg shadow-lg bg-gray-200"
          />
          <div class="flex-1 mt-2 md:mt-16">
            <h1 class="text-2xl font-bold text-verso-dark font-serif">
              {{ book.title }}
            </h1>
            <p class="text-gray-500 font-medium">By Open Library Authors</p>
            <div class="flex gap-1 text-yellow-400 text-sm mt-2">
              <span>★★★★☆</span>
              <span class="text-gray-400 ml-2">(128 Reviews)</span>
            </div>
          </div>
        </div>
      </div>

      <div class="px-6 py-6 max-w-4xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm">
          <h2 class="font-bold text-lg mb-4 text-verso-dark">Synopsis</h2>
          <p class="text-gray-600 leading-relaxed text-sm md:text-base">
            {{ book.description || "No description available for this title." }}
          </p>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="bg-white p-4 rounded-xl shadow-sm text-center">
            <p class="text-xs text-gray-400 uppercase">Status</p>
            <p class="font-bold text-verso-blue">Available</p>
          </div>
          <div class="bg-white p-4 rounded-xl shadow-sm text-center">
            <p class="text-xs text-gray-400 uppercase">Language</p>
            <p class="font-bold text-verso-blue">English</p>
          </div>
        </div>
      </div>

      <div
        class="fixed bottom-0 w-full bg-white border-t p-4 flex gap-4 max-w-7xl mx-auto left-0 right-0"
      >
        <button
          class="flex-1 py-3 border-2 border-verso-blue text-verso-blue font-bold rounded-xl"
        >
          Add to Library
        </button>
        <button
          @click="$router.push(`/read/${book.id}`)"
          class="flex-[2] py-3 bg-verso-blue text-white font-bold rounded-xl shadow-lg hover:bg-opacity-90"
        >
          Read Now
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import bookService from "@/services/bookService";

const route = useRoute();
const book = ref(null);
const loading = ref(true);

onMounted(async () => {
  book.value = await bookService.getBookDetails(route.params.id);
  loading.value = false;
});
</script>

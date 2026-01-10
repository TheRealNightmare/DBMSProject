<template>
  <div class="min-h-screen bg-verso-cream pb-24 md:pb-12">
    <div v-if="loading" class="h-screen flex items-center justify-center">
      <div
        class="animate-spin rounded-full h-12 w-12 border-b-2 border-verso-blue"
      ></div>
    </div>

    <div v-else-if="book" class="relative">
      <button
        @click="$router.back()"
        class="absolute top-4 left-4 z-20 bg-white/80 p-2 rounded-full shadow-sm hover:bg-white transition"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6 text-gray-600"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M10 19l-7-7m0 0l7-7m-7 7h18"
          />
        </svg>
      </button>

      <div class="bg-white shadow-sm pb-6">
        <div class="h-48 bg-verso-blue/20 w-full relative"></div>

        <div
          class="px-4 md:px-8 -mt-20 flex flex-col md:flex-row gap-6 items-start"
        >
          <img
            :src="book.image"
            class="w-36 h-52 object-cover rounded-lg shadow-lg bg-gray-200 shrink-0"
          />
          <div class="flex-1 mt-2 md:mt-24 w-full">
            <h1
              class="text-2xl font-bold text-verso-dark font-serif leading-tight"
            >
              {{ book.title }}
            </h1>
            <p class="text-lg text-gray-700 mt-1 font-serif italic">
              {{ book.subtitle || "Sức mạnh của việc biết mình không biết" }}
            </p>

            <div class="flex items-center gap-2 mt-3">
              <span class="text-yellow-400 text-lg">★★★★☆</span>
              <span class="text-gray-400 text-sm">• 1 Review</span>
            </div>
          </div>
        </div>
      </div>

      <div class="px-4 md:px-8 py-6 max-w-5xl mx-auto space-y-8">
        <div
          class="grid grid-cols-4 divide-x divide-gray-200 border border-gray-200 rounded-xl bg-white overflow-hidden text-center shadow-sm"
        >
          <div class="p-3">
            <p class="text-xs text-gray-500 uppercase font-bold mb-1">Author</p>
            <p class="text-sm font-medium text-verso-dark truncate">Lorem</p>
          </div>
          <div class="p-3">
            <p class="text-xs text-gray-500 uppercase font-bold mb-1">Genre</p>
            <p class="text-sm font-medium text-verso-dark truncate">Romance</p>
          </div>
          <div class="p-3">
            <p class="text-xs text-gray-500 uppercase font-bold mb-1">
              Producer
            </p>
            <p class="text-sm font-medium text-verso-dark truncate">Updating</p>
          </div>
          <div class="p-3">
            <p class="text-xs text-gray-500 uppercase font-bold mb-1">
              Release status
            </p>
            <p class="text-sm font-medium text-verso-dark truncate">25/50</p>
          </div>
        </div>

        <div>
          <h2 class="font-bold text-lg mb-3 text-verso-dark">Read</h2>
          <p
            class="text-gray-600 leading-relaxed text-sm md:text-base text-justify"
          >
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
            ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
            aliquip ex ea commodo consequat. Duis aute irure dolor in
            reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
            pariatur.
          </p>
        </div>

        <div>
          <h2 class="font-bold text-lg mb-4 text-verso-dark">Comment (1)</h2>
          <div
            class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex gap-4"
          >
            <div
              class="w-10 h-10 rounded-full bg-verso-blue/20 flex items-center justify-center text-verso-blue font-bold shrink-0"
            >
              H
            </div>
            <div class="flex-1">
              <div class="flex justify-between items-start">
                <h3 class="font-bold text-verso-dark text-sm">
                  Harleen Quinzel
                </h3>
                <span class="text-xs text-gray-400">11:22 PM</span>
              </div>
              <p class="text-gray-600 text-sm mt-1">AMAZING!!!</p>
            </div>
          </div>
        </div>
      </div>

      <div
        class="fixed bottom-0 w-full bg-white border-t p-4 left-0 right-0 z-10"
      >
        <div class="max-w-7xl mx-auto">
          <button
            @click="$router.push(`/read/${book.id}`)"
            class="w-full py-4 bg-verso-blue text-white font-bold rounded-xl shadow-lg hover:bg-opacity-90 text-lg"
          >
            Read Now
          </button>
        </div>
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

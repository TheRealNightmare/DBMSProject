<template>
  <div class="min-h-screen bg-verso-cream">
    <Navbar />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24 md:pb-8">
      <h1 class="text-3xl font-bold text-verso-dark mb-8">My Library</h1>

      <div class="flex border-b border-gray-200 mb-8">
        <button
          v-for="tab in ['History', 'Storage', 'Favorite']"
          :key="tab"
          @click="activeTab = tab"
          class="pb-4 px-6 text-sm font-medium transition relative outline-none"
          :class="
            activeTab === tab
              ? 'text-verso-blue'
              : 'text-gray-400 hover:text-gray-600'
          "
        >
          <span class="uppercase tracking-wider">{{ tab }}</span>
          <span
            v-if="activeTab === tab"
            class="absolute bottom-0 left-0 w-full h-0.5 bg-verso-blue"
          ></span>
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="book in filteredBooks"
          :key="book.id"
          class="bg-white p-4 rounded-xl shadow-sm flex gap-4 relative group hover:shadow-md transition"
        >
          <button
            class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition"
          >
            ✕
          </button>

          <img
            :src="book.image"
            class="w-24 h-36 object-cover rounded-lg shadow-md bg-gray-200"
          />

          <div class="flex-1 flex flex-col justify-center">
            <h3 class="font-bold text-verso-dark text-lg leading-tight mb-1">
              {{ book.title }}
            </h3>
            <p class="text-sm text-gray-500 mb-2">{{ book.author }}</p>

            <div class="flex text-yellow-400 text-xs mb-3">
              <span v-for="i in 5">★</span>
            </div>

            <div class="mt-auto flex items-center justify-between">
              <span
                class="text-xs font-medium px-2 py-1 rounded bg-gray-100 text-gray-600"
              >
                {{
                  activeTab === "History"
                    ? "Read: " + book.date
                    : "Status: " + book.status
                }}
              </span>
              <button class="text-sm text-verso-blue font-bold hover:underline">
                Read
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="filteredBooks.length === 0" class="text-center py-20">
        <p class="text-gray-400 text-lg">No books found in {{ activeTab }}</p>
      </div>
    </main>

    <div class="md:hidden">
      <BottomNav active="history" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import Navbar from "@/components/layout/Navbar.vue";
import BottomNav from "@/components/layout/BottomNav.vue";

const activeTab = ref("History");

// Data matching PDF Page 8 & 9
const books = ref([
  {
    id: 1,
    title: "Danh Nhan Vat Ly",
    author: "Nguyen Truong",
    image: "https://placehold.co/150x220",
    category: "History",
    date: "8/16/13 06:13 PM",
    status: "Completed",
  },
  {
    id: 2,
    title: "The Sun Also Rises",
    author: "Ernest Hemingway",
    image: "https://placehold.co/150x220",
    category: "Storage",
    status: "25/50",
  },
  {
    id: 3,
    title: "Think Again",
    author: "Adam Grant",
    image: "https://placehold.co/150x220",
    category: "Favorite",
    status: "Reading",
  },
  {
    id: 4,
    title: "Danh Nhan Van Hoc",
    author: "Nguyen Truong",
    image: "https://placehold.co/150x220",
    category: "Storage",
    status: "Updating",
  },
  {
    id: 5,
    title: "Lorem Ipsum",
    author: "Lorem",
    image: "https://placehold.co/150x220",
    category: "History",
    date: "8/16/13 06:13 PM",
    status: "25/50",
  },
]);

const filteredBooks = computed(() => {
  return books.value.filter((b) => b.category === activeTab.value);
});
</script>

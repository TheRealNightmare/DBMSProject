<template>
  <div class="min-h-screen bg-verso-cream pb-20 md:pb-0">
    <Navbar />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div
        class="flex border-b border-gray-200 mb-6 overflow-x-auto no-scrollbar"
      >
        <button
          v-for="tab in ['HISTORY', 'STORAGE', 'FAVORITE']"
          :key="tab"
          @click="activeTab = tab"
          class="pb-3 px-6 text-sm font-bold transition relative outline-none whitespace-nowrap"
          :class="
            activeTab === tab
              ? 'text-verso-blue'
              : 'text-gray-400 hover:text-gray-600'
          "
        >
          {{ tab }}
          <span
            v-if="activeTab === tab"
            class="absolute bottom-0 left-0 w-full h-0.5 bg-verso-blue"
          ></span>
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="book in filteredBooks"
          :key="book.id"
          class="bg-white p-3 rounded-xl shadow-sm flex gap-4 relative group hover:shadow-md transition border border-gray-100"
        >
          <button
            class="absolute top-2 right-2 text-gray-300 hover:text-red-500 font-bold p-1"
          >
            ✕
          </button>

          <img
            :src="book.image"
            class="w-24 h-36 object-cover rounded-md bg-gray-200 shrink-0"
          />

          <div class="flex-1 flex flex-col min-w-0">
            <h3
              class="font-bold text-verso-dark text-base leading-tight mb-1 line-clamp-2"
            >
              {{ book.title }}
            </h3>

            <div
              class="text-xs text-gray-500 grid grid-cols-2 gap-y-1 gap-x-2 mt-1 mb-2"
            >
              <div>
                Author <br /><span class="text-verso-dark font-medium">{{
                  book.author
                }}</span>
              </div>
              <div>
                Genre <br /><span class="text-verso-dark font-medium"
                  >Romance</span
                >
              </div>
              <div>
                Producer <br /><span class="text-verso-dark font-medium"
                  >Updating</span
                >
              </div>
              <div>
                Status <br /><span class="text-verso-dark font-medium"
                  >25/50</span
                >
              </div>
            </div>

            <div
              class="mt-auto flex items-center justify-between border-t border-dashed border-gray-200 pt-2"
            >
              <div class="flex text-yellow-400 text-xs">★★★★☆</div>
              <button class="text-sm text-verso-blue font-bold uppercase">
                Read
              </button>
            </div>
          </div>
        </div>
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

const activeTab = ref("HISTORY");

// Mock Data matching the PDF listing
const books = ref([
  {
    id: 1,
    title: "Danh Nhan Vat Ly",
    author: "Nguyen Truong",
    image: "https://placehold.co/150x220",
    category: "HISTORY",
  },
  {
    id: 2,
    title: "Danh Nhan Van Hoc Nghe Thuat",
    author: "Nguyen Truong",
    image: "https://placehold.co/150x220",
    category: "STORAGE",
  },
  {
    id: 3,
    title: "The Sun Also Rises",
    author: "Ernest Hemingway",
    image: "https://placehold.co/150x220",
    category: "FAVORITE",
  },
  {
    id: 4,
    title: "Danh Nhan Vat Ly",
    author: "Lorem Ipsum",
    image: "https://placehold.co/150x220",
    category: "STORAGE",
  },
]);

const filteredBooks = computed(() => {
  return books.value.filter((b) => b.category === activeTab.value);
});
</script>

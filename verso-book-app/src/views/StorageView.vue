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
          STORAGE
        </h1>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
          <div
            v-for="book in storageBooks"
            :key="book.id"
            class="group cursor-pointer flex flex-col"
            @click="goToBook(book.id)"
          >
            <div
              class="aspect-[2/3] w-full rounded-lg shadow-md overflow-hidden mb-3 relative bg-gray-200"
            >
              <img
                :src="book.image"
                :alt="book.title"
                class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
              />
              <div
                class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition duration-300"
              ></div>
            </div>

            <h3
              class="font-bold text-verso-dark text-sm leading-tight mb-1 uppercase"
            >
              {{ book.title }}
            </h3>

            <p class="text-xs text-verso-dark font-bold italic">
              {{ book.author }}
            </p>
          </div>
        </div>
      </main>
    </div>
    <Footer class="relative z-50" />
    <div class="md:hidden">
      <BottomNav active="storage" />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import Footer from "@/components/layout/Footer.vue";
import BottomNav from "@/components/layout/BottomNav.vue";

const router = useRouter();

// Navigate to Book Detail Page
const goToBook = (id) => {
  router.push(`/book/${id}`);
};

// Mock Data with REAL IDs so the detail page loads successfully
const storageBooks = ref([
  {
    id: "OL17930368W", // Valid OpenLibrary Work ID
    title: "DAHN NHAN VAN HOC NGHE THUAT",
    author: "Lorem Ipsum",
    image: "https://covers.openlibrary.org/b/id/12556509-L.jpg",
  },
  {
    id: "OL8259443W", // Valid OpenLibrary Work ID
    title: "DANH NHAN VATLY",
    author: "Lorem Ipsum",
    image: "https://covers.openlibrary.org/b/id/8259443-L.jpg",
  },
]);
</script>

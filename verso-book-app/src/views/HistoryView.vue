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

        <div class="space-y-10">
          <div
            v-for="book in historyBooks"
            :key="book.id"
            class="flex flex-col md:flex-row gap-8 items-start relative group"
          >
            <div
              class="w-32 md:w-40 flex-shrink-0 shadow-lg rounded-md overflow-hidden"
            >
              <img
                :src="book.image"
                :alt="book.title"
                class="w-full h-auto object-cover aspect-[2/3]"
              />
            </div>

            <div class="flex-1 min-w-0 pt-1">
              <h2
                class="text-xl font-extrabold text-verso-dark uppercase mb-1 tracking-tight"
              >
                {{ book.title }}
              </h2>

              <div class="flex items-center gap-2 mb-6">
                <span class="font-bold text-verso-dark text-lg">4</span>
                <div class="flex items-center gap-1 text-yellow-400">
                  <Star v-for="i in 4" :key="i" class="w-4 h-4 fill-current" />
                  <Star class="w-4 h-4 text-gray-400" />
                </div>
                <span class="text-verso-dark font-bold text-sm italic ml-1"
                  >• 1 Review</span
                >
              </div>

              <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6 max-w-3xl">
                <div>
                  <p class="text-xs text-gray-500 mb-1">Author</p>
                  <p class="text-verso-dark font-bold text-sm italic">Lorem</p>
                </div>
                <div>
                  <p class="text-xs text-gray-500 mb-1">Genre</p>
                  <p class="text-verso-dark font-bold text-sm italic">
                    Romance
                  </p>
                </div>
                <div>
                  <p class="text-xs text-gray-500 mb-1">Producer</p>
                  <p class="text-verso-dark font-bold text-sm italic">
                    Updating
                  </p>
                </div>
                <div>
                  <p class="text-xs text-gray-500 mb-1">Release status</p>
                  <p class="text-verso-dark font-bold text-sm italic">25/50</p>
                </div>
              </div>

              <button
                @click="startReading(book.id)"
                class="bg-verso-blue text-white px-10 py-2 rounded-md font-medium text-sm hover:opacity-90 transition shadow-sm mb-6"
              >
                Read
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
      </main>

      <Footer />
    </div>

    <div class="md:hidden">
      <BottomNav active="history" />
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
import { Star, X } from "lucide-vue-next";

const router = useRouter();

// Function to handle navigation to the Reader view
const startReading = (id) => {
  router.push(`/read/${id}`);
};

// Mock Data matching the image exactly
const historyBooks = ref([
  {
    id: 1,
    title: "DANH NHAN VAT LY",
    image: "https://covers.openlibrary.org/b/id/8259443-L.jpg", // Einstein/Physics cover placeholder
    statusLabel: "Read",
    date: "8/16/13 06:13 PM",
  },
  {
    id: 2,
    title: "DANH NHAN VAN HOC NGHE THUAT",
    image: "https://covers.openlibrary.org/b/id/12556509-L.jpg", // Literature cover placeholder
    statusLabel: "Save",
    date: "8/16/13 06:13 PM",
  },
  {
    id: 3,
    title: "DANH NHAN VAT LY", // Matches text in image even though cover is Hemingway
    image: "https://covers.openlibrary.org/b/id/10603788-L.jpg", // The Sun Also Rises cover
    statusLabel: "Favourite",
    date: "8/16/13 06:13 PM",
  },
]);
</script>

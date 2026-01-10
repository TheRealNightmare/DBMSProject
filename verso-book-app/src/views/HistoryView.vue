<template>
  <div class="bg-gray-50 min-h-screen pb-24">
    <div class="bg-white p-4 shadow-sm sticky top-0 z-10">
      <h1 class="text-xl font-bold text-verso-dark text-center">My Library</h1>
      
      <div class="flex mt-4 border-b">
        <button 
          v-for="tab in ['History', 'Storage', 'Favorite']" 
          :key="tab"
          @click="activeTab = tab"
          class="flex-1 pb-2 text-sm font-medium transition relative"
          :class="activeTab === tab ? 'text-verso-blue' : 'text-gray-400'"
        >
          {{ tab }}
          <span v-if="activeTab === tab" class="absolute bottom-0 left-0 w-full h-0.5 bg-verso-blue"></span>
        </button>
      </div>
    </div>

    <div class="p-4 space-y-4">
      <div v-for="book in filteredBooks" :key="book.id" class="bg-white p-3 rounded-xl shadow-sm flex gap-3 relative">
        <button class="absolute top-2 right-2 text-gray-300 hover:text-red-500">✕</button>

        <img :src="book.image" class="w-20 h-28 object-cover rounded shadow-md bg-gray-200" />
        
        <div class="flex-1 flex flex-col justify-center">
          <div class="flex justify-between items-start">
             <div>
               <h3 class="font-bold text-verso-dark leading-tight">{{ book.title }}</h3>
               <p class="text-xs text-gray-500">{{ book.author }}</p>
             </div>
          </div>

          <div class="flex text-yellow-400 text-xs mt-1">
            <span v-for="i in 5">★</span>
          </div>

          <div class="mt-3 flex items-center justify-between">
            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">
              {{ activeTab === 'History' ? 'Read: ' + book.date : 'Status: ' + book.status }}
            </span>
            <button class="text-xs text-verso-blue font-bold">Read</button>
          </div>
        </div>
      </div>

      <div v-if="filteredBooks.length === 0" class="text-center text-gray-400 mt-10">
        No books found in {{ activeTab }}
      </div>
    </div>

    <BottomNav active="history" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import BottomNav from '@/components/layout/BottomNav.vue';

const activeTab = ref('History');

// Mock Data - In real app, this comes from your Laravel API
const books = ref([
  { id: 1, title: 'Danh Nhan Vat Ly', author: 'Nguyen Truong', image: 'https://placehold.co/100x150', category: 'History', date: '8/16/23', status: 'Completed' },
  { id: 2, title: 'The Sun Also Rises', author: 'Ernest Hemingway', image: 'https://placehold.co/100x150', category: 'Storage', status: '25/50' },
  { id: 3, title: 'Think Again', author: 'Adam Grant', image: 'https://placehold.co/100x150', category: 'Favorite', status: 'Reading' },
]);

const filteredBooks = computed(() => {
  return books.value.filter(b => b.category === activeTab.value);
});
</script>
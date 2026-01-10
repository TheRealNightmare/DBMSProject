<template>
  <div class="pb-24 bg-gray-50 min-h-screen">
    <TopSearch />

    <div class="mt-4">
      <SectionHeader title="Latest" :hasLink="true" />
      <div class="flex gap-4 overflow-x-auto px-4 pb-4 no-scrollbar">
        <BookCard 
          v-for="book in latestBooks" 
          :key="book.id" 
          v-bind="book" 
          layout="vertical"
          @click="goToBook(book.id)"
        />
      </div>
    </div>

    <div class="mt-4">
      <SectionHeader title="Exclusive Books" :hasLink="true" />
      <div class="px-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <BookCard 
          v-for="book in exclusiveBooks" 
          :key="book.id" 
          v-bind="book" 
          layout="horizontal"
          @click="goToBook(book.id)"
        />
      </div>
    </div>

    <BottomNav active="home" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import TopSearch from '@/components/layout/TopSearch.vue';
import BottomNav from '@/components/layout/BottomNav.vue';
import SectionHeader from '@/components/book/SectionHeader.vue';
import BookCard from '@/components/book/BookCard.vue';

const router = useRouter();

// Mock Data - In real app, fetch from Pinia Store
const latestBooks = ref([
  { id: 1, title: 'Think Again', author: 'Adam Grant', image: 'https://placehold.co/120x180' },
  { id: 2, title: 'Don Quixote', author: 'Cervantes', image: 'https://placehold.co/120x180' },
  { id: 3, title: 'The Sun Also Rises', author: 'Hemingway', image: 'https://placehold.co/120x180' },
]);

const exclusiveBooks = ref([
  { id: 4, title: 'Treasure Island', author: 'Stevenson', rating: 4, image: 'https://placehold.co/100x150' },
  { id: 5, title: 'Mrs. Dalloway', author: 'Virginia Woolf', rating: 5, image: 'https://placehold.co/100x150' },
]);

const goToBook = (id) => router.push(`/book/${id}`);
</script>
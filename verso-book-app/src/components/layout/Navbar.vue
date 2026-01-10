<template>
  <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <div
          class="flex-shrink-0 flex items-center cursor-pointer"
          @click="$router.push('/')"
        >
          <div class="w-8 h-8 bg-verso-blue rounded-lg mr-2"></div>
          <span class="font-bold text-xl text-verso-dark tracking-tight"
            >ZS_BOOKS</span
          >
        </div>

        <div class="hidden md:flex flex-1 max-w-lg mx-8">
          <div class="relative w-full">
            <div
              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
              <Search class="h-5 w-5 text-gray-400" />
            </div>
            <input
              type="text"
              class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-full leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-verso-blue sm:text-sm transition duration-150 ease-in-out"
              placeholder="Search book, name, author..."
            />
          </div>
        </div>

        <div class="hidden md:flex items-center space-x-8">
          <router-link
            to="/"
            class="text-gray-500 hover:text-verso-blue px-3 py-2 font-medium transition"
            active-class="text-verso-blue"
          >
            Home
          </router-link>
          <router-link
            to="/history"
            class="text-gray-500 hover:text-verso-blue px-3 py-2 font-medium transition"
            active-class="text-verso-blue"
          >
            My Library
          </router-link>
          <router-link
            to="/profile"
            class="flex items-center space-x-2 text-gray-700 hover:text-verso-blue"
          >
            <img
              src="https://placehold.co/100x100"
              class="h-8 w-8 rounded-full border border-gray-200"
              alt="Peter Parker"
            />
            <span class="font-medium text-sm">Peter Parker</span>
          </router-link>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Search } from "lucide-vue-next";
</script>

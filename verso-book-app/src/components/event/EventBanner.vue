<template>
  <div
    class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-verso-blue/30 transition-all duration-300 overflow-hidden"
  >
    <div class="p-6">
      <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <!-- Left Content -->
        <div class="flex-1">
          <div class="flex items-center gap-2 mb-3">
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 bg-verso-blue rounded-full animate-pulse"></div>
              <span class="text-xs font-semibold text-verso-blue uppercase tracking-wide">
                {{ category || 'General' }}
              </span>
            </div>
            <span class="text-gray-300">•</span>
            <div class="flex items-center gap-1.5 text-gray-500">
              <UserIcon class="w-3.5 h-3.5" />
              <span class="text-xs font-medium">{{ host }}</span>
            </div>
          </div>
          
          <h3 class="text-lg font-bold text-verso-dark mb-2 group-hover:text-verso-blue transition-colors duration-200">
            {{ title }}
          </h3>
          
          <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">
            {{ description }}
          </p>
        </div>

        <!-- Right Content -->
        <div class="flex sm:flex-col items-start gap-2 sm:text-right shrink-0">
          <div class="flex items-center gap-2 px-3 py-2 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-xl">
            <CalendarIcon class="w-4 h-4 text-verso-blue" />
            <span class="text-sm font-bold text-verso-blue whitespace-nowrap">
              {{ date }}
            </span>
          </div>
          <div v-if="time" class="flex items-center gap-2 px-3 py-2 bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-xl">
            <ClockIcon class="w-4 h-4 text-amber-600" />
            <span class="text-sm font-semibold text-amber-700 whitespace-nowrap">
              {{ time }}
            </span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Bottom gradient accent -->
    <div class="h-1 bg-gradient-to-r from-verso-blue via-blue-500 to-verso-blue opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
  </div>
</template>

<script setup>
import { Calendar as CalendarIcon, Clock as ClockIcon, User as UserIcon } from 'lucide-vue-next';

defineProps({
  host: String,
  title: String,
  description: String,
  date: String,
  time: String,
  category: String,
});
</script>

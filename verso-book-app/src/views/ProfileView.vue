<template>
  <div class="bg-gray-50 min-h-screen pb-24">
    <div class="bg-verso-blue h-32 relative mb-16">
      <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 text-center">
        <div class="relative">
          <img src="https://placehold.co/100x100" class="w-24 h-24 rounded-full border-4 border-white shadow-md mx-auto" />
          <button class="absolute bottom-0 right-0 bg-gray-800 text-white p-1 rounded-full text-xs">📷</button>
        </div>
        <h2 class="font-bold text-lg mt-2 text-verso-dark">Peter Parker</h2>
      </div>
    </div>

    <div class="px-6 space-y-4 mt-8">
      <BaseInput label="Email" modelValue="spoider.man@example.com" disabled class="bg-gray-100" />
      <BaseInput label="Full Name" modelValue="Peter Parker" />
      <BaseInput label="Date of Birth" modelValue="01/01/1990" type="date" />
      
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
        <select class="w-full rounded-xl border border-gray-300 shadow-sm p-3 bg-white">
          <option>Male</option>
          <option>Female</option>
          <option>Other</option>
        </select>
      </div>

      <button class="w-full bg-verso-blue text-white py-3 rounded-xl font-bold shadow-md mt-4">
        Confirm Changes
      </button>

      <button class="w-full border-2 border-red-400 text-red-500 py-3 rounded-xl font-bold mt-2">
        Log Out
      </button>
    </div>

    <BottomNav active="profile" />
  </div>
</template>

<script setup>
import BaseInput from '@/components/ui/BaseInput.vue';
import BottomNav from '@/components/layout/BottomNav.vue';
</script>
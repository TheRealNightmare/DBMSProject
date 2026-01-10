<template>
  <div class="min-h-screen bg-verso-cream">
    <Navbar />

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-12 pb-24">
      <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="h-48 bg-verso-blue w-full"></div>

        <div class="px-8 pb-8">
          <div class="relative flex justify-between items-end -mt-16 mb-8">
            <div class="flex items-end">
              <div class="relative">
                <img
                  src="https://placehold.co/150x150"
                  class="w-32 h-32 rounded-full border-4 border-white shadow-lg bg-gray-200"
                />
                <button
                  class="absolute bottom-1 right-1 bg-gray-800 text-white p-2 rounded-full shadow-md hover:bg-gray-700 transition"
                >
                  📷
                </button>
              </div>
              <div class="ml-6 mb-2">
                <h1 class="text-3xl font-bold text-verso-dark">Peter Parker</h1>
                <p class="text-gray-500">Member since 2025</p>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <BaseInput
              label="Email"
              modelValue="spoider.man@example.com"
              disabled
              class="bg-gray-50 text-gray-500"
            />
            <BaseInputZV label="Full Name" modelValue="Peter Parker" />
            <BaseInput
              label="Date of Birth"
              modelValue="1900-01-01"
              type="date"
            />

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1"
                >Gender</label
              >
              <select
                class="w-full rounded-xl border border-gray-300 shadow-sm p-3 bg-white focus:ring-2 focus:ring-verso-blue outline-none"
              >
                <option selected>Male</option>
                <option>Female</option>
                <option>Other</option>
              </select>
            </div>
          </div>

          <div class="mt-8 flex gap-4 border-t pt-8">
            <button
              class="flex-1 bg-verso-blue text-white py-3 rounded-xl font-bold shadow-md hover:bg-opacity-90 transition"
            >
              Confirm Changes
            </button>
            <button
              class="flex-1 border-2 border-red-100 text-red-500 py-3 rounded-xl font-bold hover:bg-red-50 transition"
            >
              Log Out
            </button>
          </div>
        </div>
      </div>
    </main>

    <div class="md:hidden">
      <BottomNav active="profile" />
    </div>
  </div>
</template>

<script setup>
import Navbar from "@/components/layout/Navbar.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import BaseInput from "@/components/ui/BaseInput.vue";
// Temporary fix for BaseInputZV typo in template if BaseInput is what we have
const BaseInputZV = BaseInput;
</script>

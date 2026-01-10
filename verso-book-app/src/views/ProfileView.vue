<template>
  <div class="min-h-screen bg-verso-cream pb-20 md:pb-0">
    <Navbar />

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-6">
      <div
        class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100"
      >
        <div class="h-32 bg-verso-blue w-full"></div>

        <div class="px-6 pb-8">
          <div class="relative flex justify-center -mt-16 mb-6">
            <div class="relative flex flex-col items-center">
              <div class="relative">
                <img
                  src="https://placehold.co/150x150"
                  class="w-32 h-32 rounded-full border-4 border-white shadow-md bg-gray-200 object-cover"
                />
              </div>
              <button
                class="text-verso-blue text-sm font-bold mt-2 hover:underline"
              >
                Change Photo
              </button>
            </div>
          </div>

          <div class="space-y-4 max-w-lg mx-auto">
            <BaseInput label="Email" modelValue="spoider.man@example.com" />
            <BaseInput
              label="Password"
              type="password"
              modelValue="000000000"
            />
            <BaseInput label="Fullname" modelValue="Peter Parker" />

            <div class="grid grid-cols-2 gap-4">
              <BaseInput label="date of birth" modelValue="01/01/1900" />
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-1 ml-1"
                  >Gender</label
                >
                <select
                  class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-700 outline-none focus:ring-2 focus:ring-verso-blue/20 focus:border-verso-blue transition"
                >
                  <option>Male</option>
                  <option>Female</option>
                </select>
              </div>
            </div>

            <div class="pt-6 flex flex-col gap-3">
              <button
                class="w-full bg-verso-blue text-white py-3 rounded-xl font-bold shadow-lg shadow-verso-blue/30 hover:opacity-90 transition"
              >
                Confirm
              </button>
              <button
                class="w-full text-red-500 py-3 rounded-xl font-bold hover:bg-red-50 transition"
              >
                log out
              </button>
            </div>
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
</script>

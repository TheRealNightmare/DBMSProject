<template>
  <div class="min-h-screen bg-verso-cream font-sans flex flex-col">
    <Sidebar />

    <div
      class="md:ml-20 flex flex-col min-h-screen transition-all duration-300 relative"
    >
      <Navbar />

      <main class="flex-1 flex items-center justify-center p-6 md:p-12">
        <div
          class="w-full max-w-5xl flex flex-col-reverse md:flex-row gap-16 md:gap-32 items-start justify-center mt-10"
        >
          <div class="flex-1 w-full max-w-md space-y-5">
            <div
              class="border-2 border-verso-blue/50 rounded-2xl px-5 py-3 bg-transparent"
            >
              <label class="block text-verso-dark font-extrabold text-sm mb-1"
                >Email</label
              >
              <input
                type="text"
                value="spoider.man@example.com"
                class="w-full bg-transparent border-none outline-none text-verso-dark font-medium p-0"
              />
            </div>

            <div
              class="border-2 border-verso-blue/50 rounded-2xl px-5 py-3 bg-transparent"
            >
              <label class="block text-verso-dark font-extrabold text-sm mb-1"
                >Password</label
              >
              <input
                type="password"
                value="000000000"
                class="w-full bg-transparent border-none outline-none text-verso-dark font-medium p-0"
              />
            </div>

            <div
              class="border-2 border-verso-blue/50 rounded-2xl px-5 py-3 bg-transparent"
            >
              <label class="block text-verso-dark font-extrabold text-sm mb-1"
                >Fullname</label
              >
              <input
                type="text"
                value="Peter Parker"
                class="w-full bg-transparent border-none outline-none text-verso-dark font-medium p-0"
              />
            </div>

            <div class="flex gap-4">
              <div
                class="flex-1 border-2 border-verso-blue/50 rounded-2xl px-5 py-3 bg-transparent flex justify-between items-center relative"
              >
                <div class="w-full">
                  <label
                    class="block text-verso-dark font-extrabold text-sm mb-1"
                    >date of birth</label
                  >
                  <input
                    type="text"
                    value="01/01/1900"
                    class="w-full bg-transparent border-none outline-none text-verso-dark font-medium p-0"
                  />
                </div>
                <Calendar class="w-6 h-6 text-verso-dark/70 shrink-0 ml-2" />
              </div>

              <div
                class="w-1/3 min-w-[120px] border-2 border-verso-blue/50 rounded-2xl px-5 py-3 bg-transparent flex justify-between items-center relative cursor-pointer"
              >
                <div class="w-full">
                  <label
                    class="block text-verso-dark font-extrabold text-sm mb-1"
                    >Gender</label
                  >
                  <div class="text-verso-dark font-medium">Male</div>
                </div>
                <ChevronDown class="w-6 h-6 text-verso-dark/70 shrink-0 ml-2" />
              </div>
            </div>

            <div class="pt-4">
              <button
                class="bg-verso-blue text-white font-bold py-3 px-10 rounded-xl shadow-md hover:opacity-90 transition"
              >
                Confirm
              </button>
            </div>
          </div>

          <div class="flex flex-col items-center gap-6">
            <div
              class="w-64 h-64 rounded-full overflow-hidden border-4 border-white shadow-lg relative bg-gray-200"
            >
              <img
                src="https://i.pinimg.com/736x/8f/c3/7b/8fc37b74b608a622588fbaa361485f32.jpg"
                alt="Spider Worm"
                class="w-full h-full object-cover"
                onerror="this.src='https://placehold.co/300x300?text=Avatar'"
              />
            </div>

            <button
              class="bg-verso-blue text-white font-bold py-2.5 px-8 rounded-lg shadow-md hover:opacity-90 transition min-w-[160px]"
            >
              Change Photo
            </button>

            <button
              class="bg-verso-blue text-white font-bold py-2.5 px-8 rounded-lg shadow-md hover:opacity-90 transition min-w-[160px]"
            >
              log out
            </button>
          </div>
        </div>
      </main>
    </div>
    <Footer class="relative z-50" />
    <div class="md:hidden">
      <BottomNav active="profile" />
    </div>
  </div>
</template>

<script setup>
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import Footer from "@/components/layout/Footer.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import { Calendar, ChevronDown } from "lucide-vue-next";
</script>

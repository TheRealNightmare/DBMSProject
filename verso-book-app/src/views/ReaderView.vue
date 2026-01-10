<template>
  <div
    class="h-screen w-full bg-verso-cream text-verso-dark font-sans flex overflow-hidden"
  >
    <Sidebar />

    <div
      class="flex-1 flex flex-col relative md:ml-20 transition-all duration-300"
    >
      <header
        class="flex items-center justify-between px-12 py-6 shrink-0 z-20 h-24"
      >
        <button
          @click="$router.back()"
          class="flex items-center text-verso-dark font-bold hover:text-verso-blue transition"
        >
          <ChevronLeft class="w-5 h-5 mr-2" />
          <span class="text-lg">Think Again</span>
        </button>

        <div class="text-center absolute left-1/2 -translate-x-1/2">
          <h2
            class="text-xl font-bold text-gray-500 uppercase tracking-widest mb-1"
          >
            Chapter 1
          </h2>
          <h1 class="text-3xl font-extrabold text-verso-dark">
            Lorem Ispsum Dolor
          </h1>
        </div>

        <div v-if="!showAnnotations" class="flex gap-4">
          <button
            @click="toggleSidebar"
            class="p-2 hover:bg-gray-200 rounded-lg transition text-verso-dark"
          >
            <StickyNote class="w-6 h-6" />
          </button>
          <button
            @click="toggleSidebar"
            class="p-2 hover:bg-gray-200 rounded-lg transition text-verso-dark"
          >
            <Bookmark class="w-6 h-6" />
          </button>
          <button
            @click="toggleSidebar"
            class="p-2 hover:bg-gray-200 rounded-lg transition text-verso-dark"
          >
            <Edit3 class="w-6 h-6" />
          </button>
        </div>
      </header>

      <div
        class="flex-1 flex items-center relative px-4 md:px-16 overflow-hidden"
      >
        <button
          class="p-2 text-verso-dark hover:text-verso-blue transition hidden md:block"
        >
          <ChevronLeft class="w-10 h-10" />
        </button>

        <div
          class="flex-1 h-full max-w-6xl mx-auto overflow-y-auto no-scrollbar px-8 py-4"
        >
          <div
            class="columns-1 md:columns-2 gap-20 text-justify text-verso-dark leading-loose text-sm font-semibold h-full"
          >
            <p class="mb-8">
              Sed ut perspiciatis unde omnis iste natus error sit voluptatem
              accusantium doloremque laudantium, totam rem aperiam, eaque ipsa
              quae ab illo inventore veritatis et quasi architecto beatae vitae
              dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas.
            </p>
            <p class="mb-8">
              Totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et
              quasi architecto beatae vitae dicta sunt explicabo. Nemo enim
              ipsam voluptatem quia voluptas.
            </p>
            <p class="mb-8">
              Sit aspernatur aut odit aut fugit, sed quia consequuntur magni
              dolores eos qui ratione voluptatem sequi nesciunt. Neque porro
              quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur,
              adipisci velit, sed quia non numquam eius modi tempora incidunt ut
              labore et.
            </p>
            <p class="mb-8">
              Dolore magnam aliquam quaerat voluptatem. Ut enim ad minima
              veniam, quis nostrum exercitationem ullam corporis suscipit
              laboriosam, nisi ut aliquid ex ea.
            </p>
            <p class="mb-8">
              Commodo consequatur? Quis autem vel eum iure reprehenderit qui in
              ea voluptate velit esse quam nihil molestiae consequatur, vel
              illum qui dolorem eum.
            </p>
            <p class="mb-8">Fugiat quo voluptas nulla pariatur?</p>
            <p class="mb-8">
              Sed ut perspiciatis unde omnis iste natus error sit voluptatem
              accusantium doloremque laudantium, totam rem aperiam, eaque ipsa
              quae ab illo inventore veritatis et quasi architecto beatae vitae
              dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas.
            </p>
            <p class="mb-8">
              Totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et
              quasi architecto beatae vitae dicta sunt explicabo. Nemo enim
              ipsam voluptatem quia voluptas.
            </p>
            <p class="mb-8">
              Sit aspernatur aut odit aut fugit, sed quia consequuntur magni
              dolores eos qui ratione voluptatem sequi nesciunt. Neque porro
              quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur,
              adipisci velit, sed quia non numquam eius modi tempora incidunt ut
              labore et.
            </p>
            <p class="mb-8">
              Dolore magnam aliquam quaerat voluptatem. Ut enim ad minima
              veniam, quis nostrum exercitationem ullam corporis suscipit
              laboriosam, nisi ut aliquid ex ea.
            </p>
            <p class="mb-8">
              Commodo consequatur? Quis autem vel eum iure reprehenderit qui in
              ea voluptate velit esse quam nihil molestiae consequatur, vel
              illum qui dolorem eum.
            </p>
            <p class="mb-8">Fugiat quo voluptas nulla pariatur?</p>
          </div>
        </div>

        <button
          class="p-2 text-verso-dark hover:text-verso-blue transition hidden md:block"
        >
          <ChevronRight class="w-10 h-10" />
        </button>
      </div>

      <footer
        class="h-16 flex items-center justify-center font-extrabold text-verso-dark text-lg"
      >
        1/10
      </footer>
    </div>

    <aside
      class="w-80 bg-[#CFE2F3] flex flex-col transition-all duration-300 relative border-l border-white/20 shrink-0"
      :class="{ 'mr-0': showAnnotations, '-mr-80': !showAnnotations }"
    >
      <div class="flex justify-between items-center px-8 py-8">
        <button
          @click="toggleSidebar"
          class="p-2 bg-verso-dark text-white rounded-lg transition shadow-md"
        >
          <StickyNote class="w-6 h-6" />
        </button>
        <button
          class="p-2 hover:bg-white/20 rounded-lg transition text-verso-dark"
        >
          <Bookmark class="w-6 h-6" />
        </button>
        <button
          class="p-2 hover:bg-white/20 rounded-lg transition text-verso-dark"
        >
          <Edit3 class="w-6 h-6" />
        </button>
      </div>

      <div class="flex-1 overflow-y-auto px-6">
        <h3 class="font-extrabold text-verso-dark mb-8 text-center text-lg">
          Annotations
        </h3>

        <div class="space-y-8">
          <div
            v-for="(note, i) in annotations"
            :key="i"
            class="group cursor-pointer"
          >
            <h4 class="font-bold text-sm text-verso-dark leading-snug">
              {{ note.chapter }}
            </h4>
            <p v-if="note.text" class="text-xs text-verso-dark/70 mt-1">
              {{ note.text }}
            </p>
          </div>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref } from "vue";
import Sidebar from "@/components/layout/Sidebar.vue";
import {
  ChevronLeft,
  ChevronRight,
  StickyNote,
  Bookmark,
  Edit3,
} from "lucide-vue-next";

// Initial state matches image_98ffd3 (Open)
const showAnnotations = ref(true);

const toggleSidebar = () => {
  showAnnotations.value = !showAnnotations.value;
};

const annotations = ref([
  { chapter: "Chapter 1: Lorem Ipsum Dolor Totum" },
  { chapter: "Chapter 2: Lorem Ipsum Dolor" },
  { chapter: "Chapter 3: Lorem Ipsum Dolor Totum" },
  { chapter: "Chapter 4: Lorem Ipsum Dolor Totum" },
  { chapter: "Chapter 5: Lorem Ipsum Dolor Totum .Lorem Ipsum Dolor Totum" },
  { chapter: "Chapter 6: Lorem Ipsum Dolor Totum" },
  { chapter: "Chapter 7: Lorem Ipsum Dolor Totum Lorem Ipsum Dolor Totum" },
  { chapter: "Chapter 8: Lorem Ipsum Dolor Totum Lorem Ipsum Dolor Totum" },
]);
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

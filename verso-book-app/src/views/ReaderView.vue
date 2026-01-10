<template>
  <div
    class="h-screen flex flex-col bg-[#FDF6E9] text-verso-dark overflow-hidden font-serif"
  >
    <header
      class="h-14 flex items-center justify-between px-4 border-b border-[#EADDCD] bg-[#FDF6E9] shrink-0 z-10"
    >
      <button
        @click="$router.back()"
        class="text-2xl text-gray-600 leading-none pb-1"
      >
        ←
      </button>
      <div class="text-center">
        <h1 class="text-sm font-bold uppercase tracking-wide text-gray-500">
          {{ currentChapter }}
        </h1>
        <p class="text-xs text-gray-400">Lorem Ipsum Dolor</p>
      </div>
      <button
        @click="showAnnotations = !showAnnotations"
        class="text-verso-blue font-bold text-xl"
      >
        {{ showAnnotations ? "✕" : "A" }}
      </button>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
      <div
        class="flex-1 overflow-y-auto px-6 py-8 md:px-20 lg:px-60 text-lg leading-loose text-gray-800 scroll-smooth"
        id="reader-content"
      >
        <h2 class="text-3xl font-bold mb-8 text-center">
          {{ currentChapter }}
        </h2>

        <p class="mb-6">
          <span class="text-4xl font-bold float-left mr-2 mt-[-6px]">S</span>ed
          ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
          doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo
          inventore veritatis et quasi architecto beatae vitae dicta sunt
          explicabo.
        </p>
        <p class="mb-6">
          Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut
          fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem
          sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor
          sit amet, consectetur, adipisci velit, sed quia non numquam eius modi
          tempora incidunt ut labore et dolore magnam aliquam quaerat
          voluptatem.
        </p>
        <p class="mb-6">
          Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis
          suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis
          autem vel eum iure reprehenderit qui in ea voluptate velit esse quam
          nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo
          voluptas nulla pariatur?
        </p>
        <p class="mb-6">
          At vero eos et accusamus et iusto odio dignissimos ducimus qui
          blanditiis praesentium voluptatum deleniti atque corrupti quos dolores
          et quas molestias excepturi sint occaecati cupiditate non provident,
          similique sunt in culpa qui officia deserunt mollitia animi, id est
          laborum et dolorum fuga.
        </p>
        <p class="mb-6">
          Et harum quidem rerum facilis est et expedita distinctio. Nam libero
          tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo
          minus id quod maxime placeat facere possimus, omnis voluptas assumenda
          est, omnis dolor repellendus.
        </p>
      </div>

      <transition
        enter-active-class="transform transition ease-in-out duration-300"
        enter-from-class="translate-x-full"
        enter-to-class="translate-x-0"
        leave-active-class="transform transition ease-in-out duration-300"
        leave-from-class="translate-x-0"
        leave-to-class="translate-x-full"
      >
        <div
          v-if="showAnnotations"
          class="absolute inset-y-0 right-0 w-full md:w-80 bg-white shadow-2xl z-20 flex flex-col border-l border-gray-200"
        >
          <div
            class="p-4 border-b bg-gray-50 flex justify-between items-center"
          >
            <h2 class="font-bold text-verso-dark">Annotations</h2>
            <button
              @click="showAnnotations = false"
              class="text-gray-400 hover:text-red-500"
            >
              Close
            </button>
          </div>

          <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <div
              v-for="(note, index) in annotations"
              :key="index"
              class="p-3 rounded-lg border border-gray-100 hover:bg-verso-cream/50 cursor-pointer transition"
              @click="currentChapter = note.chapter"
            >
              <h4 class="font-bold text-sm text-verso-blue">
                {{ note.chapter }}
              </h4>
              <p class="text-xs text-gray-500 mt-1">{{ note.text }}</p>
            </div>
          </div>
        </div>
      </transition>
    </div>

    <footer
      class="h-16 bg-white border-t border-gray-200 flex items-center justify-between px-6 shrink-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]"
    >
      <div class="flex items-center gap-4 text-sm font-medium text-gray-500">
        <span>{{ page }} / 10</span>
      </div>

      <div class="flex items-center gap-4">
        <button
          class="w-8 h-8 rounded-full bg-gray-100 hover:bg-verso-blue hover:text-white transition font-serif font-bold text-lg"
        >
          A-
        </button>
        <button
          class="w-8 h-8 rounded-full bg-gray-100 hover:bg-verso-blue hover:text-white transition font-serif font-bold text-lg"
        >
          A+
        </button>
      </div>

      <div class="w-1/3 max-w-xs hidden md:block">
        <div class="h-1.5 w-full bg-gray-200 rounded-full">
          <div
            class="h-full bg-verso-blue rounded-full"
            style="width: 10%"
          ></div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from "vue";

const showAnnotations = ref(false);
const currentChapter = ref("Chapter 1");
const page = ref(1);

const annotations = ref([
  { chapter: "Chapter 1", text: "Lorem Ipsum Dolor Totum" },
  { chapter: "Chapter 2", text: "Sed ut perspiciatis unde omnis" },
  { chapter: "Chapter 3", text: "Lorem Ipsum Dolor Totum" },
  { chapter: "Chapter 4", text: "Nemo enim ipsam voluptatem" },
  { chapter: "Chapter 5", text: "Lorem Ipsum Dolor Totum" },
  { chapter: "Chapter 6", text: "Lorem Ipsum Dolor Totum" },
  { chapter: "Chapter 7", text: "Lorem Ipsum Dolor Totum" },
]);
</script>

<style scoped>
/* Custom Scrollbar for Reader */
#reader-content::-webkit-scrollbar {
  width: 8px;
}
#reader-content::-webkit-scrollbar-track {
  background: transparent;
}
#reader-content::-webkit-scrollbar-thumb {
  background-color: #e5e7eb;
  border-radius: 20px;
}
</style>

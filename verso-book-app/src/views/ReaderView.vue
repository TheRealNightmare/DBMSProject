<template>
  <div
    class="h-screen flex flex-col bg-[#FDF6E9] text-verso-dark overflow-hidden font-serif relative"
  >
    <header
      class="h-16 flex items-center justify-between px-4 py-3 bg-[#FDF6E9] shrink-0 z-10 relative"
    >
      <button
        @click="$router.back()"
        class="text-3xl text-gray-400 hover:text-gray-600 transition leading-none pb-2 pl-2"
      >
        ←
      </button>

      <div class="absolute left-1/2 transform -translate-x-1/2 text-center">
        <h1 class="text-xl font-bold text-verso-dark tracking-wide">
          Think Again
        </h1>
      </div>

      <button
        @click="showAnnotations = !showAnnotations"
        class="text-xl font-bold text-gray-400 hover:text-verso-blue tracking-widest p-2"
      >
        ••
      </button>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
      <div
        id="reader-content"
        class="flex-1 overflow-y-auto px-6 py-2 md:px-20 lg:px-60 text-lg leading-loose text-gray-800 scroll-smooth pb-24"
      >
        <h2
          class="text-lg font-bold text-gray-400 mb-8 uppercase tracking-widest"
        >
          {{ currentChapter }}
        </h2>

        <p class="mb-6 text-justify">
          <span
            class="text-5xl font-bold float-left mr-3 mt-[-8px] text-verso-dark"
            >S</span
          >ed ut perspiciatis unde omnis iste natus error sit voluptatem
          accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae
          ab illo inventore veritatis et quasi architecto beatae vitae dicta
          sunt explicabo.
        </p>
        <p class="mb-6 text-justify">
          Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut
          fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem
          sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor
          sit amet, consectetur, adipisci velit, sed quia non numquam eius modi
          tempora incidunt ut labore et dolore magnam aliquam quaerat
          voluptatem.
        </p>
        <p class="mb-6 text-justify">
          Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis
          suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis
          autem vel eum iure reprehenderit qui in ea voluptate velit esse quam
          nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo
          voluptas nulla pariatur?
        </p>
        <p class="mb-6 text-justify">
          At vero eos et accusamus et iusto odio dignissimos ducimus qui
          blanditiis praesentium voluptatum deleniti atque corrupti quos dolores
          et quas molestias excepturi sint occaecati cupiditate non provident,
          similique sunt in culpa qui officia deserunt mollitia animi, id est
          laborum et dolorum fuga.
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
          class="absolute inset-y-0 right-0 w-full md:w-80 bg-white shadow-2xl z-30 flex flex-col border-l border-gray-200"
        >
          <div
            class="p-4 border-b bg-gray-50 flex justify-between items-center"
          >
            <h2 class="font-bold text-verso-dark">Annotations</h2>
            <button
              @click="showAnnotations = false"
              class="text-gray-400 hover:text-red-500 text-xl"
            >
              ✕
            </button>
          </div>

          <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <div
              v-for="(note, index) in annotations"
              :key="index"
              class="p-4 rounded-xl border border-gray-100 hover:bg-verso-cream/50 cursor-pointer transition shadow-sm"
              @click="handleChapterClick(note.chapter)"
            >
              <h4 class="font-bold text-sm text-verso-blue mb-1">
                {{ note.chapter }}
              </h4>
              <p class="text-xs text-gray-500 leading-relaxed">
                {{ note.text }}
              </p>
            </div>
          </div>
        </div>
      </transition>
    </div>

    <button
      class="absolute bottom-20 right-6 w-14 h-14 bg-verso-blue text-white rounded-full shadow-lg shadow-verso-blue/30 flex items-center justify-center text-3xl font-light z-20 hover:scale-105 active:scale-95 transition"
    >
      +
    </button>

    <footer
      class="h-16 bg-white border-t border-gray-100 flex items-center justify-center shrink-0 z-10 px-4 shadow-[0_-4px_20px_-5px_rgba(0,0,0,0.05)]"
    >
      <div class="flex items-center gap-8 select-none">
        <button
          @click="prevPage"
          class="text-2xl text-gray-300 hover:text-verso-blue transition transform hover:-translate-x-1 disabled:opacity-30"
          :disabled="page <= 1"
        >
          &lt;
        </button>

        <span
          class="text-sm font-bold text-gray-600 font-serif tracking-widest min-w-[3rem] text-center"
        >
          {{ page }}/10
        </span>

        <button
          @click="nextPage"
          class="text-2xl text-gray-300 hover:text-verso-blue transition transform hover:translate-x-1 disabled:opacity-30"
          :disabled="page >= 10"
        >
          &gt;
        </button>
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
]);

const nextPage = () => {
  if (page.value < 10) page.value++;
};

const prevPage = () => {
  if (page.value > 1) page.value--;
};

const handleChapterClick = (chapter) => {
  currentChapter.value = chapter;
  showAnnotations.value = false;
};
</script>

<style scoped>
#reader-content::-webkit-scrollbar {
  width: 6px;
}
#reader-content::-webkit-scrollbar-track {
  background: transparent;
}
#reader-content::-webkit-scrollbar-thumb {
  background-color: #eaddcd;
  border-radius: 20px;
}
</style>

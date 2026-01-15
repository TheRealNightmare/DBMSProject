<template>
  <div
    class="bg-white/50 rounded-2xl p-4 flex flex-col items-center justify-center h-full relative"
  >
    <div class="w-full flex justify-between items-center mb-2">
      <div class="flex items-center gap-2">
        <div class="w-3 h-3 bg-verso-blue rounded-sm"></div>
        <span class="text-xs font-bold text-gray-600">Point Progress</span>
      </div>
      <span class="text-xs text-gray-400">Monthly v</span>
    </div>

    <div class="relative w-32 h-16 overflow-hidden mt-4">
      <div
        class="absolute top-0 left-0 w-32 h-32 rounded-full border-[12px] border-gray-200"
      ></div>
      <div
        class="absolute top-0 left-0 w-32 h-32 rounded-full border-[12px] border-verso-blue border-b-transparent border-r-transparent -rotate-45 transform origin-center"
      ></div>
    </div>

    <div
      class="absolute top-[55%] left-1/2 -translate-x-1/2 -translate-y-1/2 w-1 h-10 bg-gray-600 origin-bottom rotate-[-30deg]"
      style="border-radius: 50% 50% 0 0"
    >
      <div
        class="absolute -bottom-1 -left-1.5 w-4 h-4 bg-white border-2 border-gray-600 rounded-full"
      ></div>
    </div>

    <div class="mt-4 text-center">
      <p class="text-xs text-gray-400">
        Your Point: <span class="font-bold text-verso-dark text-sm">8.966</span>
      </p>
    </div>
  </div>
</template>

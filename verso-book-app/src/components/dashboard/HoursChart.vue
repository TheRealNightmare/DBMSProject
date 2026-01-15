<template>
  <div class="bg-transparent h-full flex flex-col">
    <h3 class="font-bold text-verso-dark mb-4">Hours Spent</h3>
    <div class="flex-1 flex items-end justify-between gap-4 px-2 relative">
      <div class="absolute left-0 top-0 bottom-6 flex flex-col justify-between text-[10px] text-gray-400">
        <span>80 h</span>
        <span>60 h</span>
        <span>40 h</span>
        <span>20 h</span>
        <span>0 h</span>
      </div>

      <div class="ml-8 w-full h-full flex items-end justify-between">
        <div class="flex flex-col items-center gap-2 group w-1/5">
          <div class="w-full bg-verso-dark rounded-sm h-[50%] transition-all duration-500 hover:opacity-80"></div>
          <span class="text-xs font-bold text-gray-600">Jan</span>
        </div>
        <div class="flex flex-col items-center gap-2 group w-1/5">
          <div class="w-full bg-verso-blue rounded-sm h-[20%] transition-all duration-500 hover:opacity-80"></div>
          <span class="text-xs font-bold text-gray-600">Feb</span>
        </div>
        <div class="flex flex-col items-center gap-2 group w-1/5">
          <div class="w-full bg-verso-dark rounded-sm h-[45%] transition-all duration-500 hover:opacity-80"></div>
          <span class="text-xs font-bold text-gray-600">Mar</span>
        </div>
        <div class="flex flex-col items-center gap-2 group w-1/5">
          <div class="w-full bg-verso-blue rounded-sm h-[70%] transition-all duration-500 hover:opacity-80"></div>
          <span class="text-xs font-bold text-gray-600">Apr</span>
        </div>
        <div class="flex flex-col items-center gap-2 group w-1/5">
          <div class="w-full bg-verso-dark rounded-sm h-[60%] transition-all duration-500 hover:opacity-80"></div>
          <span class="text-xs font-bold text-gray-600">May</span>
        </div>
      </div>
    </div>
  </div>
</template>
<template>
  <aside class="w-80 bg-[#EFE8D8] h-screen p-6 flex flex-col border-l border-gray-200/50 sticky top-0 overflow-y-auto">
    <div class="flex justify-between items-start mb-8">
      <h2 class="font-bold text-verso-dark text-lg">Profile</h2>
      <button class="text-gray-500 hover:text-verso-dark">
        <Edit2 class="w-4 h-4" />
      </button>
    </div>

    <div class="flex flex-col items-center mb-10 relative">
      <div class="w-24 h-24 rounded-full p-1 border-2 border-gray-300 relative mb-3">
        <img src="https://i.pinimg.com/736x/8f/c3/7b/8fc37b74b608a622588fbaa361485f32.jpg" 
             class="w-full h-full rounded-full object-cover" 
             alt="Peter" />
        <div class="absolute top-0 right-0 w-full h-full border-t-4 border-r-4 border-verso-dark rounded-full rotate-45 pointer-events-none"></div>
      </div>
      <h3 class="text-xl font-bold text-verso-dark">Peter</h3>
      <p class="text-xs text-gray-500 font-medium">College Student</p>
      
      <div class="absolute right-0 top-1/2 translate-x-4 w-1.5 h-1.5 bg-verso-dark rounded-full"></div>
    </div>

    <div class="mb-10">
      <div class="flex justify-between items-center mb-4 px-2">
        <button class="text-gray-400 hover:text-verso-dark"><ChevronLeft class="w-4 h-4" /></button>
        <span class="font-bold text-sm text-verso-dark">Jan 2025</span>
        <button class="text-gray-400 hover:text-verso-dark"><ChevronRight class="w-4 h-4" /></button>
      </div>
      <div class="grid grid-cols-7 gap-y-3 text-center text-xs font-medium text-gray-400">
        <div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div><div>S</div>
        <div class="opacity-0">29</div><div class="opacity-0">30</div>
        <div>1</div><div>2</div><div>3</div><div>4</div><div>5</div>
        <div>6</div><div>7</div><div>8</div><div>9</div><div>10</div><div>11</div><div>12</div>
        <div>13</div>
        <div class="bg-verso-dark text-white rounded-full w-6 h-6 flex items-center justify-center mx-auto shadow-md">14</div>
        <div>15</div><div>16</div><div>17</div><div>18</div><div>19</div>
      </div>
    </div>

    <div class="flex-1">
      <h3 class="font-bold text-verso-dark mb-4 text-sm">To Do List</h3>
      <div class="space-y-6">
        <div class="flex gap-3 group cursor-pointer">
          <div class="mt-0.5">
             <div class="w-5 h-5 border-2 border-gray-400 rounded-lg group-hover:border-verso-blue transition"></div>
          </div>
          <div>
            <p class="text-xs font-bold text-verso-dark leading-tight">Theory of Networking</p>
            <p class="text-[10px] text-gray-500 mt-1"><span class="font-medium text-verso-blue">Social Book</span> • 08:00 AM</p>
          </div>
        </div>

        <div class="flex gap-3 group cursor-pointer">
           <div class="mt-0.5">
             <div class="w-5 h-5 border-2 border-gray-400 rounded-lg group-hover:border-verso-blue transition"></div>
          </div>
          <div>
            <p class="text-xs font-bold text-verso-dark leading-tight">Learn about data</p>
          </div>
        </div>

        <div class="flex gap-3 group cursor-pointer">
           <div class="mt-0.5">
             <div class="w-5 h-5 border-2 border-gray-400 rounded-lg group-hover:border-verso-blue transition"></div>
          </div>
          <div>
            <p class="text-xs font-bold text-verso-dark leading-tight">30 min of peace</p>
            <p class="text-[10px] text-gray-500 mt-1"><span class="font-medium text-verso-blue">Poetry Session</span><br/>Art • 02:40 PM</p>
          </div>
        </div>

        <div class="flex gap-3 group cursor-pointer opacity-60">
           <div class="mt-0.5">
             <div class="w-5 h-5 bg-verso-dark border-2 border-verso-dark rounded-lg flex items-center justify-center">
                <Check class="w-3 h-3 text-white" />
             </div>
          </div>
          <div>
             <p class="text-xs font-bold text-verso-dark leading-tight line-through">Learn to cod...</p>
             <p class="text-[10px] text-gray-500 mt-1"><span class="font-medium text-verso-blue">Business</span> • 01:50 PM</p>
          </div>
        </div>
      </div>
    </div>

  </aside>
</template>

<script setup>
import { Edit2, ChevronLeft, ChevronRight, Check } from 'lucide-vue-next';
</script>
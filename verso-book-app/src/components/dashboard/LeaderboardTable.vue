<template>
  <div class="w-full">
    <h3 class="font-bold text-verso-dark mb-4">Leader Board</h3>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-gray-400 text-xs uppercase tracking-wider">
            <th class="pb-3 pl-2">Rank</th>
            <th class="pb-3">Name</th>
            <th class="pb-3 text-center">Course</th>
            <th class="pb-3 text-center">Hour</th>
            <th class="pb-3 text-right pr-2">Point</th>
          </tr>
        </thead>
        <tbody class="space-y-4">
          <tr v-for="(user, index) in users" :key="index" class="hover:bg-white/50 transition rounded-lg group">
            <td class="py-3 pl-2 font-bold text-verso-blue flex items-center gap-2">
               {{ user.rank }}
               <span v-if="index === 0" class="w-0 h-0 border-l-[4px] border-l-transparent border-r-[4px] border-r-transparent border-b-[6px] border-b-verso-dark"></span>
               <span v-if="index === 1" class="w-0 h-0 border-l-[4px] border-l-transparent border-r-[4px] border-r-transparent border-t-[6px] border-t-red-400"></span>
               <span v-if="index === 2" class="w-0 h-0 border-l-[4px] border-l-transparent border-r-[4px] border-r-transparent border-t-[6px] border-t-red-400"></span>
            </td>
            <td class="py-3">
              <div class="flex items-center gap-3">
                <img :src="user.avatar" class="w-8 h-8 rounded-full object-cover border border-white shadow-sm" />
                <span class="font-medium text-verso-dark">{{ user.name }}</span>
              </div>
            </td>
            <td class="py-3 text-center font-medium text-gray-500">{{ user.course }}</td>
            <td class="py-3 text-center font-medium text-gray-500">{{ user.hour }}</td>
            <td class="py-3 text-right pr-2 font-bold text-verso-blue">{{ user.point }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
const users = [
  { rank: 1, name: "Charlie Rawal", avatar: "https://placehold.co/100x100/orange/white", course: 53, hour: 250, point: "10.450" },
  { rank: 2, name: "Arlene Agarwal", avatar: "https://placehold.co/100x100/blue/white", course: 56, hour: 212, point: "10.333" },
  { rank: 3, name: "Bessie Charles", avatar: "https://placehold.co/100x100/green/white", course: 86, hour: 212, point: "10.333" },
];
</script>
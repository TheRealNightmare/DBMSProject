{ type: uploaded file fileName:
therealnightmare/dbmsproject/DBMSProject-5b83893bfc00ef964c65db6a2f8ddb218e24e0ba/verso-book-app/src/views/EventView.vue
fullContent:
<template>
  <div
    class="min-h-screen bg-[#FDF6E9] font-sans flex flex-col md:flex-row overflow-x-hidden"
  >
    <Sidebar />

    <div class="flex-1 flex flex-col md:ml-20 min-h-screen">
      <main class="flex-1 px-8 py-8 max-w-6xl mx-auto w-full">
        <h1 class="text-base font-bold text-verso-dark mb-4">
          Up coming events:
        </h1>

        <div class="bg-[#EFE8D8] rounded-xl p-6 mb-8">
          <EventBanner
            host="Rosamund Pike"
            title="Know your Writer"
            description="Short documentary on the following writers: William Shakespeare, Agatha Christie"
            date="April 1"
          />

          <EventBanner
            host="Lin-Manuel Miranda"
            title="Genre-mania"
            description="Festival themed on 'FICTION' containing book stalls, author signings and many more"
            date="April 4"
          />
        </div>

        <EventCalendar />
      </main>
    </div>

    <div class="md:hidden">
      <BottomNav active="events" />
    </div>
  </div>
</template>

<script setup>
import Sidebar from "@/components/layout/Sidebar.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import EventBanner from "@/components/event/EventBanner.vue";
import EventCalendar from "@/components/event/EventCalendar.vue";
</script>
}

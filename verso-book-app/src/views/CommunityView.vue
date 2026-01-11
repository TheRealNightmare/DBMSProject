<template>
  <div class="min-h-screen bg-verso-cream font-sans flex flex-col">
    <Sidebar />

    <div
      class="md:ml-20 flex flex-col min-h-screen transition-all duration-300 relative"
    >
      <Navbar />

      <main class="flex-1 flex flex-col p-4 md:p-6 h-[calc(100vh-64px)]">
        <div
          class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row"
        >
          <div
            class="w-full md:w-72 bg-gray-50 border-r border-gray-100 flex flex-col"
          >
            <div class="p-5 border-b border-gray-100">
              <h2 class="font-bold text-verso-dark text-lg tracking-tight">
                Community
              </h2>
              <div class="relative mt-4">
                <input
                  type="text"
                  placeholder="Search channels..."
                  class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-verso-blue transition"
                />
              </div>
            </div>

            <div class="flex-1 overflow-y-auto p-3 space-y-1">
              <div
                class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider"
              >
                Channels
              </div>

              <button
                v-for="channel in channels"
                :key="channel.id"
                @click="activeChannel = channel.id"
                class="w-full text-left px-3 py-2 rounded-lg flex items-center gap-3 transition"
                :class="
                  activeChannel === channel.id
                    ? 'bg-verso-blue/10 text-verso-blue'
                    : 'text-gray-600 hover:bg-gray-100'
                "
              >
                <span class="text-lg">#</span>
                <span class="font-medium text-sm">{{ channel.name }}</span>
                <span
                  v-if="channel.unread"
                  class="ml-auto bg-verso-blue text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                >
                  {{ channel.unread }}
                </span>
              </button>

              <div
                class="px-3 py-2 mt-4 text-xs font-bold text-gray-400 uppercase tracking-wider"
              >
                Online Members
              </div>
              <button
                v-for="user in onlineUsers"
                :key="user.id"
                class="w-full text-left px-3 py-2 rounded-lg flex items-center gap-3 transition hover:bg-gray-100"
              >
                <div class="relative">
                  <img
                    :src="user.avatar"
                    class="w-8 h-8 rounded-full object-cover"
                  />
                  <div
                    class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"
                  ></div>
                </div>
                <span class="font-medium text-sm text-verso-dark">{{
                  user.name
                }}</span>
              </button>
            </div>
          </div>

          <div class="flex-1 flex flex-col min-w-0">
            <div
              class="h-16 border-b border-gray-100 flex items-center justify-between px-6 bg-white shrink-0"
            >
              <div class="flex items-center gap-2">
                <span class="text-2xl text-gray-400">#</span>
                <div>
                  <h3 class="font-bold text-verso-dark">General Discussion</h3>
                  <p class="text-xs text-gray-500">1,240 members • 32 online</p>
                </div>
              </div>
              <div class="flex -space-x-2">
                <img
                  v-for="i in 3"
                  :key="i"
                  :src="`https://placehold.co/40x40?text=${i}`"
                  class="w-8 h-8 rounded-full border-2 border-white"
                />
              </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50/50">
              <div
                v-for="msg in messages"
                :key="msg.id"
                class="flex gap-4 group"
                :class="{ 'flex-row-reverse': msg.isMe }"
              >
                <img
                  :src="msg.avatar"
                  class="w-10 h-10 rounded-full object-cover shadow-sm mt-1"
                />

                <div
                  class="flex flex-col max-w-[70%]"
                  :class="{ 'items-end': msg.isMe }"
                >
                  <div
                    class="flex items-baseline gap-2 mb-1"
                    :class="{ 'flex-row-reverse': msg.isMe }"
                  >
                    <span class="font-bold text-verso-dark text-sm">{{
                      msg.sender
                    }}</span>
                    <span class="text-xs text-gray-400">{{ msg.time }}</span>
                  </div>

                  <div
                    class="px-5 py-3 rounded-2xl text-sm leading-relaxed shadow-sm"
                    :class="
                      msg.isMe
                        ? 'bg-verso-blue text-white rounded-tr-none'
                        : 'bg-white text-gray-700 rounded-tl-none border border-gray-100'
                    "
                  >
                    {{ msg.text }}
                  </div>
                </div>
              </div>
            </div>

            <div class="p-4 bg-white border-t border-gray-100">
              <div
                class="bg-gray-50 border border-gray-200 rounded-xl flex items-center px-4 py-2 gap-3 focus-within:ring-2 focus-within:ring-verso-blue/20 focus-within:border-verso-blue transition"
              >
                <button class="text-gray-400 hover:text-verso-blue transition">
                  <Plus class="w-5 h-5" />
                </button>
                <input
                  type="text"
                  placeholder="Message #General Discussion..."
                  class="flex-1 bg-transparent border-none focus:outline-none text-verso-dark placeholder-gray-400 py-2"
                  @keyup.enter="sendMessage"
                />
                <button class="text-gray-400 hover:text-verso-blue transition">
                  <Smile class="w-5 h-5" />
                </button>
                <button
                  class="bg-verso-blue text-white p-2 rounded-lg hover:opacity-90 transition shadow-sm"
                >
                  <Send class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <div class="md:hidden">
      <BottomNav active="community" />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import { Send, Plus, Smile } from "lucide-vue-next";

const activeChannel = ref(1);

// Mock Data
const channels = ref([
  { id: 1, name: "General Discussion", unread: 0 },
  { id: 2, name: "Book Recommendations", unread: 3 },
  { id: 3, name: "Sci-Fi & Fantasy", unread: 0 },
  { id: 4, name: "Writers Corner", unread: 12 },
  { id: 5, name: "Announcements", unread: 0 },
]);

const onlineUsers = ref([
  { id: 1, name: "Peter Parker", PJ: "https://placehold.co/100x100" },
  { id: 2, name: "Harleen Quinzel", avatar: "https://placehold.co/100x100" },
  { id: 3, name: "Bruce Wayne", avatar: "https://placehold.co/100x100" },
]);

const messages = ref([
  {
    id: 1,
    sender: "Harleen Quinzel",
    avatar: "https://placehold.co/100x100",
    text: "Has anyone finished 'Think Again' yet? That ending completely blew my mind! 🤯",
    time: "10:30 AM",
    isMe: false,
  },
  {
    id: 2,
    sender: "Peter Parker",
    avatar: "https://placehold.co/100x100",
    text: "I'm about halfway through! Please no spoilers, I'm loving the character development so far.",
    time: "10:32 AM",
    isMe: true,
  },
  {
    id: 3,
    sender: "Bruce Wayne",
    avatar: "https://placehold.co/100x100",
    text: "It's definitely one of the best books released this year. The philosophical themes are quite deep.",
    time: "10:35 AM",
    isMe: false,
  },
  {
    id: 4,
    sender: "Harleen Quinzel",
    avatar: "https://placehold.co/100x100",
    text: "Exactly! I love how it challenges your perspective on logical fallacies.",
    time: "10:36 AM",
    isMe: false,
  },
]);

const sendMessage = (e) => {
  const text = e.target.value;
  if (!text) return;

  messages.value.push({
    id: Date.now(),
    sender: "Peter Parker",
    avatar: "https://placehold.co/100x100",
    text: text,
    time: new Date().toLocaleTimeString([], {
      hour: "2-digit",
      minute: "2-digit",
    }),
    isMe: true,
  });

  e.target.value = "";
};
</script>

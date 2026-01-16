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
                v-if="onlineUsers.length > 0"
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
                  <h3 class="font-bold text-verso-dark">
                    {{
                      channels.find((c) => c.id === activeChannel)?.name ||
                      "Select a Channel"
                    }}
                  </h3>
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
                  v-model="newMessage"
                  type="text"
                  :placeholder="`Message #${
                    channels.find((c) => c.id === activeChannel)?.name ||
                    'Channel'
                  }...`"
                  class="flex-1 bg-transparent border-none focus:outline-none text-verso-dark placeholder-gray-400 py-2"
                  @keyup.enter="sendMessage"
                />

                <button class="text-gray-400 hover:text-verso-blue transition">
                  <Smile class="w-5 h-5" />
                </button>

                <button
                  @click="sendMessage"
                  class="bg-verso-blue text-white p-2 rounded-lg hover:opacity-90 transition shadow-sm"
                  :disabled="!newMessage.trim()"
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
import { ref, onMounted, onUnmounted, nextTick, watch } from "vue";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import { Send, Plus, Smile } from "lucide-vue-next";
import api from "@/services/api";
import createEcho from "@/services/echo";

// State
const activeChannel = ref(null);
const channels = ref([]);
const messages = ref([]);
const newMessage = ref("");
const currentUser = JSON.parse(localStorage.getItem("user") || "{}");
const onlineUsers = ref([]);

let echoInstance = null;

// Lifecycle: Initialize
onMounted(async () => {
  await fetchGroups();

  // Initialize Echo
  echoInstance = createEcho();

  // Select first channel by default if available
  if (channels.value.length > 0) {
    activeChannel.value = channels.value[0].id;
  }
});

// Lifecycle: Cleanup
onUnmounted(() => {
  if (echoInstance) {
    echoInstance.disconnect();
  }
});

// Watcher: Switch Channels & Subscribe
watch(activeChannel, (newId, oldId) => {
  if (oldId && echoInstance) {
    echoInstance.leave(`community.${oldId}`);
  }

  if (newId) {
    fetchMessages(newId);
    subscribeToChannel(newId);
  }
});

// --- API Actions ---

const fetchGroups = async () => {
  try {
    const response = await api.get("/community/groups");
    // Map backend groups to frontend structure
    channels.value = response.data.map((g) => ({
      id: g.group_id,
      name: g.name,
      unread: 0,
    }));
  } catch (error) {
    console.error("Failed to fetch groups:", error);
  }
};

const fetchMessages = async (groupId) => {
  try {
    const response = await api.get(`/community/groups/${groupId}/messages`);
    messages.value = response.data.map(transformMessage);
    scrollToBottom();
  } catch (error) {
    console.error("Failed to fetch messages:", error);
  }
};

const sendMessage = async () => {
  if (!newMessage.value.trim()) return;

  const tempText = newMessage.value;
  newMessage.value = ""; // Clear input immediately for UX

  try {
    await api.post(`/community/groups/${activeChannel.value}/messages`, {
      message: tempText,
    });
    // The socket event will handle adding the message to the UI
  } catch (error) {
    console.error("Failed to send message:", error);
    alert("Message failed to send.");
  }
};

// --- Real-time Logic ---

const subscribeToChannel = (groupId) => {
  if (!echoInstance) return;

  console.log(`Subscribing to community.${groupId}...`);

  echoInstance.private(`community.${groupId}`).listen("MessageSent", (e) => {
    console.log("Event received:", e);

    // Transform the incoming event payload to match our UI
    const incomingMsg = {
      id: e.message_id,
      sender: e.sender.username,
      avatar: e.sender.profile_image || "https://placehold.co/100x100",
      text: e.message_body,
      time: formatTime(e.sent_at),
      isMe: e.sender.user_id === currentUser.user_id,
    };

    messages.value.push(incomingMsg);
    scrollToBottom();
  });
};

// --- Helpers ---

const transformMessage = (msg) => {
  return {
    id: msg.message_id,
    sender: msg.sender?.username || "Unknown",
    avatar: msg.sender?.profile_image || "https://placehold.co/100x100",
    text: msg.message_body,
    time: formatTime(msg.sent_at),
    isMe: msg.sender_id === currentUser.user_id,
  };
};

const formatTime = (isoString) => {
  if (!isoString) return "";
  const date = new Date(isoString);
  return date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
};

const scrollToBottom = () => {
  nextTick(() => {
    const container = document.querySelector(
      ".overflow-y-auto.bg-gray-50\\/50"
    );
    if (container) {
      container.scrollTop = container.scrollHeight;
    }
  });
};
</script>

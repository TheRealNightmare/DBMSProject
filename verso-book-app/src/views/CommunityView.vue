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
            </div>

            <div class="flex-1 overflow-y-auto p-3 space-y-1">
              <div
                class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider flex justify-between items-center"
              >
                <span>Channels</span>
                <div class="flex gap-1">
                  <button
                    @click="showCreateModal = true"
                    class="text-verso-blue hover:bg-verso-blue/10 rounded p-1"
                    title="Create Channel"
                  >
                    <Plus class="w-4 h-4" />
                  </button>
                  <button
                    @click="showJoinModal = true"
                    class="text-green-600 hover:bg-green-50 rounded p-1"
                    title="Join Channel"
                  >
                    <UserPlus class="w-4 h-4" />
                  </button>
                </div>
              </div>

              <div
                v-if="channels.length === 0"
                class="px-3 py-4 text-sm text-gray-500 italic"
              >
                No channels found.
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
                <div class="flex-1 min-w-0">
                  <div class="font-medium text-sm truncate">{{ channel.name }}</div>
                  <div v-if="channel.is_default" class="text-xs text-gray-400">Default</div>
                </div>
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
                    {{ currentChannelName }}
                  </h3>
                  <p v-if="currentChannelDescription" class="text-xs text-gray-500">
                    {{ currentChannelDescription }}
                  </p>
                </div>
              </div>
            </div>

            <div
              class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50/50"
              ref="messagesContainer"
            >
              <div
                v-if="messages.length === 0"
                class="text-center text-gray-400 mt-10"
              >
                <p>No messages yet. Be the first to say hello!</p>
              </div>

              <div
                v-for="msg in messages"
                :key="msg.id"
                class="flex gap-4 group"
                :class="{ 'flex-row-reverse': msg.isMe }"
              >
                <img
                  :src="msg.avatar"
                  class="w-10 h-10 rounded-full object-cover shadow-sm mt-1 bg-gray-200"
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
                    class="px-5 py-3 rounded-2xl text-sm leading-relaxed shadow-sm cursor-pointer select-none relative"
                    :class="[
                      msg.isMe
                        ? 'bg-verso-blue text-white rounded-tr-none'
                        : 'bg-white text-gray-700 rounded-tl-none border border-gray-100',
                      msg.isBlurred && !msg.revealed ? 'blur-message' : ''
                    ]"
                    @click="msg.isBlurred && !msg.revealed ? revealMessage(msg) : null"
                    :title="msg.isBlurred && !msg.revealed ? 'Click to reveal' : ''"
                  >
                    {{ msg.text }}
                    <span v-if="msg.isBlurred && !msg.revealed" class="absolute top-1 right-2 text-xs opacity-70">🔒</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="p-4 bg-white border-t border-gray-100">
              <div class="flex items-center gap-2 mb-2" v-if="activeChannel">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 hover:text-verso-blue transition">
                  <input
                    type="checkbox"
                    v-model="isBlurEnabled"
                    class="w-4 h-4 rounded border-gray-300 text-verso-blue focus:ring-verso-blue"
                  />
                  <span>Send as blurred message</span>
                </label>
              </div>
              <div
                class="bg-gray-50 border border-gray-200 rounded-xl flex items-center px-4 py-2 gap-3 focus-within:ring-2 focus-within:ring-verso-blue/20 focus-within:border-verso-blue transition"
                :class="{ 'opacity-50 pointer-events-none': !activeChannel }"
              >
                <input
                  v-model="newMessage"
                  type="text"
                  :placeholder="
                    activeChannel
                      ? `Message #${currentChannelName}...`
                      : 'Select a channel to chat'
                  "
                  class="flex-1 bg-transparent border-none focus:outline-none text-verso-dark placeholder-gray-400 py-2"
                  @keyup.enter="sendMessage"
                  :disabled="!activeChannel"
                />

                <button
                  @click="sendMessage"
                  class="bg-verso-blue text-white p-2 rounded-lg hover:opacity-90 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                  :disabled="!newMessage.trim() || !activeChannel"
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

    <!-- Create Channel Modal -->
    <div
      v-if="showCreateModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="showCreateModal = false"
    >
      <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl">
        <h3 class="text-xl font-bold text-verso-dark mb-4">Create New Channel</h3>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Channel Name</label>
            <input
              v-model="createForm.name"
              type="text"
              placeholder="e.g. Fantasy Lovers"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verso-blue/20 focus:border-verso-blue"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="createForm.description"
              placeholder="What is this channel about?"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verso-blue/20 focus:border-verso-blue"
            ></textarea>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Room Code</label>
            <input
              v-model="createForm.room_code"
              type="text"
              placeholder="e.g. FANTASY-2026"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verso-blue/20 focus:border-verso-blue"
            />
            <p class="text-xs text-gray-500 mt-1">Others will use this code to join your channel</p>
          </div>
        </div>

        <div v-if="createError" class="mt-4 p-3 bg-red-50 text-red-600 rounded-lg text-sm">
          {{ createError }}
        </div>

        <div class="flex gap-3 mt-6">
          <button
            @click="showCreateModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
          >
            Cancel
          </button>
          <button
            @click="createChannel"
            :disabled="!createForm.name || !createForm.room_code"
            class="flex-1 px-4 py-2 bg-verso-blue text-white rounded-lg hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Create Channel
          </button>
        </div>
      </div>
    </div>

    <!-- Join Channel Modal -->
    <div
      v-if="showJoinModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="showJoinModal = false"
    >
      <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl">
        <h3 class="text-xl font-bold text-verso-dark mb-4">Join Channel</h3>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Room Code</label>
            <input
              v-model="joinForm.room_code"
              type="text"
              placeholder="Enter the room code"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verso-blue/20 focus:border-verso-blue"
            />
          </div>
        </div>

        <div v-if="joinError" class="mt-4 p-3 bg-red-50 text-red-600 rounded-lg text-sm">
          {{ joinError }}
        </div>

        <div class="flex gap-3 mt-6">
          <button
            @click="showJoinModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
          >
            Cancel
          </button>
          <button
            @click="joinChannel"
            :disabled="!joinForm.room_code"
            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Join Channel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick, watch, computed } from "vue";
import Sidebar from "@/components/layout/Sidebar.vue";
import Navbar from "@/components/layout/Navbar.vue";
import BottomNav from "@/components/layout/BottomNav.vue";
import { Send, Plus, UserPlus } from "lucide-vue-next";
import api from "@/services/api";
import createEcho from "@/services/echo";

// State
const activeChannel = ref(null);
const channels = ref([]);
const messages = ref([]);
const newMessage = ref("");
const isBlurEnabled = ref(false);
// Use ref for currentUser so we can update it after fetching fresh data
const currentUser = ref(JSON.parse(localStorage.getItem("user") || "{}"));
const messagesContainer = ref(null);

// Modal states
const showCreateModal = ref(false);
const showJoinModal = ref(false);
const createForm = ref({
  name: "",
  description: "",
  room_code: "",
});
const joinForm = ref({
  room_code: "",
});
const createError = ref("");
const joinError = ref("");

let echoInstance = null;

const currentChannelName = computed(() => {
  return (
    channels.value.find((c) => c.id === activeChannel.value)?.name ||
    "Select a Channel"
  );
});

const currentChannelDescription = computed(() => {
  return channels.value.find((c) => c.id === activeChannel.value)?.description || "";
});

// Lifecycle
onMounted(async () => {
  // Fetch fresh user data to ensure ID and image are correct
  try {
    const { data } = await api.get("/profile");
    currentUser.value = data;
    localStorage.setItem("user", JSON.stringify(data));
  } catch (e) {
    console.error("Failed to fetch current user info", e);
  }

  await fetchGroups();
  echoInstance = createEcho();

  if (channels.value.length > 0) {
    activeChannel.value = channels.value[0].id;
  }
});

onUnmounted(() => {
  if (echoInstance) {
    echoInstance.disconnect();
  }
});

// Watchers
watch(activeChannel, (newId, oldId) => {
  if (oldId && echoInstance) {
    echoInstance.leave(`community.${oldId}`);
  }

  if (newId) {
    messages.value = [];
    fetchMessages(newId);
    subscribeToChannel(newId);
  }
});

// Helper to construct full image URL
const getAvatarUrl = (path) => {
  if (!path) return "https://placehold.co/100x100";
  if (path.startsWith("http")) return path;
  return `http://localhost:8000/storage/${path}`;
};

// API Actions
const fetchGroups = async () => {
  try {
    const response = await api.get("/community/groups");
    channels.value = response.data.map((g) => ({
      id: g.group_id,
      name: g.name,
      description: g.description,
      is_default: g.is_default,
      room_code: g.room_code,
    }));
  } catch (error) {
    console.error("Failed to fetch groups:", error);
  }
};

const createChannel = async () => {
  createError.value = "";
  
  try {
    const response = await api.post("/community/channels/create", createForm.value);
    const newChannel = response.data.channel;
    
    // Add to channels list
    channels.value.push({
      id: newChannel.group_id,
      name: newChannel.name,
      description: newChannel.description,
      is_default: newChannel.is_default,
      room_code: newChannel.room_code,
    });
    
    // Switch to the new channel
    activeChannel.value = newChannel.group_id;
    
    // Reset and close modal
    createForm.value = { name: "", description: "", room_code: "" };
    showCreateModal.value = false;
  } catch (error) {
    console.error("Failed to create channel:", error);
    createError.value = error.response?.data?.message || "Failed to create channel. Room code might already be in use.";
  }
};

const joinChannel = async () => {
  joinError.value = "";
  
  try {
    const response = await api.post("/community/channels/join", joinForm.value);
    const foundChannel = response.data.channel;
    
    // Check if already in list
    const exists = channels.value.find(c => c.id === foundChannel.group_id);
    
    if (!exists) {
      channels.value.push({
        id: foundChannel.group_id,
        name: foundChannel.name,
        description: foundChannel.description,
        is_default: foundChannel.is_default,
        room_code: foundChannel.room_code,
      });
    }
    
    // Switch to the channel
    activeChannel.value = foundChannel.group_id;
    
    // Reset and close modal
    joinForm.value = { room_code: "" };
    showJoinModal.value = false;
  } catch (error) {
    console.error("Failed to join channel:", error);
    joinError.value = error.response?.data?.message || "Channel not found with this room code.";
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
  if (!newMessage.value.trim() || !activeChannel.value) return;

  const tempText = newMessage.value;
  const blurFlag = isBlurEnabled.value;
  newMessage.value = "";
  isBlurEnabled.value = false;

  try {
    const response = await api.post(
      `/community/groups/${activeChannel.value}/messages`,
      {
        message: tempText,
        is_blurred: blurFlag,
      }
    );

    const returnedMsg = response.data.message;
    if (!messages.value.some((m) => m.id === returnedMsg.message_id)) {
      messages.value.push(transformMessage(returnedMsg));
      scrollToBottom();
    }
  } catch (error) {
    console.error("Failed to send message:", error);
    alert("Message failed to send.");
    newMessage.value = tempText;
  }
};

// Real-time
const subscribeToChannel = (groupId) => {
  if (!echoInstance) return;

  echoInstance.private(`community.${groupId}`).listen("MessageSent", (e) => {
    if (messages.value.some((m) => m.id === e.message_id)) return;

    const incomingMsg = {
      id: e.message_id,
      sender: e.sender.username,
      avatar: getAvatarUrl(e.sender.profile_image),
      text: e.message_body,
      time: formatTime(e.sent_at),
      isMe: e.sender.user_id == currentUser.value.user_id,
      isBlurred: e.is_blurred || false,
      revealed: false,
    };

    messages.value.push(incomingMsg);
    scrollToBottom();
  });
};

// Helpers
const transformMessage = (msg) => {
  return {
    id: msg.message_id,
    sender: msg.sender?.username || "Unknown",
    avatar: getAvatarUrl(msg.sender?.profile_image),
    text: msg.message_body,
    time: formatTime(msg.sent_at),
    isMe: msg.sender_id == currentUser.value.user_id,
    isBlurred: msg.is_blurred || false,
    revealed: false,
  };
};

const revealMessage = (msg) => {
  msg.revealed = true;
};

const formatTime = (isoString) => {
  if (!isoString) return "";
  const date = new Date(isoString);
  return date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
};

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
  });
};
</script>

<style scoped>
.blur-message {
  filter: blur(8px);
  user-select: none;
  transition: filter 0.3s ease;
}

.blur-message:hover {
  filter: blur(6px);
}
</style>

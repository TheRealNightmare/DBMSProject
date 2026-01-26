<template>
  <div class="relative" ref="panelContainer">
    <!-- Notification Button -->
    <button
      @click="togglePanel"
      class="relative p-2 text-gray-500 hover:text-verso-dark hover:bg-white/50 rounded-full transition-all duration-200 group"
    >
      <Bell :class="unreadCount > 0 ? 'animate-wiggle' : ''" class="w-5 h-5 transition-transform group-hover:scale-110" />
      <span
        v-if="unreadCount > 0"
        class="absolute top-1 right-1 flex items-center justify-center min-w-[18px] h-[18px] text-[10px] font-bold text-white bg-gradient-to-br from-red-500 to-red-600 rounded-full border-2 border-verso-cream shadow-lg animate-pulse"
      >
        {{ unreadCount > 9 ? '9+' : unreadCount }}
      </span>
    </button>

    <!-- Notification Dropdown Panel -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 translate-y-1"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-1"
    >
      <div
        v-if="showPanel"
        class="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden ring-1 ring-black/5 z-50"
      >
        <!-- Header -->
        <div class="flex justify-between items-center px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-verso-blue/5 to-blue-50/50">
          <div class="flex items-center gap-2">
            <Bell class="w-5 h-5 text-verso-blue" />
            <h3 class="text-base font-bold text-verso-dark">Notifications</h3>
            <span v-if="unreadCount > 0" class="px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">
              {{ unreadCount }}
            </span>
          </div>
          <button
            v-if="unreadCount > 0"
            @click="markAllRead"
            class="text-xs text-verso-blue hover:text-blue-700 font-semibold transition-colors"
          >
            Mark all read
          </button>
        </div>

        <!-- Notifications List -->
        <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
          <!-- Loading State -->
          <div v-if="loading" class="p-8 flex justify-center">
            <div class="animate-spin h-8 w-8 border-3 border-verso-blue rounded-full border-t-transparent"></div>
          </div>

          <!-- Empty State -->
          <div
            v-else-if="notifications.length === 0"
            class="p-8 text-center flex flex-col items-center justify-center"
          >
            <div class="bg-gray-50 p-4 rounded-full mb-3">
              <Bell class="h-8 w-8 text-gray-300" />
            </div>
            <p class="text-gray-500 text-sm font-medium">No notifications yet</p>
            <p class="text-gray-400 text-xs mt-1">We'll notify you when something arrives</p>
          </div>

          <!-- Notification Items -->
          <div v-else>
            <div
              v-for="notification in notifications"
              :key="notification.id"
              @click="markAsRead(notification.id)"
              class="p-4 border-b border-gray-50 hover:bg-gray-50 cursor-pointer transition-colors group"
              :class="{ 'bg-blue-50/30': !notification.is_read }"
            >
              <div class="flex items-start gap-3">
                <!-- Icon based on type -->
                <div
                  class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center transition-colors"
                  :class="{
                    'bg-blue-100 text-blue-600 group-hover:bg-blue-200': notification.type === 'read_reminder',
                    'bg-green-100 text-green-600 group-hover:bg-green-200': notification.type === 'explore_books',
                    'bg-purple-100 text-purple-600 group-hover:bg-purple-200': notification.type === 'connect_people',
                  }"
                >
                  <BookOpen v-if="notification.type === 'read_reminder'" class="w-5 h-5" />
                  <Search v-else-if="notification.type === 'explore_books'" class="w-5 h-5" />
                  <Users v-else-if="notification.type === 'connect_people'" class="w-5 h-5" />
                  <Bell v-else class="w-5 h-5" />
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-2 mb-1">
                    <h4 class="text-sm font-bold text-verso-dark leading-tight">
                      {{ notification.title }}
                    </h4>
                    <div
                      v-if="!notification.is_read"
                      class="flex-shrink-0 w-2 h-2 bg-verso-blue rounded-full mt-1 animate-pulse"
                    ></div>
                  </div>
                  <p class="text-xs text-gray-600 leading-relaxed mb-2">
                    {{ notification.message }}
                  </p>
                  <span class="text-[10px] text-gray-400 font-medium">
                    {{ formatTime(notification.created_at) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
          <button
            @click="sendTestNotification"
            class="w-full text-xs text-verso-blue hover:text-blue-700 font-semibold transition-colors py-2 hover:bg-white rounded-lg"
          >
            Send Test Notification
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { Bell, BookOpen, Search, Users } from 'lucide-vue-next';
import api from '@/services/api';

const showPanel = ref(false);
const notifications = ref([]);
const loading = ref(false);
const panelContainer = ref(null);

const unreadCount = computed(() => {
  return notifications.value.filter(n => !n.is_read).length;
});

const togglePanel = async () => {
  showPanel.value = !showPanel.value;
  if (showPanel.value && notifications.value.length === 0) {
    await fetchNotifications();
  }
};

const fetchNotifications = async () => {
  loading.value = true;
  try {
    const response = await api.get('/notifications');
    notifications.value = response.data.notifications;
  } catch (error) {
    console.error('Error fetching notifications:', error);
  } finally {
    loading.value = false;
  }
};

const markAsRead = async (id) => {
  const notification = notifications.value.find(n => n.id === id);
  if (!notification || notification.is_read) return;

  try {
    await api.post(`/notifications/${id}/read`);
    notification.is_read = true;
  } catch (error) {
    console.error('Error marking notification as read:', error);
  }
};

const markAllRead = async () => {
  try {
    await api.post('/notifications/read-all');
    notifications.value.forEach(n => n.is_read = true);
  } catch (error) {
    console.error('Error marking all as read:', error);
  }
};

const sendTestNotification = async () => {
  try {
    await api.post('/notifications/send');
    await fetchNotifications();
  } catch (error) {
    console.error('Error sending test notification:', error);
  }
};

const formatTime = (timestamp) => {
  const date = new Date(timestamp);
  const now = new Date();
  const diff = now - date;
  const seconds = Math.floor(diff / 1000);
  const minutes = Math.floor(seconds / 60);
  const hours = Math.floor(minutes / 60);
  const days = Math.floor(hours / 24);

  if (seconds < 60) return 'Just now';
  if (minutes < 60) return `${minutes}m ago`;
  if (hours < 24) return `${hours}h ago`;
  if (days < 7) return `${days}d ago`;
  return date.toLocaleDateString();
};

// Auto-refresh notifications every 30 seconds
let refreshInterval;
onMounted(() => {
  fetchNotifications();
  refreshInterval = setInterval(fetchNotifications, 30000);
  
  // Add click outside listener
  document.addEventListener('click', handleClickOutside);
});

// Cleanup on unmount
import { onUnmounted } from 'vue';
onUnmounted(() => {
  if (refreshInterval) {
    clearInterval(refreshInterval);
  }
  document.removeEventListener('click', handleClickOutside);
});

// Handle click outside
const handleClickOutside = (event) => {
  if (panelContainer.value && !panelContainer.value.contains(event.target)) {
    showPanel.value = false;
  }
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e0;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #a0aec0;
}

@keyframes wiggle {
  0%, 100% { transform: rotate(0deg); }
  25% { transform: rotate(-10deg); }
  75% { transform: rotate(10deg); }
}

.animate-wiggle {
  animation: wiggle 0.5s ease-in-out infinite;
}
</style>

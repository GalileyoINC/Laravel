<template>
  <div class="chat-widget">
    <!-- Chat Button -->
    <button 
      @click="toggleChat"
      class="chat-button"
      :class="{ unread: hasUnreadMessages }"
    >
      <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
      <span v-if="unreadCount > 0" class="badge">{{ unreadCount }}</span>
    </button>

    <!-- Chat Window -->
    <div v-if="isOpen" class="chat-window-popup">
      <div class="chat-header">
        <h3>Chat</h3>
        <button @click="toggleChat" class="close-btn">×</button>
      </div>
      
      <div class="chat-body">
        <div class="chat-list-container">
          <ChatList 
            @select="handleSelectConversation"
          />
        </div>
        <div class="chat-window-container">
          <ChatWindow 
            :conversation-id="selectedConversationId"
            :current-user-id="currentUserId"
            @back="selectedConversationId = null"
            @select-conversation="handleSelectConversation"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ChatList from './ChatList.vue'
import ChatWindow from './ChatWindow.vue'

const isOpen = ref(false)
const hasUnreadMessages = ref(false)
const unreadCount = ref(0)
const selectedConversationId = ref(null)
const currentUserId = ref(1) // TODO: Get from auth

const toggleChat = () => {
  isOpen.value = !isOpen.value
}

const handleSelectConversation = (id) => {
  selectedConversationId.value = id
}
</script>

<style scoped>
.chat-widget {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 1000;
}

.chat-button {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  color: white;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.2s;
  position: relative;
}

.chat-button:hover {
  transform: scale(1.1);
}

.chat-button svg {
  width: 24px;
  height: 24px;
}

.badge {
  position: absolute;
  top: 5px;
  right: 5px;
  background: #ef4444;
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 600;
}

.chat-window-popup {
  position: absolute;
  bottom: 80px;
  right: 0;
  width: 750px;
  height: 650px;
  background: white;
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  border: 1px solid #e5e7eb;
  z-index: 1001;
}

.chat-header {
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e5e7eb;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.chat-header h3 {
  margin: 0;
  font-size: 1.125rem;
}

.close-btn {
  background: none;
  border: none;
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-btn:hover {
  opacity: 0.8;
}

.chat-body {
  flex: 1;
  overflow: hidden;
  display: flex;
  min-height: 0;
}

.chat-list-container {
  flex: 0 0 370px;
  min-height: 0;
  display: flex;
  overflow: hidden;
  border-right: 1px solid #e5e7eb;
}

.chat-window-container {
  flex: 1;
  min-height: 0;
  display: flex;
  overflow: hidden;
}
</style>


<template>
  <div class="chat-container">
    <ChatList 
      @select="handleSelectConversation"
      @create-group="handleCreateGroup"
    />
    <ChatWindow 
      :conversation-id="selectedConversationId"
      :current-user-id="currentUserId"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ChatList from './ChatList.vue'
import ChatWindow from './ChatWindow.vue'

const selectedConversationId = ref(null)

// Get current user ID from auth
const currentUserId = ref(1) // TODO: Get from auth

const handleSelectConversation = (id) => {
  selectedConversationId.value = id
}

const handleCreateGroup = () => {
  // TODO: Implement group creation modal
  console.log('Create group clicked')
}
</script>

<style scoped>
.chat-container {
  display: flex;
  height: 100vh;
  max-width: 1200px;
  margin: 0 auto;
}
</style>


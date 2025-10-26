<template>
  <div class="chat-list">
    <div class="conversations">
      <div v-if="loading" class="loading-state">
        <div class="loading-spinner"></div>
        <p>Loading conversations...</p>
      </div>
      
      <div v-else-if="conversations.length === 0" class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="empty-icon">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <p>No conversations yet</p>
        <p class="empty-hint">Start a chat to get in touch with others</p>
      </div>
      
      <div 
        v-else
        v-for="conversation in conversations" 
        :key="conversation.id"
        @click="selectConversation(conversation.id)"
        class="conversation-item"
        :class="{ active: selectedId === conversation.id }"
      >
        <div class="avatar">
          <div class="avatar-circle">
            {{ getInitials(conversation) }}
          </div>
        </div>
        <div class="info">
          <div class="name">{{ getConversationName(conversation) }}</div>
          <div class="last-message">{{ conversation.last_message || 'No messages yet' }}</div>
        </div>
        <div class="meta">
          <div class="unread" v-if="conversation.unread_count > 0">
            {{ conversation.unread_count }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const conversations = ref([])
const selectedId = ref(null)
const loading = ref(false)

const getInitials = (conversation) => {
  if (conversation.users && conversation.users.length > 0) {
    const user = conversation.users[0]
    return `${user.first_name?.[0] || ''}${user.last_name?.[0] || ''}`.toUpperCase()
  }
  return '?'
}

const getConversationName = (conversation) => {
  if (conversation.users && conversation.users.length > 0) {
    const user = conversation.users[0]
    return `${user.first_name} ${user.last_name}`
  }
  return 'Unknown'
}

const selectConversation = (id) => {
  selectedId.value = id
  emit('select', id)
}

const emit = defineEmits(['select'])

const fetchConversations = async () => {
  loading.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const response = await fetch('/api/v1/chat/list', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        page: 1,
        per_page: 50,
      }),
    })
    
    if (!response.ok) {
      throw new Error('Failed to fetch conversations')
    }
    
    const data = await response.json()
    conversations.value = data.data || []
  } catch (error) {
    console.error('Error fetching conversations:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchConversations()
})
</script>

<style scoped>
.chat-list {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  background: white;
}

.conversations {
  flex: 1;
  overflow-y: auto;
  min-height: 0;
}

.loading-state,
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  padding: 2rem;
  color: #6b7280;
  text-align: center;
}

.empty-icon {
  width: 64px;
  height: 64px;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.empty-state p {
  margin: 0.5rem 0;
}

.empty-hint {
  font-size: 0.875rem;
  opacity: 0.7;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid #e5e7eb;
  border-top-color: #3b82f6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.conversation-item {
  display: flex;
  padding: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
  border-bottom: 1px solid #f3f4f6;
}

.conversation-item:hover {
  background-color: #f9fafb;
}

.conversation-item.active {
  background-color: #eff6ff;
  border-left: 3px solid #3b82f6;
}

.avatar-circle {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: #3b82f6;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 0.875rem;
}

.info {
  flex: 1;
  margin-left: 0.75rem;
}

.name {
  font-weight: 600;
  color: #111827;
}

.last-message {
  font-size: 0.875rem;
  color: #6b7280;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.meta {
  display: flex;
  align-items: center;
}

.unread {
  background: #3b82f6;
  color: white;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 600;
}
</style>


<template>
  <div class="chat-window">
    <div class="header">
      <button @click="$emit('back')" class="back-btn">← Back</button>
      <h3>{{ conversationName }}</h3>
    </div>

    <div class="chat-content">
      <div class="messages" ref="messagesContainer">
        <div 
          v-for="message in messages" 
          :key="message.id"
          class="message-item"
          :class="{ 'own-message': message.id_user === currentUserId }"
        >
          <div class="message-bubble">
            <div class="message-text">{{ message.message }}</div>
            <div class="message-time">{{ formatTime(message.created_at) }}</div>
          </div>
        </div>
        <div v-if="messages.length === 0" class="empty-state">
          No messages yet. Start the conversation!
        </div>
      </div>

      <div class="input-area">
        <textarea
          ref="messageInput"
          v-model="newMessage"
          @keydown.enter.prevent="handleEnter"
          @click="focusInput"
          @focus="focusInput"
          placeholder="Type a message..."
          rows="2"
          class="message-input"
        ></textarea>
        <button 
          @click="sendMessage"
          :disabled="!newMessage.trim()"
          class="send-button"
        >
          Send
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick, onMounted, defineEmits } from 'vue'

const emit = defineEmits(['back'])

const props = defineProps({
  conversationId: {
    type: Number,
    default: null,
  },
  currentUserId: {
    type: Number,
    required: true,
  },
})

const messages = ref([])
const newMessage = ref('')
const conversationName = ref('Chat')
const messagesContainer = ref(null)
const messageInput = ref(null)
const loading = ref(false)

const fetchMessages = async () => {
  if (!props.conversationId) {
    messages.value = []
    return
  }

  loading.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const response = await fetch('/api/v1/chat/chat-messages', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        conversation_id: props.conversationId,
        page: 1,
        per_page: 50,
      }),
    })

    if (!response.ok) {
      throw new Error('Failed to fetch messages')
    }

    const data = await response.json()
    messages.value = data.data || []
    
    nextTick(() => {
      scrollToBottom()
    })
  } catch (error) {
    console.error('Error fetching messages:', error)
  } finally {
    loading.value = false
  }
}

const focusInput = () => {
  if (messageInput.value) {
    messageInput.value.focus()
  }
}

const sendMessage = async () => {
  if (!newMessage.value.trim() || !props.conversationId) {
    return
  }

  try {
    const token = localStorage.getItem('auth_token')
    const response = await fetch('/api/v1/chat/send', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        conversation_id: props.conversationId,
        message: newMessage.value,
      }),
    })

    if (!response.ok) {
      throw new Error('Failed to send message')
    }

    const data = await response.json()
    messages.value.unshift(data.data)
    newMessage.value = ''
    
    nextTick(() => {
      scrollToBottom()
    })
  } catch (error) {
    console.error('Error sending message:', error)
  }
}

const handleEnter = (event) => {
  if (event.shiftKey) {
    return // Allow new line
  }
  sendMessage()
}

const formatTime = (timestamp) => {
  const date = new Date(timestamp)
  return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
}

const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
  }
}

watch(() => props.conversationId, () => {
  fetchMessages()
  nextTick(() => {
    focusInput()
  })
})

watch(messages, () => {
  nextTick(() => {
    scrollToBottom()
  })
})

onMounted(() => {
  nextTick(() => {
    focusInput()
  })
})
</script>

<style scoped>
.chat-window {
  height: 100%;
  display: flex;
  flex-direction: column;
  background: #f9fafb;
}

.chat-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.empty-state {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #6b7280;
  font-size: 0.875rem;
}

.header {
  padding: 1rem;
  background: white;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.back-btn {
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
  font-size: 0.875rem;
  padding: 0.5rem;
  transition: color 0.2s;
}

.back-btn:hover {
  color: #3b82f6;
}

.messages {
  flex: 1;
  overflow-y: auto;
  padding: 1rem;
}

.message-item {
  margin-bottom: 1rem;
  display: flex;
}

.message-item.own-message {
  justify-content: flex-end;
}

.message-bubble {
  max-width: 70%;
  padding: 0.75rem 1rem;
  background: white;
  border-radius: 0.75rem;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.message-item.own-message .message-bubble {
  background: #3b82f6;
  color: white;
}

.message-text {
  margin-bottom: 0.25rem;
  word-wrap: break-word;
}

.message-time {
  font-size: 0.75rem;
  opacity: 0.7;
}

.input-area {
  padding: 1rem;
  background: white;
  border-top: 1px solid #e5e7eb;
  display: flex;
  gap: 0.5rem;
}

.message-input {
  flex: 1;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  padding: 0.75rem;
  resize: none;
  cursor: text;
  outline: none;
  font-size: 0.875rem;
}

.message-input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.send-button {
  padding: 0.5rem 1rem;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
  font-weight: 600;
}

.send-button:hover:not(:disabled) {
  background: #2563eb;
}

.send-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>


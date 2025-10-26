<template>
  <div class="chat-window">
    <div class="header">
      <button v-if="conversationId" @click="$emit('back')" class="back-btn">← Back</button>
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
        <div v-if="messages.length === 0 && conversationId" class="empty-state">
          No messages yet. Start the conversation!
        </div>
        <div v-else-if="!conversationId" class="empty-state">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="empty-icon">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          <p>Select a conversation from the list to start chatting</p>
          <p class="empty-hint">You can type below, but need to select a conversation to send</p>
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

const emit = defineEmits(['back', 'select-conversation'])

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
  nextTick(() => {
    if (messageInput.value) {
      messageInput.value.focus()
    }
  })
}

const createOrGetConversation = async () => {
  try {
    const token = localStorage.getItem('auth_token')
    
    // Try to find or create a conversation with support/admin (user ID 1 for now)
    // You can modify this to get the support user ID from API
    const response = await fetch('/api/v1/chat/get-friend-chat', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        friend_id: 1, // Support/admin user ID - modify as needed
      }),
    })

    if (!response.ok) {
      throw new Error('Failed to create conversation')
    }

    const data = await response.json()
    return data.data.id
  } catch (error) {
    console.error('Error creating conversation:', error)
    throw error
  }
}

const sendMessage = async () => {
  if (!newMessage.value.trim()) {
    return
  }

  let conversationIdToUse = props.conversationId
  let shouldReloadMessages = false

  // If no conversation selected, create one automatically
  if (!conversationIdToUse) {
    try {
      conversationIdToUse = await createOrGetConversation()
      
      // Emit the new conversation ID to parent
      emit('select-conversation', conversationIdToUse)
      
      // Mark that we need to reload messages
      shouldReloadMessages = true
    } catch (error) {
      alert('Failed to create conversation. Please try again.')
      return
    }
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
        conversation_id: conversationIdToUse,
        message: newMessage.value,
      }),
    })

    if (!response.ok) {
      throw new Error('Failed to send message')
    }

    const data = await response.json()
    
    // If we just created a conversation, reload messages after prop update
    if (shouldReloadMessages) {
      // Wait for the parent to update the conversationId prop
      await nextTick()
      await new Promise(resolve => setTimeout(resolve, 100)) // Small delay for prop update
      if (props.conversationId) {
        await fetchMessages()
      } else {
        // If still no conversationId, just show the sent message
        messages.value.unshift(data.data)
      }
    } else {
      messages.value.unshift(data.data)
    }
    
    newMessage.value = ''
    
    nextTick(() => {
      scrollToBottom()
    })
  } catch (error) {
    console.error('Error sending message:', error)
    alert('Failed to send message. Please try again.')
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

// Real-time socket listening
let channel = null

watch(() => props.conversationId, (newId, oldId) => {
  // Leave old channel
  if (oldId && channel && window.Echo) {
    window.Echo.leave(`chat.${oldId}`)
    channel = null
  }
  
  // Join new channel if Echo is available
  if (newId && window.Echo) {
    console.log(`📡 Joining chat.${newId}`)
    channel = window.Echo.private(`chat.${newId}`)
    
    // Listen for new messages
    channel.listen('.message.sent', (data) => {
      console.log('📩 New message received via socket:', data)
      messages.value.push(data)
      nextTick(() => {
        scrollToBottom()
      })
    })
    
    console.log(`✅ Listening to chat.${newId}`)
  }
  
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
  if (props.conversationId) {
    fetchMessages()
  }
  nextTick(() => {
    focusInput()
  })
})
</script>

<style scoped>
.chat-window {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  background: #f9fafb;
  min-height: 0;
}

.chat-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  min-height: 0;
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #6b7280;
  font-size: 0.875rem;
}

.empty-state svg.empty-icon {
  width: 48px;
  height: 48px;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.empty-state p {
  margin: 0.5rem 0;
  font-size: 0.875rem;
}

.empty-state .empty-hint {
  font-size: 0.75rem;
  opacity: 0.6;
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
  min-height: 0;
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
  flex-shrink: 0;
  position: relative;
  z-index: 10;
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
  font-family: inherit;
  background: white;
  pointer-events: auto;
  z-index: 1;
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


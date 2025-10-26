<!-- Admin Chat Widget (Bottom Right Corner) -->
<div id="adminChatWidget" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    <!-- Chat Button -->
    <button 
        id="adminChatButton" 
        onclick="toggleAdminChat()"
        style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; cursor: pointer; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; align-items: center; justify-content: center; transition: transform 0.2s; position: relative;"
        class="chat-button-pulse"
    >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <span id="adminChatBadge" style="position: absolute; top: 5px; right: 5px; background: #ef4444; color: white; border-radius: 50%; width: 20px; height: 20px; display: none; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;">0</span>
    </button>

    <!-- Chat Window Popup -->
    <div id="adminChatWindow" style="display: none; position: absolute; bottom: 80px; right: 0; width: 380px; height: 650px; background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); flex-direction: column; overflow: hidden; border: 1px solid #e5e7eb;">
        <div style="padding: 1rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h3 style="margin: 0; font-size: 1.125rem;">Live Chat</h3>
            <button onclick="toggleAdminChat()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">×</button>
        </div>
        
        <div id="adminChatMessages" style="flex: 1; overflow-y: auto; padding: 1rem;">
            <p class="text-muted">Listening for new messages...</p>
        </div>
        
        <div id="adminChatInputArea" style="padding: 1rem; background: white; border-top: 1px solid #e5e7eb; display: none;">
            <textarea id="adminChatReply" style="width: 100%; min-height: 60px; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem; resize: none; font-size: 0.875rem;" placeholder="Type your reply..."></textarea>
            <button onclick="sendAdminReply()" style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-weight: 600; width: 100%;">Send Reply</button>
        </div>
    </div>
</div>

<script>
let unreadCount = 0;
let adminChatOpen = false;

function toggleAdminChat() {
    const chatWindow = document.getElementById('adminChatWindow');
    const chatInput = document.getElementById('adminChatInputArea');
    
    adminChatOpen = !adminChatOpen;
    
    if (adminChatOpen) {
        chatWindow.style.display = 'flex';
        chatInput.style.display = 'block';
        // Load messages from database when opened
        loadAdminMessages();
        // Clear badge when opened
        updateBadge(0);
    } else {
        chatWindow.style.display = 'none';
        chatInput.style.display = 'none';
    }
}

function loadAdminMessages() {
    // Load recent messages from database
    fetch('/admin/chat/recent-messages')
        .then(response => response.json())
        .then(data => {
            const messagesContainer = document.getElementById('adminChatMessages');
            
            if (data.data && data.data.length > 0) {
                messagesContainer.innerHTML = '';
                data.data.forEach(message => {
                    addMessageToWindow(message);
                });
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            } else {
                messagesContainer.innerHTML = '<p class="text-muted">No messages yet</p>';
            }
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
}

function updateBadge(count) {
    const badge = document.getElementById('adminChatBadge');
    unreadCount = count;
    
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}

function sendAdminReply() {
    const message = document.getElementById('adminChatReply').value;
    if (!message.trim()) {
        return;
    }
    
    // Send via API
    fetch('/api/v1/chat/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            conversation_id: window.currentConversationId || 1,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('adminChatReply').value = '';
            // Message will arrive via socket
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
    });
}

// Initialize Reverb connection when page loads
document.addEventListener('DOMContentLoaded', function() {
    if (window.Echo) {
        console.log('✅ Admin Chat Widget initialized');
        
        // Listen for new messages from any conversation
        window.Echo.private('admin.live-chat')
            .listen('.new.message', (data) => {
                console.log('📩 New message received:', data);
                
                // Show notification
                showNotification(data);
                
                // Update badge
                updateBadge(unreadCount + 1);
                
                // Add to chat window if open
                if (adminChatOpen) {
                    addMessageToWindow(data);
                }
                
                // Add pulsing animation
                document.getElementById('adminChatButton').classList.add('animate-pulse');
                setTimeout(() => {
                    document.getElementById('adminChatButton').classList.remove('animate-pulse');
                }, 2000);
            });
    }
});

function showNotification(data) {
    // Show toast notification
    const notification = document.createElement('div');
    notification.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #3b82f6; color: white; padding: 1rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); z-index: 10000; max-width: 300px;';
    notification.innerHTML = `<strong>New Message</strong><br>${data.message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function addMessageToWindow(data) {
    const messagesContainer = document.getElementById('adminChatMessages');
    
    if (messagesContainer.innerHTML === '<p class="text-muted">Listening for new messages...</p>') {
        messagesContainer.innerHTML = '';
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.style.cssText = 'margin-bottom: 1rem; padding: 0.75rem; background: #f5f5f5; border-radius: 8px;';
    messageDiv.innerHTML = `
        <div style="font-weight: 600; margin-bottom: 0.25rem;">User #${data.id_user}</div>
        <div>${data.message}</div>
        <small class="text-muted">${new Date(data.created_at).toLocaleString()}</small>
    `;
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Add CSS for pulsing animation
const style = document.createElement('style');
style.textContent = `
    .chat-button-pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    .text-muted {
        color: #6b7280;
    }
`;
document.head.appendChild(style);
</script>

<style scoped>
.chat-button-pulse:hover {
    transform: scale(1.1);
}
</style>


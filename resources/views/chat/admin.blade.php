@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Chat Messages</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <!-- Conversations List -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Conversations</h3>
                    </div>
                    <div class="box-body">
                        <div class="list-group" id="conversations-list">
                            @foreach($conversations as $conversation)
                            <a href="#" class="list-group-item conversation-item" 
                               data-conversation-id="{{ $conversation->id }}" 
                               onclick="loadConversation({{ $conversation->id }})">
                                <h4 class="list-group-item-heading">
                                    Conversation #{{ $conversation->id }}
                                </h4>
                                <p class="list-group-item-text">
                                    {{ $conversation->users->count() }} participants
                                </p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Messages -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Messages</h3>
                    </div>
                    <div class="box-body">
                        <div id="messages-container" style="max-height: 400px; overflow-y: auto;">
                            <p class="text-muted">Select a conversation to view messages</p>
                        </div>
                    </div>
                </div>

                <!-- Reply Form -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Reply</h3>
                    </div>
                    <form id="reply-form" onsubmit="sendMessage(event)">
                        <div class="box-body">
                            <div class="form-group">
                                <textarea id="message-input" class="form-control" rows="3" 
                                          placeholder="Type your reply..." required></textarea>
                            </div>
                            <input type="hidden" id="current-conversation-id" value="">
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Send Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
let currentConversationId = null;
let messageRefreshInterval = null;

function loadConversation(conversationId) {
    currentConversationId = conversationId;
    document.getElementById('current-conversation-id').value = conversationId;
    
    // Clear previous interval
    if (messageRefreshInterval) {
        clearInterval(messageRefreshInterval);
    }
    
    // Load messages immediately
    fetchMessages();
    
    // Auto-refresh every 5 seconds
    messageRefreshInterval = setInterval(fetchMessages, 5000);
}

function fetchMessages() {
    if (!currentConversationId) {
        return;
    }
    
    const container = document.getElementById('messages-container');
    
    // Use direct AJAX with CSRF token
    $.ajax({
        url: '/admin/chat/messages/' + currentConversationId,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(data) {
            container.innerHTML = '';
            
            if (data.data && data.data.length > 0) {
                // Reverse to show oldest first
                const messages = [...data.data].reverse();
                
                messages.forEach(message => {
                    const msgDiv = document.createElement('div');
                    msgDiv.className = 'well';
                    msgDiv.innerHTML = `
                        <div style="margin-bottom: 5px;">
                            <strong>User ID: ${message.id_user}</strong>
                            ${message.user ? `<br><small>${message.user.first_name} ${message.user.last_name}</small>` : ''}
                        </div>
                        <p style="margin-bottom: 5px;">${message.message}</p>
                        <small class="text-muted">${new Date(message.created_at).toLocaleString()}</small>
                    `;
                    container.appendChild(msgDiv);
                });
            } else {
                container.innerHTML = '<p class="text-muted">No messages yet</p>';
            }
            
            container.scrollTop = container.scrollHeight;
        },
        error: function(xhr, status, error) {
            console.error('Error loading messages:', error);
            container.innerHTML = '<p class="text-danger">Error loading messages</p>';
        }
    });
}

function sendMessage(event) {
    event.preventDefault();
    
    if (!currentConversationId) {
        alert('Please select a conversation');
        return;
    }
    
    const message = document.getElementById('message-input').value;
    if (!message.trim()) {
        return;
    }
    
    $.ajax({
        url: '/admin/chat/send',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        data: JSON.stringify({
            conversation_id: currentConversationId,
            message: message
        }),
        contentType: 'application/json',
        success: function(data) {
            $('#message-input').val('');
            fetchMessages();
        },
        error: function(xhr, status, error) {
            console.error('Error sending message:', error);
            alert('Error sending message');
        }
    });
}

// Clean up interval on page unload
window.addEventListener('beforeunload', function() {
    if (messageRefreshInterval) {
        clearInterval(messageRefreshInterval);
    }
});
</script>

<style>
.conversation-item:hover {
    background-color: #f0f0f0;
    cursor: pointer;
}
.conversation-item.active {
    background-color: #337ab7;
    color: white;
}
.well {
    margin-bottom: 10px;
    padding: 10px;
    background-color: #f5f5f5;
    border-radius: 4px;
}
</style>
@endsection


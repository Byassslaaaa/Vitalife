@extends('layouts.admin')

@section('judul-halaman', 'Chat Management')

@section('content')
<style>
/* Modern Chat Interface Styles */
.chat-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

/* Sidebar Styles */
.chat-sidebar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-right: 1px solid rgba(255, 255, 255, 0.2);
}

.sidebar-header {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sidebar-title::before {
    content: "💬";
    font-size: 1.5rem;
}

/* Filter Buttons */
.filter-container {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.filter-btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    backdrop-filter: blur(5px);
}

.filter-btn.active,
.filter-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Conversation Cards */
.conversations-container {
    padding: 1rem;
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

.conversations-container::-webkit-scrollbar {
    width: 6px;
}

.conversations-container::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.conversations-container::-webkit-scrollbar-thumb {
    background: rgba(79, 172, 254, 0.5);
    border-radius: 3px;
}

.conversation-card {
    background: white;
    border-radius: 15px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.conversation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #4facfe;
}

.conversation-card.selected {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
}

.conversation-header {
    display: flex;
    justify-content: between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.user-info {
    flex: 1;
}

.user-name {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
}

.status-active {
    background: #10b981;
    box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
}

.status-closed {
    background: #ef4444;
    box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
}

.conversation-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
}

.conversation-time {
    font-size: 0.75rem;
    opacity: 0.7;
    font-weight: 500;
}

.unread-badge {
    background: #ef4444;
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    min-width: 20px;
    text-align: center;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-weight: 600;
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.conversation-preview {
    font-size: 0.875rem;
    opacity: 0.8;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.conversation-card.selected .conversation-preview {
    opacity: 0.9;
}

.category-tag {
    display: inline-block;
    background: rgba(79, 172, 254, 0.1);
    color: #4facfe;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    border: 1px solid rgba(79, 172, 254, 0.2);
}

.conversation-card.selected .category-tag {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border-color: rgba(255, 255, 255, 0.3);
}

/* Chat Area */
.chat-area {
    background: white;
    display: flex;
    flex-direction: column;
}

.chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    position: relative;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.chat-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.chat-user-info h2 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chat-category {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-bottom: 0.25rem;
}

.chat-status {
    font-size: 0.75rem;
    opacity: 0.8;
}

.chat-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-close {
    background: #ef4444;
    color: white;
}

.btn-close:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
}

.btn-reopen {
    background: #10b981;
    color: white;
}

.btn-reopen:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
}

/* Messages Area */
.messages-container {
    flex: 1;
    padding: 1.5rem;
    overflow-y: auto;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 400px;
    max-height: calc(100vh - 300px);
}

.messages-container::-webkit-scrollbar {
    width: 6px;
}

.messages-container::-webkit-scrollbar-track {
    background: rgba(0,0,0,0.05);
    border-radius: 3px;
}

.messages-container::-webkit-scrollbar-thumb {
    background: rgba(79, 172, 254, 0.3);
    border-radius: 3px;
}

.message-wrapper {
    margin-bottom: 1rem;
    display: flex;
    align-items: flex-end;
    gap: 0.5rem;
}

.message-wrapper.user {
    justify-content: flex-start;
}

.message-wrapper.admin {
    justify-content: flex-end;
}

.message-wrapper.ai {
    justify-content: center;
}

.message-bubble {
    max-width: 70%;
    padding: 1rem 1.25rem;
    border-radius: 20px;
    position: relative;
    word-wrap: break-word;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.message-bubble.user {
    background: white;
    color: #374151;
    border-bottom-left-radius: 5px;
    border: 1px solid #e5e7eb;
}

.message-bubble.admin {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border-bottom-right-radius: 5px;
}

.message-bubble.ai {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 20px;
    border: 2px dashed rgba(255, 255, 255, 0.3);
}

.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.message-content {
    line-height: 1.5;
}

.message-status {
    font-size: 0.75rem;
    opacity: 0.8;
    margin-top: 0.5rem;
    font-style: italic;
}

/* Input Area */
.input-container {
    padding: 1.5rem;
    background: white;
    border-top: 1px solid #e5e7eb;
}

.input-form {
    display: flex;
    gap: 1rem;
    align-items: flex-end;
}

.message-input {
    flex: 1;
    border: 2px solid #e5e7eb;
    border-radius: 25px;
    padding: 1rem 1.5rem;
    font-size: 1rem;
    resize: none;
    min-height: 50px;
    max-height: 120px;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.message-input:focus {
    outline: none;
    border-color: #4facfe;
    background: white;
    box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
}

.send-btn {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.send-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
}

.send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.typing-indicator {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
    font-style: italic;
}

/* Loading States */
.loading-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
    color: #6b7280;
}

.loading-dots {
    display: inline-block;
}

.loading-dots::after {
    content: '...';
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0%, 20% { content: '.'; }
    40% { content: '..'; }
    60% { content: '...'; }
    80%, 100% { content: ''; }
}

/* Empty States */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    color: #6b7280;
    text-align: center;
}

.empty-state-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state-text {
    font-size: 1.125rem;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
    .chat-container {
        border-radius: 0;
        height: 100vh;
    }
    
    .conversation-card {
        padding: 0.75rem;
    }
    
    .chat-header {
        padding: 1rem;
    }
    
    .messages-container {
        padding: 1rem;
    }
    
    .input-container {
        padding: 1rem;
    }
    
    .message-bubble {
        max-width: 85%;
    }
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.slide-in {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from { transform: translateX(-20px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>

<div class="chat-container flex h-[calc(100vh-100px)]">
    <!-- Conversations Sidebar -->
    <div class="chat-sidebar w-1/3 flex flex-col">
        <div class="sidebar-header">
            <div class="sidebar-title">Conversations</div>
            <div class="filter-container">
                <button id="filter-all" class="filter-btn active">All</button>
                <button id="filter-active" class="filter-btn">Active</button>
                <button id="filter-closed" class="filter-btn">Closed</button>
            </div>
        </div>
        
        <div id="conversations-list" class="conversations-container flex-1">
            <div class="loading-container">
                <span class="loading-dots">Loading conversations</span>
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="chat-area w-2/3 flex flex-col">
        <!-- Chat Header -->
        <div id="chat-header" class="chat-header">
            <div class="chat-header-content">
                <div class="chat-user-info">
                    <h2 id="chat-user-name">Select a conversation</h2>
                    <div id="chat-category" class="chat-category"></div>
                    <div id="chat-status" class="chat-status"></div>
                </div>
                <div class="chat-actions">
                    <button id="close-conversation" class="action-btn btn-close hidden">Close</button>
                    <button id="reopen-conversation" class="action-btn btn-reopen hidden">Reopen</button>
                </div>
            </div>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" class="messages-container">
            <div class="empty-state">
                <div class="empty-state-icon">💬</div>
                <div class="empty-state-text">Select a conversation to view messages</div>
            </div>
        </div>

        <!-- Input Area -->
        <div id="chat-input-container" class="input-container">
            <form id="chat-form" class="input-form">
                <textarea 
                    id="chat-input" 
                    class="message-input" 
                    placeholder="Type your message..." 
                    rows="1"
                    disabled
                ></textarea>
                <button type="submit" id="send-button" class="send-btn" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
            <div id="typing-indicator" class="typing-indicator hidden">
                <span class="loading-dots">Admin is typing</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const conversationsList = document.getElementById('conversations-list');
    const chatMessages = document.getElementById('chat-messages');
    const chatUserName = document.getElementById('chat-user-name');
    const chatCategory = document.getElementById('chat-category');
    const chatStatus = document.getElementById('chat-status');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const closeConversationBtn = document.getElementById('close-conversation');
    const reopenConversationBtn = document.getElementById('reopen-conversation');
    const filterAllBtn = document.getElementById('filter-all');
    const filterActiveBtn = document.getElementById('filter-active');
    const filterClosedBtn = document.getElementById('filter-closed');
    const typingIndicator = document.getElementById('typing-indicator');

    // State
    let currentConversation = null;
    let conversations = [];
    let currentFilter = 'all';
    let refreshInterval;
    let messagePollingInterval;

    // Initialize
    loadConversations();
    refreshInterval = setInterval(loadConversations, 30000);

    // Event Listeners
    filterAllBtn.addEventListener('click', () => setFilter('all'));
    filterActiveBtn.addEventListener('click', () => setFilter('active'));
    filterClosedBtn.addEventListener('click', () => setFilter('closed'));

    closeConversationBtn.addEventListener('click', closeConversation);
    reopenConversationBtn.addEventListener('click', reopenConversation);
    chatForm.addEventListener('submit', sendMessage);

    // Auto-resize textarea
    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // Functions
    function updateFilterBtnStyle() {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        if (currentFilter === 'all') filterAllBtn.classList.add('active');
        if (currentFilter === 'active') filterActiveBtn.classList.add('active');
        if (currentFilter === 'closed') filterClosedBtn.classList.add('active');
    }

    function loadConversations() {
        fetch('/admin/chat/conversations', {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            conversations = data.conversations || [];
            renderConversations();
        })
        .catch(error => {
            console.error('Error loading conversations:', error);
            conversationsList.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">⚠️</div>
                    <div class="empty-state-text">Error loading conversations</div>
                </div>
            `;
        });
    }

    function loadConversation(conversationId) {
        if (messagePollingInterval) {
            clearInterval(messagePollingInterval);
        }

        fetch(`/admin/chat/conversations/${conversationId}`, {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            currentConversation = data.conversation;
            renderConversation();
            
            messagePollingInterval = setInterval(() => {
                if (currentConversation) {
                    loadConversation(currentConversation.id);
                }
            }, 5000);
        })
        .catch(error => {
            console.error('Error loading conversation:', error);
            chatMessages.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">⚠️</div>
                    <div class="empty-state-text">Error loading conversation</div>
                </div>
            `;
        });
    }

    function renderConversations() {
        if (!conversations || conversations.length === 0) {
            conversationsList.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">💬</div>
                    <div class="empty-state-text">No conversations found</div>
                </div>
            `;
            return;
        }

        conversationsList.innerHTML = '';
        conversations
            .filter(conversation => currentFilter === 'all' || conversation.status === currentFilter)
            .forEach((conversation, index) => {
                const conversationEl = document.createElement('div');
                conversationEl.className = `conversation-card fade-in${currentConversation && currentConversation.id === conversation.id ? ' selected' : ''}`;
                conversationEl.dataset.id = conversation.id;
                conversationEl.style.animationDelay = `${index * 0.1}s`;
                
                const date = new Date(conversation.updated_at);
                const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                
                const latestMessage = conversation.latest_message ?
                    (conversation.latest_message.message.length > 50 ?
                        conversation.latest_message.message.substring(0, 50) + '...' :
                        conversation.latest_message.message) :
                    'No messages yet';

                conversationEl.innerHTML = `
                    <div class="conversation-header">
                        <div class="user-info">
                            <div class="user-name">
                                <span class="status-dot ${conversation.status === 'active' ? 'status-active' : 'status-closed'}"></span>
                                ${conversation.user ? escapeHtml(conversation.user.name) : 'Unknown User'}
                            </div>
                            <div class="conversation-preview">${escapeHtml(latestMessage)}</div>
                        </div>
                        <div class="conversation-meta">
                            <div class="conversation-time">${formattedDate}</div>
                            ${conversation.unread_count > 0 ? `<div class="unread-badge">${conversation.unread_count}</div>` : ''}
                            ${conversation.status === 'closed' ? '<div class="status-badge">Closed</div>' : ''}
                        </div>
                    </div>
                    ${conversation.category ? `<div class="category-tag">${escapeHtml(conversation.category)}</div>` : ''}
                `;
                
                conversationEl.addEventListener('click', function() {
                    loadConversation(conversation.id);
                });
                
                conversationsList.appendChild(conversationEl);
            });
        
        updateFilterBtnStyle();
    }

    function renderConversation() {
        if (!currentConversation) return;

        // Update header
        chatUserName.innerHTML = `
            <span class="status-dot ${currentConversation.status === 'active' ? 'status-active' : 'status-closed'}"></span>
            ${currentConversation.user ? escapeHtml(currentConversation.user.name) : 'Unknown User'}
        `;
        chatCategory.textContent = currentConversation.category ? `📂 ${currentConversation.category}` : '';
        chatStatus.textContent = `Status: ${currentConversation.status.charAt(0).toUpperCase() + currentConversation.status.slice(1)}`;

        // Clear and render messages
        chatMessages.innerHTML = '';
        if (currentConversation.messages && currentConversation.messages.length > 0) {
            currentConversation.messages.forEach((message, index) => {
                addMessage(message, false, index);
            });
        } else {
            chatMessages.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">💭</div>
                    <div class="empty-state-text">No messages yet</div>
                </div>
            `;
        }

        updateConversationUI();
        scrollToBottom();
        updateSelectedConversation();
    }

    function updateConversationUI() {
        if (!currentConversation) return;

        if (currentConversation.status === 'active') {
            chatInput.disabled = false;
            sendButton.disabled = false;
            chatInput.placeholder = "Type your message...";
            closeConversationBtn.classList.remove('hidden');
            reopenConversationBtn.classList.add('hidden');
        } else {
            chatInput.disabled = true;
            sendButton.disabled = true;
            chatInput.placeholder = "Conversation is closed";
            closeConversationBtn.classList.add('hidden');
            reopenConversationBtn.classList.remove('hidden');
        }
    }

    function updateSelectedConversation() {
        document.querySelectorAll('.conversation-card').forEach(el => {
            if (currentConversation && el.dataset.id == currentConversation.id) {
                el.classList.add('selected');
            } else {
                el.classList.remove('selected');
            }
        });
    }

    function addMessage(message, scroll = true, index = 0) {
        const messageEl = document.createElement('div');
        let wrapperClass = 'message-wrapper slide-in';
        let bubbleClass = 'message-bubble';
        
        if (message.sender_type === 'user') {
            wrapperClass += ' user';
            bubbleClass += ' user';
        } else if (message.sender_type === 'admin') {
            wrapperClass += ' admin';
            bubbleClass += ' admin';
        } else if (message.sender_type === 'ai') {
            wrapperClass += ' ai';
            bubbleClass += ' ai';
        }

        messageEl.className = wrapperClass;
        messageEl.style.animationDelay = `${index * 0.1}s`;
        messageEl.innerHTML = `
            <div class="${bubbleClass}" data-message-id="${message.id}">
                <div class="message-time">${formatMessageTime(message.created_at)}</div>
                <div class="message-content">${escapeHtml(message.message)}</div>
                ${message.sender_type === 'admin' ? '<div class="message-status">✓ Sent</div>' : ''}
            </div>
        `;
        
        chatMessages.appendChild(messageEl);
        if (scroll) scrollToBottom();
    }

    function sendMessage(e) {
        e.preventDefault();
        if (!currentConversation || currentConversation.status !== 'active') return;
        
        const message = chatInput.value.trim();
        if (!message) return;

        chatInput.disabled = true;
        sendButton.disabled = true;
        typingIndicator.classList.remove('hidden');

        const originalMessage = message;
        chatInput.value = '';
        chatInput.style.height = 'auto';

        // Add temporary message
        const tempMessageEl = document.createElement('div');
        tempMessageEl.className = 'message-wrapper admin';
        tempMessageEl.innerHTML = `
            <div class="message-bubble admin" style="opacity: 0.7;">
                <div class="message-time">${formatMessageTime(new Date().toISOString())}</div>
                <div class="message-content">${escapeHtml(originalMessage)}</div>
                <div class="message-status">📤 Sending...</div>
            </div>
        `;
        chatMessages.appendChild(tempMessageEl);
        scrollToBottom();

        fetch(`/admin/chat/conversations/${currentConversation.id}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: originalMessage })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            tempMessageEl.remove();
            addMessage(data.message);
            loadConversations();
        })
        .catch(error => {
            console.error('Error sending message:', error);
            
            const errorEl = tempMessageEl.querySelector('.message-status');
            if (errorEl) {
                errorEl.innerHTML = '❌ Failed to send';
                errorEl.style.color = '#ef4444';
            }
        })
        .finally(() => {
            if (currentConversation && currentConversation.status === 'active') {
                chatInput.disabled = false;
                sendButton.disabled = false;
                chatInput.focus();
            }
            typingIndicator.classList.add('hidden');
        });
    }

    function closeConversation() {
        if (!currentConversation) return;
        
        closeConversationBtn.disabled = true;
        closeConversationBtn.textContent = 'Closing...';
        
        fetch(`/admin/chat/conversations/${currentConversation.id}/close`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            currentConversation.status = 'closed';
            updateConversationUI();
            loadConversations();
        })
        .catch(error => {
            console.error('Error closing conversation:', error);
            alert('Error closing conversation: ' + error.message);
        })
        .finally(() => {
            closeConversationBtn.disabled = false;
            closeConversationBtn.textContent = 'Close';
        });
    }

    function reopenConversation() {
        if (!currentConversation) return;
        
        reopenConversationBtn.disabled = true;
        reopenConversationBtn.textContent = 'Reopening...';
        
        fetch(`/admin/chat/conversations/${currentConversation.id}/reopen`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            currentConversation.status = 'active';
            updateConversationUI();
            loadConversations();
        })
        .catch(error => {
            console.error('Error reopening conversation:', error);
            alert('Error reopening conversation: ' + error.message);
        })
        .finally(() => {
            reopenConversationBtn.disabled = false;
            reopenConversationBtn.textContent = 'Reopen';
        });
    }

    function setFilter(filter) {
        currentFilter = filter;
        renderConversations();
    }

    function formatMessageTime(timestamp) {
        if (!timestamp) return '';
        const date = new Date(timestamp);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function scrollToBottom() {
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }

    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Cleanup
    window.addEventListener('beforeunload', function() {
        if (refreshInterval) clearInterval(refreshInterval);
        if (messagePollingInterval) clearInterval(messagePollingInterval);
    });
});
</script>
@endsection

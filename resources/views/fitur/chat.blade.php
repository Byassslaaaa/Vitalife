<x-app-layout>
    <style>
        /* Chat animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Smooth scrolling for chat messages */
        #chat-messages {
            scroll-behavior: smooth;
        }

        #chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        #chat-messages::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        #chat-messages::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #10b981, #14b8a6);
            border-radius: 10px;
        }

        #chat-messages::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #059669, #0d9488);
        }

        /* Scroll to bottom button animation */
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        #scroll-to-bottom {
            animation: bounce 2s infinite;
        }

        #scroll-to-bottom:hover {
            animation: none;
        }
    </style>

    {{-- Full Page Chat --}}
    <div class="bg-gray-50 min-h-screen pt-24 pb-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Chat Container --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
                {{-- Chat Header --}}
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold">HeaLife Support Chat</h1>
                                <p class="text-emerald-100 text-sm">We're here to help you 24/7</p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard') }}"
                            class="text-white hover:bg-white/20 rounded-lg p-2 transition-all duration-200 transform hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Guest User Info (show for non-authenticated) --}}
                @guest
                    <div class="px-6 py-3 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-emerald-100">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-700 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                💬 You're chatting as a guest -
                                <button onclick="showLoginPrompt()"
                                    class="ml-1 text-emerald-600 hover:text-emerald-700 font-medium underline">
                                    Login for full support
                                </button>
                            </p>
                        </div>
                    </div>
                @endguest

                {{-- Chat Messages Area --}}
                <div class="relative">
                    <div id="chat-messages" class="p-6 space-y-4 bg-gray-50 overflow-y-auto" style="height: calc(100vh - 400px); min-height: 400px;">
                        {{-- Messages will be added here dynamically --}}
                    </div>

                    {{-- Scroll to Bottom Button --}}
                    <button id="scroll-to-bottom"
                        class="hidden absolute bottom-4 right-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-full p-3 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 z-20"
                        title="Scroll to bottom">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                        {{-- New message badge --}}
                        <span id="new-message-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">!</span>
                    </button>
                </div>

                {{-- Category Selection (initially hidden) --}}
                <div id="category-selection" class="p-6 bg-gradient-to-b from-gray-50 to-white border-t border-gray-200 hidden">
                    <p class="text-base text-gray-700 mb-4 font-medium flex items-center">
                        <svg class="w-6 h-6 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        What would you like to discuss?
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <button
                            class="category-btn bg-white border-2 border-gray-200 rounded-xl p-4 text-sm hover:border-emerald-400 hover:bg-gradient-to-br hover:from-emerald-50 hover:to-teal-50 transition-all duration-300 transform hover:scale-105 hover:shadow-md font-medium text-gray-700 group"
                            data-category="Facilities & Accommodations">
                            <svg class="w-8 h-8 mx-auto mb-2 text-emerald-500 group-hover:scale-110 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Facilities
                        </button>
                        <button
                            class="category-btn bg-white border-2 border-gray-200 rounded-xl p-4 text-sm hover:border-teal-400 hover:bg-gradient-to-br hover:from-teal-50 hover:to-emerald-50 transition-all duration-300 transform hover:scale-105 hover:shadow-md font-medium text-gray-700 group"
                            data-category="Health & Security">
                            <svg class="w-8 h-8 mx-auto mb-2 text-teal-500 group-hover:scale-110 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Health
                        </button>
                        <button
                            class="category-btn bg-white border-2 border-gray-200 rounded-xl p-4 text-sm hover:border-emerald-400 hover:bg-gradient-to-br hover:from-emerald-50 hover:to-teal-50 transition-all duration-300 transform hover:scale-105 hover:shadow-md font-medium text-gray-700 group"
                            data-category="General Information">
                            <svg class="w-8 h-8 mx-auto mb-2 text-emerald-500 group-hover:scale-110 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            General Info
                        </button>
                        <button
                            class="category-btn bg-white border-2 border-gray-200 rounded-xl p-4 text-sm hover:border-teal-400 hover:bg-gradient-to-br hover:from-teal-50 hover:to-emerald-50 transition-all duration-300 transform hover:scale-105 hover:shadow-md font-medium text-gray-700 group"
                            data-category="Services & Pricing">
                            <svg class="w-8 h-8 mx-auto mb-2 text-teal-500 group-hover:scale-110 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pricing
                        </button>
                    </div>
                </div>

                {{-- Chat Input Area --}}
                <div id="chat-input-container" class="p-6 border-t border-gray-200 bg-white relative z-10">
                    <form id="chat-form" class="flex space-x-3">
                        <input type="text" id="chat-input" autocomplete="off"
                            class="flex-1 border-2 border-gray-200 rounded-xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all text-base"
                            placeholder="Type your message..." required>
                        <button type="submit"
                            class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl px-8 py-4 hover:from-emerald-600 hover:to-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const chatMessages = document.getElementById('chat-messages');
            const categorySelection = document.getElementById('category-selection');
            const categoryButtons = document.querySelectorAll('.category-btn');
            const chatInputContainer = document.getElementById('chat-input-container');
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            const scrollToBottomBtn = document.getElementById('scroll-to-bottom');
            const newMessageBadge = document.getElementById('new-message-badge');

            // State
            let currentConversation = null;
            let selectedCategory = 'General Information';
            let lastMessageId = 0;
            let pollingInterval = null;
            let isUserScrolling = false;
            let autoScrollEnabled = true;

            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Scroll detection for showing/hiding scroll button
            chatMessages.addEventListener('scroll', function() {
                const isAtBottom = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight < 50;

                if (isAtBottom) {
                    scrollToBottomBtn.classList.add('hidden');
                    newMessageBadge.classList.add('hidden');
                    autoScrollEnabled = true;
                } else {
                    scrollToBottomBtn.classList.remove('hidden');
                    autoScrollEnabled = false;
                }
            });

            // Scroll to bottom button click
            scrollToBottomBtn.addEventListener('click', function() {
                autoScrollEnabled = true;
                newMessageBadge.classList.add('hidden');
                scrollToBottom(true);
                scrollToBottomBtn.classList.add('hidden');
            });

            // Initialize chat on page load
            initializeChat();

            async function initializeChat() {
                await loadConversation();
                // Show welcome message
                setTimeout(() => {
                    const welcomeMessage = getWelcomeMessage('General Information');
                    addBotMessage(welcomeMessage);
                }, 500);
            }

            // Category selection
            categoryButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    selectedCategory = this.dataset.category;
                    categorySelection.classList.add('hidden');
                    addSystemMessage(`Category: ${selectedCategory}`);
                });
            });

            // Send message
            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const message = chatInput.value.trim();
                if (!message) return;

                // Enable auto-scroll when user sends a message
                autoScrollEnabled = true;

                // Add user message to UI immediately
                addUserMessage(message);

                // Clear input and refocus
                chatInput.value = '';
                chatInput.focus();

                // Send to server
                await sendMessage(message);
            });

            // Get welcome message
            function getWelcomeMessage(category) {
                const messages = {
                    'Spa Information': 'Halo! 🌸 Selamat datang di HeaLife Spa Support.\n\nSaya siap membantu Anda dengan informasi tentang layanan spa, treatment, harga, dan booking.\n\nAda yang bisa saya bantu?',
                    'Yoga Information': 'Namaste! 🧘 Selamat datang di HeaLife Yoga Support.\n\nSaya siap membantu Anda dengan informasi tentang kelas yoga, instruktur, jadwal, dan membership.\n\nAda yang ingin ditanyakan?',
                    'Gym Information': 'Hi! 💪 Selamat datang di HeaLife Gym Support.\n\nSaya siap membantu Anda dengan informasi tentang fasilitas gym, personal trainer, membership, dan program latihan.\n\nAda yang bisa saya bantu?',
                    'General Information': 'Halo! 👋 Selamat datang di HeaLife Chat Support.\n\nSaya adalah asisten virtual yang siap membantu Anda dengan informasi tentang Spa, Yoga, Gym, dan layanan kami lainnya.\n\nAda yang bisa saya bantu hari ini?'
                };
                return messages[category] || messages['General Information'];
            }

            // Load conversation from server
            async function loadConversation(category = null) {
                try {
                    const url = category ?
                        `/chat/conversation?category=${encodeURIComponent(category)}` :
                        '/chat/conversation';

                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    const data = await response.json();

                    if (data.conversation) {
                        currentConversation = data.conversation;
                        lastMessageId = 0;

                        // Load existing messages
                        if (data.messages && data.messages.length > 0) {
                            chatMessages.innerHTML = '';
                            data.messages.forEach(msg => {
                                if (msg.sender_type === 'user') {
                                    addUserMessage(msg.message, false);
                                } else if (msg.sender_type === 'bot') {
                                    addBotMessageInstant(msg.message, false);
                                } else if (msg.sender_type === 'admin') {
                                    addAdminMessage(msg.message, false);
                                }
                                lastMessageId = Math.max(lastMessageId, msg.id);
                            });
                            scrollToBottom();
                        }

                        startPolling();
                    }
                } catch (error) {
                    console.error('Error loading conversation:', error);
                    addSystemMessage('Sorry, there was an error loading the chat.');
                }
            }

            // Send message to server
            async function sendMessage(message) {
                try {
                    const response = await fetch('/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            conversation_id: currentConversation?.id,
                            message: message,
                            category: selectedCategory
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        if (data.message && data.message.id) {
                            lastMessageId = Math.max(lastMessageId, data.message.id);
                        }

                        if (data.bot_message) {
                            addBotMessage(data.bot_message);
                        }

                        if (!currentConversation) {
                            await loadConversation();
                        }

                        if (data.quick_replies && data.quick_replies.length > 0) {
                            showQuickReplies(data.quick_replies);
                        }
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    addSystemMessage('Maaf, terjadi kesalahan. Silakan coba lagi.');
                }
            }

            // Polling error tracking
            let pollingErrorCount = 0;
            const MAX_POLLING_ERRORS = 5;
            let isConnectionLost = false;

            // Start polling for new messages
            function startPolling() {
                if (pollingInterval) return;

                pollingInterval = setInterval(async () => {
                    if (!currentConversation) return;

                    try {
                        const response = await fetch(
                            `/chat/poll/${currentConversation.id}?last_message_id=${lastMessageId}`, {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        pollingErrorCount = 0;

                        if (isConnectionLost) {
                            isConnectionLost = false;
                            removeConnectionLostMessage();
                        }

                        if (data.success && data.has_new) {
                            data.messages.forEach(msg => {
                                if (msg.sender_type === 'admin') {
                                    addAdminMessage(msg.message);
                                } else if (msg.sender_type === 'bot') {
                                    addBotMessageInstant(msg.message);
                                } else if (msg.sender_type === 'system') {
                                    addSystemMessage(msg.message);
                                }
                                lastMessageId = Math.max(lastMessageId, msg.id);
                            });
                        }
                    } catch (error) {
                        console.error('Error polling messages:', error);
                        pollingErrorCount++;

                        if (pollingErrorCount >= MAX_POLLING_ERRORS) {
                            stopPolling();
                            if (!isConnectionLost) {
                                isConnectionLost = true;
                                addConnectionLostMessage();
                            }
                        }
                    }
                }, 3000);
            }

            // Stop polling
            function stopPolling() {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }

            // Show quick replies
            function showQuickReplies(replies) {
                const quickRepliesDiv = document.createElement('div');
                quickRepliesDiv.className = 'flex flex-wrap gap-2 my-2 animate-fade-in';

                replies.forEach(reply => {
                    const button = document.createElement('button');
                    button.className =
                        'bg-white border-2 border-emerald-200 text-emerald-700 px-4 py-2 rounded-xl text-sm hover:bg-gradient-to-r hover:from-emerald-500 hover:to-teal-500 hover:text-white transition-all duration-300 shadow-sm hover:shadow-md transform hover:scale-105 font-medium';
                    button.textContent = reply;
                    button.addEventListener('click', function() {
                        chatInput.value = reply;
                        chatForm.dispatchEvent(new Event('submit'));
                    });
                    quickRepliesDiv.appendChild(button);
                });

                chatMessages.appendChild(quickRepliesDiv);
                scrollToBottom();
            }

            // Add user message to UI
            function addUserMessage(message, scroll = true) {
                const messageElement = document.createElement('div');
                messageElement.className = 'flex justify-end animate-fade-in-up';
                messageElement.innerHTML = `
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-2xl rounded-tr-sm py-3 px-5 max-w-[75%] shadow-md">
                    <p class="text-base">${escapeHtml(message)}</p>
                </div>
            `;
                chatMessages.appendChild(messageElement);
                if (scroll) scrollToBottom();
            }

            // Add bot message to UI (with typing indicator)
            function addBotMessage(message, scroll = true) {
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    addBotMessageInstant(message, scroll);
                }, 600);
            }

            // Add bot message instantly (no typing indicator)
            function addBotMessageInstant(message, scroll = true) {
                const messageElement = document.createElement('div');
                messageElement.className = 'flex justify-start animate-fade-in-up';
                const formattedMessage = escapeHtml(message).replace(/\n/g, '<br>');
                messageElement.innerHTML = `
                <div class="flex items-start space-x-3 max-w-[75%]">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="bg-gray-100 rounded-2xl rounded-tl-sm py-3 px-5 shadow-md">
                        <p class="text-base text-gray-800">${formattedMessage}</p>
                    </div>
                </div>
            `;
                chatMessages.appendChild(messageElement);

                // Show badge if not at bottom
                if (!autoScrollEnabled && scroll) {
                    newMessageBadge.classList.remove('hidden');
                }

                if (scroll) scrollToBottom();
            }

            // Add admin message to UI
            function addAdminMessage(message, scroll = true) {
                const messageElement = document.createElement('div');
                messageElement.className = 'flex justify-start animate-fade-in-up';
                messageElement.innerHTML = `
                <div class="flex items-start space-x-3 max-w-[75%]">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-600 to-teal-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-2xl rounded-tl-sm py-3 px-5 shadow-md">
                        <p class="text-base text-gray-800"><span class="font-bold text-emerald-700">Admin:</span> ${escapeHtml(message)}</p>
                    </div>
                </div>
            `;
                chatMessages.appendChild(messageElement);

                // Show badge if not at bottom
                if (!autoScrollEnabled && scroll) {
                    newMessageBadge.classList.remove('hidden');
                }

                if (scroll) scrollToBottom();
            }

            // Add system message to UI
            function addSystemMessage(message, scroll = true) {
                const messageElement = document.createElement('div');
                messageElement.className = 'flex justify-center animate-fade-in';
                messageElement.innerHTML = `
                <div class="bg-gradient-to-r from-gray-100 to-gray-50 text-gray-600 rounded-xl py-2 px-4 text-sm max-w-[90%] text-center border border-gray-200 shadow-sm">
                    <p>${escapeHtml(message)}</p>
                </div>
            `;
                chatMessages.appendChild(messageElement);
                if (scroll) scrollToBottom();
            }

            // Add connection lost warning
            function addConnectionLostMessage() {
                const messageElement = document.createElement('div');
                messageElement.id = 'connection-lost-warning';
                messageElement.className = 'flex justify-center animate-fade-in';
                messageElement.innerHTML = `
                <div class="bg-gradient-to-r from-red-100 to-orange-50 text-red-700 rounded-xl py-4 px-6 text-sm max-w-[90%] text-center border-2 border-red-300 shadow-md">
                    <div class="flex items-center justify-center space-x-2 mb-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p class="font-semibold">Connection lost</p>
                    </div>
                    <p class="mb-3">Please refresh the page to continue chatting.</p>
                    <button onclick="location.reload()" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                        Refresh Page
                    </button>
                </div>
            `;
                chatMessages.appendChild(messageElement);
                scrollToBottom();
            }

            // Remove connection lost warning
            function removeConnectionLostMessage() {
                const warning = document.getElementById('connection-lost-warning');
                if (warning) {
                    warning.remove();
                    addSystemMessage('Connection restored. You can continue chatting.');
                }
            }

            // Show typing indicator
            function showTypingIndicator() {
                const indicator = document.createElement('div');
                indicator.id = 'typing-indicator';
                indicator.className = 'flex justify-start';
                indicator.innerHTML = `
                <div class="flex items-start space-x-3 max-w-[75%]">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="bg-gray-100 rounded-2xl rounded-tl-sm py-3 px-5 shadow-md">
                        <div class="flex space-x-2">
                            <div class="w-2.5 h-2.5 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2.5 h-2.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2.5 h-2.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            `;
                chatMessages.appendChild(indicator);
                scrollToBottom();
            }

            // Hide typing indicator
            function hideTypingIndicator() {
                const indicator = document.getElementById('typing-indicator');
                if (indicator) {
                    indicator.remove();
                }
            }

            // Scroll chat to bottom
            function scrollToBottom(force = false) {
                // Only auto-scroll if enabled or forced
                if (!autoScrollEnabled && !force) return;

                setTimeout(() => {
                    const container = document.getElementById('chat-messages');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                        requestAnimationFrame(() => {
                            container.scrollTop = container.scrollHeight;
                        });
                    }
                }, 50);
            }

            // Escape HTML to prevent XSS
            function escapeHtml(unsafe) {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                stopPolling();
            });
        });

        // Function to show login prompt
        function showLoginPrompt() {
            if (confirm('Login for personalized support with our team. Would you like to login now?')) {
                window.location.href = '{{ route('login') }}';
            }
        }
    </script>
</x-app-layout>

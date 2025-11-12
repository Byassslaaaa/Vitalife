{{-- Chatbot Widget Component - Floating Button that redirects to Chat Page --}}
@props(['defaultCategory' => 'General Information'])

<div id="chatbot-widget" class="fixed bottom-6 right-6 z-50">
    <!-- Chat Button - Redirects to Full Chat Page -->
    <a href="{{ route('chat') }}" id="chat-button"
        class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-full p-4 shadow-2xl flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-emerald-500/50 hover:rotate-12 group"
        title="Open Chat Support">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 group-hover:scale-110 transition-transform" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <span class="absolute -top-1 -right-1 flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
        </span>
    </a>
</div>

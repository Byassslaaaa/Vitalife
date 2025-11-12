@props(['type', 'id', 'name'])

<div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Customer Reviews</h3>
                <p class="text-sm text-gray-600">See what others are saying</p>
            </div>
        </div>
        <div id="testimonial-count" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold">
            Loading...
        </div>
    </div>

    <!-- Testimonials Container -->
    <div id="testimonials-container" class="space-y-6">
        <!-- Loading State -->
        <div id="loading-testimonials" class="text-center py-12">
            <svg class="animate-spin h-12 w-12 mx-auto text-emerald-500 mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-600">Loading testimonials...</p>
        </div>

        <!-- Empty State -->
        <div id="empty-testimonials" class="hidden text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">No Reviews Yet</h4>
            <p class="text-gray-600">Be the first to share your experience!</p>
        </div>

        <!-- Testimonials List -->
        <div id="testimonials-list" class="space-y-6"></div>
    </div>
</div>

<script>
(function() {
    const container = document.getElementById('testimonials-container');
    const loading = document.getElementById('loading-testimonials');
    const empty = document.getElementById('empty-testimonials');
    const list = document.getElementById('testimonials-list');
    const countBadge = document.getElementById('testimonial-count');

    async function loadTestimonials() {
        try {
            const response = await fetch('/api/testimonials?type={{ $type }}&id={{ $id }}');
            const data = await response.json();

            loading.classList.add('hidden');

            if (data.success && data.testimonials.length > 0) {
                countBadge.textContent = `${data.testimonials.length} Review${data.testimonials.length !== 1 ? 's' : ''}`;
                renderTestimonials(data.testimonials);
            } else {
                countBadge.textContent = '0 Reviews';
                empty.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error loading testimonials:', error);
            loading.classList.add('hidden');
            empty.classList.remove('hidden');
            countBadge.textContent = 'Error';
        }
    }

    function renderTestimonials(testimonials) {
        list.innerHTML = testimonials.map(testimonial => {
            const stars = renderStars(testimonial.rating);
            const date = formatDate(testimonial.created_at);
            const service = testimonial.service ? `<span class="text-emerald-600 font-medium">${testimonial.service}</span>` : '';

            return `
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                ${testimonial.name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">${escapeHtml(testimonial.name)}</h4>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <span>${date}</span>
                                    ${service ? `<span>•</span> ${service}` : ''}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            ${stars}
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed">${escapeHtml(testimonial.comment)}</p>
                </div>
            `;
        }).join('');
    }

    function renderStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>';
            } else {
                stars += '<svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>';
            }
        }
        return stars;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays === 0) return 'Today';
        if (diffDays === 1) return 'Yesterday';
        if (diffDays < 7) return `${diffDays} days ago`;
        if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
        if (diffDays < 365) return `${Math.floor(diffDays / 30)} months ago`;
        return `${Math.floor(diffDays / 365)} years ago`;
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Load testimonials on page load
    loadTestimonials();
})();
</script>

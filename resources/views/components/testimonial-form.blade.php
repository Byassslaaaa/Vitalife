@props(['type', 'id', 'name'])

<div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl shadow-lg p-8 border border-gray-100">
    <div class="flex items-center mb-6">
        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Share Your Experience</h3>
            <p class="text-sm text-gray-600">Help others by sharing your testimonial</p>
        </div>
    </div>

    <form id="testimonialForm" class="space-y-6">
        @csrf
        <input type="hidden" name="testimonial_type" value="{{ $type }}">
        <input type="hidden" name="testimonial_id" value="{{ $id }}">

        <!-- Name Input -->
        <div>
            <label for="testimonial_name" class="block text-sm font-semibold text-gray-700 mb-2">
                Your Name <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="testimonial_name"
                   name="name"
                   required
                   placeholder="Enter your name"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
        </div>

        <!-- Service Selection -->
        <div>
            <label for="testimonial_service" class="block text-sm font-semibold text-gray-700 mb-2">
                Service Used <span class="text-gray-400">(Optional)</span>
            </label>
            <select id="testimonial_service"
                    name="service"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none appearance-none bg-white">
                <option value="">Loading services...</option>
            </select>
            <div id="service-loading" class="mt-2 text-sm text-gray-500 flex items-center hidden">
                <svg class="animate-spin h-4 w-4 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading services...
            </div>
        </div>

        <!-- Rating -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-3">
                Rating <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center space-x-2">
                <div id="star-rating" class="flex space-x-1">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            data-rating="{{ $i }}"
                            class="star-btn focus:outline-none transition-transform hover:scale-110">
                        <svg class="w-10 h-10 text-gray-300 hover:text-yellow-400 transition-colors"
                             fill="currentColor"
                             viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </button>
                    @endfor
                </div>
                <span id="rating-text" class="text-sm font-medium text-gray-600 ml-3">Select rating</span>
            </div>
            <input type="hidden" id="testimonial_rating" name="rating" required>
        </div>

        <!-- Comment -->
        <div>
            <label for="testimonial_comment" class="block text-sm font-semibold text-gray-700 mb-2">
                Your Review <span class="text-red-500">*</span>
            </label>
            <textarea id="testimonial_comment"
                      name="comment"
                      rows="5"
                      required
                      minlength="10"
                      placeholder="Share your experience with {{ $name }}... (minimum 10 characters)"
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none resize-none"></textarea>
            <p class="mt-2 text-xs text-gray-500">
                <span id="char-count">0</span> / 10 characters minimum
            </p>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-between pt-4">
            <p class="text-xs text-gray-500">
                <span class="text-red-500">*</span> Required fields
            </p>
            <button type="submit"
                    id="submit-btn"
                    class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                Submit Review
            </button>
        </div>
    </form>
</div>

<script>
(function() {
    const form = document.getElementById('testimonialForm');
    const ratingInput = document.getElementById('testimonial_rating');
    const ratingText = document.getElementById('rating-text');
    const starButtons = document.querySelectorAll('.star-btn');
    const serviceSelect = document.getElementById('testimonial_service');
    const serviceLoading = document.getElementById('service-loading');
    const commentTextarea = document.getElementById('testimonial_comment');
    const charCount = document.getElementById('char-count');
    const submitBtn = document.getElementById('submit-btn');

    let selectedRating = 0;

    // Load services
    async function loadServices() {
        serviceLoading.classList.remove('hidden');
        try {
            const response = await fetch('/api/testimonials/services?type={{ $type }}&id={{ $id }}');
            const data = await response.json();

            if (data.success && data.services.length > 0) {
                serviceSelect.innerHTML = '<option value="">Choose a service (optional)</option>';
                data.services.forEach(service => {
                    const option = document.createElement('option');
                    option.value = service;
                    option.textContent = service;
                    serviceSelect.appendChild(option);
                });
            } else {
                serviceSelect.innerHTML = '<option value="">No services available</option>';
            }
        } catch (error) {
            console.error('Error loading services:', error);
            serviceSelect.innerHTML = '<option value="">Failed to load services</option>';
        } finally {
            serviceLoading.classList.add('hidden');
        }
    }

    // Star rating functionality
    starButtons.forEach(button => {
        button.addEventListener('click', function() {
            selectedRating = parseInt(this.dataset.rating);
            ratingInput.value = selectedRating;
            updateStars();
            updateRatingText();
        });

        button.addEventListener('mouseenter', function() {
            const hoverRating = parseInt(this.dataset.rating);
            highlightStars(hoverRating);
        });
    });

    document.getElementById('star-rating').addEventListener('mouseleave', function() {
        updateStars();
    });

    function highlightStars(rating) {
        starButtons.forEach((btn, index) => {
            const star = btn.querySelector('svg');
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    function updateStars() {
        highlightStars(selectedRating);
    }

    function updateRatingText() {
        const texts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
        ratingText.textContent = texts[selectedRating] || 'Select rating';
    }

    // Character count
    commentTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (selectedRating === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Rating Required',
                text: 'Please select a rating before submitting.',
                confirmButtonColor: '#10b981'
            });
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Submitting...';

        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route("testimonials.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thank You!',
                    text: data.message,
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    form.reset();
                    selectedRating = 0;
                    updateStars();
                    updateRatingText();
                    charCount.textContent = '0';
                });
            } else {
                throw new Error(data.message || 'Failed to submit testimonial');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message || 'Failed to submit testimonial. Please try again.',
                confirmButtonColor: '#10b981'
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Review';
        }
    });

    // Load services when page loads
    loadServices();
})();
</script>

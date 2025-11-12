<x-app-layout>
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Prevent browser caching to ensure fresh authentication state -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <style>
        .voucher-card {
            transition: all 0.3s ease;
        }

        .voucher-card:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .voucher-card .voucher-header {
            transition: transform 0.5s ease;
        }

        .voucher-card:hover .voucher-header::before {
            transform: scale(1.05);
        }

        .voucher-header {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
        }

        .copy-btn {
            background: linear-gradient(to r, #10b981, #14b8a6);
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            background: linear-gradient(to r, #059669, #0d9488);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>

    {{-- Unified Voucher Section --}}
    <div class="bg-white min-h-screen">
        <!-- Hero Section with Modern Design -->
        <section class="pt-32 pb-20 relative overflow-hidden" aria-labelledby="hero-title">
            <!-- Background Decorations -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-emerald-200/30 to-teal-200/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-emerald-300/20 to-teal-300/20 rounded-full blur-3xl"></div>

            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10">
                <div class="flex items-center space-x-6 mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg float-animation">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <div>
                        <h1 id="hero-title" class="text-4xl sm:text-5xl lg:text-6xl font-black bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent mb-2">
                            VOUCHERS & DEALS
                        </h1>
                        <p class="text-lg lg:text-xl text-gray-600 font-medium">
                            Discover Amazing Deals & Save on Your Wellness Journey 🎟️
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Page Content -->
        <div class="pb-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">

                @if (session('success'))
                    <div
                        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-8 mx-4 sm:mx-0">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8 mx-4 sm:mx-0">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Featured promotion banner -->
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl p-8 text-white text-center mb-12 shadow-xl">
                    <h2 class="text-2xl lg:text-3xl font-bold mb-4">🎉 Special Offer!</h2>
                    <p class="text-lg mb-6 opacity-90">Get exclusive vouchers and save up to 50% on wellness services
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('spa.index') }}"
                            class="bg-white text-emerald-600 px-6 py-3 rounded-xl font-semibold hover:bg-emerald-50 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                            Explore SPA
                        </a>
                        <a href="{{ route('yoga.index') }}"
                            class="bg-white text-emerald-600 px-6 py-3 rounded-xl font-semibold hover:bg-emerald-50 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                            Try YOGA
                        </a>
                        <a href="{{ route('gym.index') }}"
                            class="bg-white text-emerald-600 px-6 py-3 rounded-xl font-semibold hover:bg-emerald-50 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                            Visit GYM
                        </a>
                    </div>
                </div>

                <!-- Voucher display section -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($vouchers as $voucher)
                        <div class="voucher-card bg-white rounded-2xl shadow-lg hover:shadow-2xl overflow-hidden group border border-gray-100 cursor-pointer"
                             onclick="copyVoucherCode(event, '{{ $voucher->code }}', this)">
                            <div class="relative">
                                <!-- Simple voucher header -->
                                <div class="voucher-header w-full h-48 flex items-center justify-center overflow-hidden">
                                    <div class="text-white text-center">
                                        <div class="text-5xl font-bold mb-2">
                                            @if($voucher->discount_type === 'percentage')
                                                {{ $voucher->discount_percentage }}%
                                            @else
                                                Rp {{ number_format($voucher->discount_amount, 0, ',', '.') }}
                                            @endif
                                        </div>
                                        <div class="text-white/90 font-medium text-lg">OFF</div>
                                    </div>
                                </div>

                                <!-- Simple discount badge -->
                                <div
                                    class="absolute top-4 right-4 bg-red-500 text-white font-bold py-2 px-4 rounded-full text-sm shadow-lg">
                                    @if($voucher->discount_type === 'percentage')
                                        -{{ $voucher->discount_percentage }}%
                                    @else
                                        -Rp {{ number_format($voucher->discount_amount / 1000, 0) }}K
                                    @endif
                                </div>
                            </div>

                            <div class="p-6">
                                <!-- Voucher title -->
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    @if($voucher->discount_type === 'percentage')
                                        {{ $voucher->discount_percentage }}% Discount
                                    @else
                                        Rp {{ number_format($voucher->discount_amount, 0, ',', '.') }} Off
                                    @endif
                                </h3>

                                <!-- Voucher description -->
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($voucher->description, 80) }}</p>

                                <!-- Voucher code section -->
                                <div class="bg-emerald-50 rounded-lg p-4 mb-4 border border-emerald-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-xs font-medium text-emerald-600 mb-1">Code</p>
                                            <p class="font-mono font-bold text-gray-900 text-base voucher-code-text">
                                                {{ $voucher->code }}
                                            </p>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Voucher details -->
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    @if ($voucher->expired_at)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($voucher->expired_at)->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="flex items-center text-emerald-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            No expiry
                                        </span>
                                    @endif

                                    @if ($voucher->usage_limit)
                                        <span class="bg-gray-100 px-3 py-1 rounded-full">
                                            {{ max(0, $voucher->usage_limit - $voucher->usage_count) }} left
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full py-16 flex flex-col items-center justify-center bg-white rounded-xl shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400 mb-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No vouchers available</h3>
                            <p class="text-gray-600 text-lg">Check back later for new promotions and discounts.</p>
                        </div>
                    @endforelse
                </div>

                <!-- How to use vouchers section -->
                <div class="mt-20 bg-white rounded-xl p-8 shadow-lg border border-gray-200">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">How to Use Vouchers</h2>
                        <p class="text-gray-600">Follow these simple steps to apply your voucher and save money</p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div
                                class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center shadow-lg">
                                <span class="text-2xl font-bold text-white">1</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Copy Code</h3>
                            <p class="text-sm text-gray-600">Click the copy button to get your voucher code</p>
                        </div>

                        <div class="text-center">
                            <div
                                class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center shadow-lg">
                                <span class="text-2xl font-bold text-white">2</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Shop Services</h3>
                            <p class="text-sm text-gray-600">Browse and select your desired wellness services</p>
                        </div>

                        <div class="text-center">
                            <div
                                class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center shadow-lg">
                                <span class="text-2xl font-bold text-white">3</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Apply at Checkout</h3>
                            <p class="text-sm text-gray-600">Paste the code in the "Apply Voucher" field</p>
                        </div>

                        <div class="text-center">
                            <div
                                class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center shadow-lg">
                                <span class="text-2xl font-bold text-white">4</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Enjoy Savings</h3>
                            <p class="text-sm text-gray-600">Your discount will be automatically applied</p>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3">Important Notes:</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Vouchers cannot be combined with other promotions
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Check expiration dates before using
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Some vouchers have usage limits
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyVoucherCode(event, code, cardElement) {
            // Copy to clipboard
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(code).then(() => {
                    showCopyFeedback(cardElement, code);
                }).catch(() => {
                    fallbackCopy(code, cardElement);
                });
            } else {
                fallbackCopy(code, cardElement);
            }
        }

        function fallbackCopy(text, cardElement) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showCopyFeedback(cardElement, text);
        }

        function showCopyFeedback(cardElement, code) {
            // Show success alert with SweetAlert2
            Swal.fire({
                icon: 'success',
                title: 'Voucher Copied!',
                html: `Code <strong class="font-mono">${code}</strong> copied to clipboard`,
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: 'top-end',
                background: '#10b981',
                color: '#ffffff',
                iconColor: '#ffffff',
                customClass: {
                    popup: 'colored-toast'
                }
            });

            // Add visual feedback to the card
            cardElement.classList.add('ring-2', 'ring-emerald-500');
            setTimeout(() => {
                cardElement.classList.remove('ring-2', 'ring-emerald-500');
            }, 2000);
        }
    </script>
</x-app-layout>

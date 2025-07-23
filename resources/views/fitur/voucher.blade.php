<x-app-layout>
    <style>
        .unified-gradient {
            background: linear-gradient(to bottom, #FFFFFF 0%, #BED9FE 100%);
        }
    </style>

    {{-- Unified Voucher Section --}}
    <div class="unified-gradient min-h-screen">
        <!-- Page Content -->
        <div class="pt-32 pb-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="text-center mb-16">
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Available Vouchers</h1>
                    <p class="text-lg text-gray-600">Discover amazing deals and save on your wellness journey</p>
                </div>

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
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-8 text-white text-center mb-12">
                    <h2 class="text-2xl lg:text-3xl font-bold mb-4">🎉 Special Offer!</h2>
                    <p class="text-lg mb-6 opacity-90">Get exclusive vouchers and save up to 50% on wellness
                        services</p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('spa.index') }}"
                            class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Explore SPA
                        </a>
                        <a href="{{ route('yoga.index') }}"
                            class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Try YOGA
                        </a>
                        <a href="{{ route('gym.index') }}"
                            class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Visit GYM
                        </a>
                    </div>
                </div>

                <!-- Voucher display section -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($vouchers as $voucher)
                        <div
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <div class="relative">
                                <!-- Voucher image -->
                                @if ($voucher->image)
                                    <img src="{{ asset($voucher->image) }}" alt="Voucher {{ $voucher->code }}"
                                        class="w-full h-48 object-cover">
                                @else
                                    <!-- Default voucher image if none exists -->
                                    <div
                                        class="w-full h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-4xl font-bold text-white">{{ $voucher->discount_percentage }}%
                                            OFF</span>
                                    </div>
                                @endif

                                <!-- Discount badge -->
                                <div
                                    class="absolute top-4 right-4 bg-red-500 text-white font-bold py-2 px-4 rounded-full shadow-lg">
                                    {{ $voucher->discount_percentage }}% OFF
                                </div>
                            </div>

                            <div class="p-6">
                                <!-- Voucher title -->
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    Discount {{ $voucher->discount_percentage }}%
                                </h3>

                                <!-- Voucher description -->
                                <p class="text-gray-600 mb-6">{{ $voucher->description }}</p>

                                <!-- Voucher code section with copy functionality -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">Voucher Code</p>
                                            <p class="font-mono font-bold text-gray-800 text-lg">
                                                {{ $voucher->code }}</p>
                                        </div>
                                        <button onclick="copyToClipboard('{{ $voucher->code }}')"
                                            class="text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200 hover:bg-gray-600"
                                            style="background-color: #374151;">
                                            Copy
                                        </button>
                                    </div>
                                </div>

                                <!-- Voucher details -->
                                <div class="space-y-3 text-sm text-gray-600">
                                    <!-- Expiration date -->
                                    @if ($voucher->expired_at)
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Expires:
                                                {{ \Carbon\Carbon::parse($voucher->expired_at)->format('M d, Y') }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>No expiration date</span>
                                        </div>
                                    @endif

                                    <!-- Usage limit -->
                                    @if ($voucher->usage_limit)
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span>Remaining:
                                                {{ max(0, $voucher->usage_limit - $voucher->usage_count) }}
                                                uses</span>
                                        </div>
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
                <div class="mt-20 bg-white shadow-lg rounded-xl p-8 border border-gray-100">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">How to Use Vouchers</h2>
                        <p class="text-gray-600">Follow these simple steps to apply your voucher and save money</p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div
                                class="bg-blue-50 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <span class="text-2xl font-bold text-blue-600">1</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Copy Code</h3>
                            <p class="text-sm text-gray-600">Click the copy button to get your voucher code</p>
                        </div>

                        <div class="text-center">
                            <div
                                class="bg-green-50 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <span class="text-2xl font-bold text-green-600">2</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Shop Services</h3>
                            <p class="text-sm text-gray-600">Browse and select your desired wellness services</p>
                        </div>

                        <div class="text-center">
                            <div
                                class="bg-yellow-50 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <span class="text-2xl font-bold text-yellow-600">3</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Apply at Checkout</h3>
                            <p class="text-sm text-gray-600">Paste the code in the "Apply Voucher" field</p>
                        </div>

                        <div class="text-center">
                            <div
                                class="bg-purple-50 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <span class="text-2xl font-bold text-purple-600">4</span>
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
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Create a temporary success message
                const button = event.target;
                const originalText = button.textContent;

                button.textContent = 'Copied!';
                button.classList.add('bg-green-500');
                button.classList.remove('hover:bg-gray-600');

                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-500');
                    button.classList.add('hover:bg-gray-600');
                }, 2000);
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                alert('Voucher code copied!');
            });
        }
    </script>

    @include('layouts.footer')
</x-app-layout>

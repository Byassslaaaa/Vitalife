<x-app-layout>
    <div class="pt-24 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 px-4 sm:px-0">Available Vouchers</h1>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 mx-4 sm:mx-0">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 mx-4 sm:mx-0">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            <!-- Voucher display section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
                @forelse ($vouchers as $voucher)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300">
                        <div class="relative">
                            <!-- Voucher image -->
                            @if($voucher->image)
                                <img src="{{ asset($voucher->image) }}" alt="Voucher {{ $voucher->code }}" class="w-full h-48 object-cover">
                            @else
                                <!-- Default voucher image if none exists -->
                                <div class="w-full h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-3xl font-bold text-white">{{ $voucher->discount_percentage }}% OFF</span>
                                </div>
                            @endif
                            
                            <!-- Discount badge -->
                            <div class="absolute top-0 right-0 bg-red-500 text-white font-bold py-1 px-3 rounded-bl-lg">
                                {{ $voucher->discount_percentage }}% OFF
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <!-- Voucher code section with copy functionality -->
                            <div class="flex items-center justify-between mb-4 bg-gray-100 p-3 rounded-lg">
                                <div class="font-mono font-bold text-gray-800">{{ $voucher->code }}</div>
                                <button 
                                    onclick="copyToClipboard('{{ $voucher->code }}')"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors duration-200">
                                    Copy
                                </button>
                            </div>
                            
                            <!-- Voucher description -->
                            <p class="text-gray-600 mb-6">{{ $voucher->description }}</p>
                            
                            <!-- Expiration date -->
                            @if($voucher->expired_at)
                                <div class="text-sm text-gray-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Expires: {{ \Carbon\Carbon::parse($voucher->expired_at)->format('M d, Y') }}
                                </div>
                            @else
                                <div class="text-sm text-gray-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    No expiration date
                                </div>
                            @endif
                            
                            <!-- Usage limit if applicable -->
                            @if($voucher->usage_limit)
                                <div class="mt-2 text-sm text-gray-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Remaining: {{ max(0, $voucher->usage_limit - $voucher->usage_count) }} uses
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 flex flex-col items-center justify-center bg-white rounded-lg shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-4 text-xl font-medium text-gray-900">No vouchers available</h3>
                        <p class="mt-1 text-gray-500">Check back later for new promotions and discounts.</p>
                    </div>
                @endforelse
            </div>
            
            <!-- How to use vouchers section -->
            <div class="mt-12 bg-white shadow-sm rounded-lg border border-gray-200 p-6 px-4 sm:px-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">How to Use Vouchers</h2>
                <ol class="list-decimal pl-5 space-y-2 text-gray-600">
                    <li>Copy the voucher code you want to use.</li>
                    <li>Apply the voucher code during checkout in the "Apply Voucher" field.</li>
                    <li>The discount will be automatically applied to your total.</li>
                    <li>Note that vouchers cannot be combined with other promotions.</li>
                </ol>
            </div>
        </div>
    </div>
    
    @include('layouts.footer')
    
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show a temporary tooltip or notification
                alert('Voucher code copied!');
            });
        }
    </script>
</x-app-layout>

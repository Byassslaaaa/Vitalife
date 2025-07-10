@extends('layouts.admin')

@section('judul-halaman', 'Edit Detail Spa')

@section('content')
    <div class="max-w-6xl mx-auto p-4 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Edit Detail Spa: {{ $spa->nama }}</h2>
            <a href="{{ route('admin.spa-details.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="spaDetailsForm" action="{{ route('admin.spa-details.update', $spa->id_spa) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-8">
                
                <!-- Gallery Images Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gallery Images (5 Photos)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        @for($i = 0; $i < 5; $i++)
                            <div class="space-y-2">
                                <label for="gallery_{{ $i }}" class="block text-sm font-medium text-gray-700">Photo {{ $i + 1 }}</label>
                                <input type="file" name="gallery_images[{{ $i }}]" id="gallery_{{ $i }}" 
                                       accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100"
                                       onchange="previewGalleryImage(event, {{ $i }})">
                                <div id="galleryPreview{{ $i }}" class="mt-2">
                                    @if($spa->spaDetail && isset($spa->spaDetail->gallery_images[$i]))
                                        <img src="{{ asset($spa->spaDetail->gallery_images[$i]) }}" alt="Gallery {{ $i + 1 }}" class="w-full h-32 object-cover rounded-lg">
                                    @else
                                        <img src="/placeholder.svg?height=128&width=200" alt="Preview" class="w-full h-32 object-cover rounded-lg bg-gray-200 hidden">
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Contact Person Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Person</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="contact_person_name" class="block text-sm font-medium text-gray-700">Contact Person Name *</label>
                            <input type="text" name="contact_person_name" id="contact_person_name" 
                                   value="{{ old('contact_person_name', $spa->spaDetail->contact_person_name ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                   placeholder="e.g., Jane Doe" required>
                            @error('contact_person_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_person_phone" class="block text-sm font-medium text-gray-700">Contact Person Phone *</label>
                            <input type="tel" name="contact_person_phone" id="contact_person_phone" 
                                   value="{{ old('contact_person_phone', $spa->spaDetail->contact_person_phone ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                   placeholder="e.g., +62812345678" required>
                            @error('contact_person_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-map-marker-alt text-pink-500 mr-2"></i>
                        Location & Maps
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="location_maps" class="block text-sm font-medium text-gray-700 mb-2">
                                Google Maps Embed Code
                            </label>
                            <textarea name="location_maps" id="location_maps" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                      placeholder="Paste the complete iframe embed code from Google Maps here...">{{ old('location_maps', $spa->maps ?? '') }}</textarea>
                            <div class="mt-2 text-sm text-gray-600">
                                <p class="mb-2"><strong>How to get Google Maps embed code:</strong></p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li>Go to <a href="https://maps.google.com" target="_blank" class="text-pink-600 hover:underline">Google Maps</a></li>
                                    <li>Search for your spa location</li>
                                    <li>Click "Share" button</li>
                                    <li>Click "Embed a map" tab</li>
                                    <li>Copy the entire iframe code and paste it here</li>
                                </ol>
                            </div>
                            @error('location_maps')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Maps Preview -->
                        <div id="mapsPreview" class="mt-4">
                            @if($spa->maps)
                                <div class="border rounded-lg p-4 bg-white">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Current Map Preview:</h4>
                                    <div class="w-full h-64 rounded-lg overflow-hidden">
                                        {!! $spa->maps !!}
                                    </div>
                                </div>
                            @else
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                    <i class="fas fa-map text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-gray-500">No map embedded yet. Paste your Google Maps iframe code above to see preview.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Additional Services Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Services (Beyond Original Services)</h3>
                    <div id="additionalServicesContainer">
                        @php
                            $additionalServices = old('additional_services', $spa->spaDetail->additional_services ?? []);
                        @endphp
                        @forelse($additionalServices as $index => $service)
                            <div class="additional-service-item mb-4 p-4 border border-gray-200 rounded-lg bg-white">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Service Name</label>
                                        <input type="text" name="additional_services[{{ $index }}][name]" 
                                               value="{{ $service['name'] ?? '' }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                               placeholder="e.g., Hot Stone Massage">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Service Description</label>
                                        <input type="text" name="additional_services[{{ $index }}][description]" 
                                               value="{{ $service['description'] ?? '' }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                               placeholder="Brief description of the service">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Service Photo (Circular)</label>
                                        <input type="file" name="additional_services[{{ $index }}][image]" 
                                               accept="image/*"
                                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100"
                                               onchange="previewAdditionalServiceImage(event, {{ $index }})">
                                        <div id="additionalServiceImagePreview{{ $index }}" class="mt-2">
                                            @if(isset($service['image']) && $service['image'])
                                                <img src="{{ asset($service['image']) }}" alt="Service {{ $index + 1 }}" class="w-16 h-16 object-cover rounded-full border-2 border-gray-300">
                                            @else
                                                <img src="/placeholder.svg" alt="Preview" class="w-16 h-16 object-cover rounded-full bg-gray-200 hidden border-2 border-gray-300">
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Photo will be displayed in circular form. Leave empty if you don't want to change photo.</p>
                                    </div>
                                </div>
                                <button type="button" class="mt-2 text-red-600 hover:text-red-800 text-sm" onclick="removeAdditionalService(this)">
                                    Remove Service
                                </button>
                            </div>
                        @empty
                            <div class="additional-service-item mb-4 p-4 border border-gray-200 rounded-lg bg-white">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Service Name</label>
                                        <input type="text" name="additional_services[0][name]" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                               placeholder="e.g., Hot Stone Massage">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Service Description</label>
                                        <input type="text" name="additional_services[0][description]" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                               placeholder="Brief description of the service">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Service Photo (Circular)</label>
                                        <input type="file" name="additional_services[0][image]" 
                                               accept="image/*"
                                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100"
                                               onchange="previewAdditionalServiceImage(event, 0)">
                                        <div id="additionalServiceImagePreview0" class="mt-2">
                                            <img src="/placeholder.svg" alt="Preview" class="w-16 h-16 object-cover rounded-full bg-gray-200 hidden border-2 border-gray-300">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Photo will be displayed in circular form</p>
                                    </div>
                                </div>
                                <button type="button" class="mt-2 text-red-600 hover:text-red-800 text-sm" onclick="removeAdditionalService(this)">
                                    Remove Service
                                </button>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" id="addAdditionalService" class="mt-2 bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
                        Add Another Service
                    </button>
                </div>

                <!-- About This Spa Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">About This Spa</h3>
                    <div>
                        <label for="about_spa" class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="about_spa" id="about_spa" rows="5"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                  placeholder="Write a detailed description about this spa..." required>{{ old('about_spa', $spa->spaDetail->about_spa ?? '') }}</textarea>
                        @error('about_spa')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Opening Hours Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Opening Hours (Monday to Sunday) *</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            $openingHours = old('opening_hours', $spa->waktuBuka ?? []);
                        @endphp
                        @foreach($days as $day)
                            <div>
                                <label for="opening_hours_{{ strtolower($day) }}" class="block text-sm font-medium text-gray-700">{{ $day }} *</label>
                                <input type="text" name="opening_hours[{{ $day }}]" id="opening_hours_{{ strtolower($day) }}"
                                       value="{{ $openingHours[$day] ?? '' }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                       placeholder="e.g., 08:00-22:00" required>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Facilities Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Facilities</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @php
                            $facilityOptions = [
                                'Private Treatment Rooms', 'Couple Treatment Room', 'Steam Room', 'Sauna', 
                                'Jacuzzi', 'Relaxation Area', 'Changing Room', 'Shower Facilities', 'Parking Area',
                                'WiFi', 'Air Conditioning', 'Aromatherapy', 'Hot Stone Therapy', 'Herbal Treatment',
                                'Reflexology', 'Body Scrub', 'Facial Treatment', 'Massage Therapy'
                            ];
                            $selectedFacilities = old('facilities', $spa->spaDetail->facilities ?? []);
                        @endphp
                        @foreach($facilityOptions as $facility)
                            <div class="flex items-center">
                                <input type="checkbox" name="facilities[]" value="{{ $facility }}" 
                                       id="{{ strtolower(str_replace([' ', '/'], ['_', '_'], $facility)) }}"
                                       {{ in_array($facility, $selectedFacilities) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                <label for="{{ strtolower(str_replace([' ', '/'], ['_', '_'], $facility)) }}" class="ml-2 text-sm text-gray-700">{{ $facility }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t pt-6">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.spa-details.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                            Cancel
                        </a>
                        <button type="submit" id="submitBtn"
                            class="inline-flex items-center px-4 py-2 bg-pink-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-700 active:bg-pink-800 focus:outline-none focus:border-pink-800 focus:ring focus:ring-pink-300 disabled:opacity-25 transition">
                            <svg id="submitIcon" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div id="loadingSpinner" class="hidden w-4 h-4 mr-2 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span id="submitText">Update Spa Details</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let additionalServiceIndex = {{ count(old('additional_services', $spa->spaDetail->additional_services ?? [])) }};

        // Form submission with loading state
        document.getElementById('spaDetailsForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitIcon = document.getElementById('submitIcon');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const submitText = document.getElementById('submitText');
            
            console.log('Form submission started');
            
            // Validate required fields first
            const requiredFields = ['contact_person_name', 'contact_person_phone', 'about_spa'];
            let isValid = true;
            
            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    console.log('Field validation failed:', fieldName);
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            // Validate opening hours
            const days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
            days.forEach(day => {
                const field = document.getElementById(`opening_hours_${day}`);
                if (field && !field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    console.log('Opening hours validation failed:', day);
                } else if (field) {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi (ditandai dengan *)');
                console.log('Form validation failed');
                return false;
            }
            
            console.log('Form validation passed, showing loading state');
            
            // Show loading state - form will submit normally after this
            submitBtn.disabled = true;
            submitIcon.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');
            submitText.textContent = 'Updating...';
            submitBtn.classList.add('opacity-75');
            
            // Form will submit normally - don't prevent default
            return true;
        });

        function previewGalleryImage(event, index) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector(`#galleryPreview${index} img`);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewAdditionalServiceImage(event, index) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector(`#additionalServiceImagePreview${index} img`);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function addAdditionalService() {
            const container = document.getElementById('additionalServicesContainer');
            const newService = document.createElement('div');
            newService.className = 'additional-service-item mb-4 p-4 border border-gray-200 rounded-lg bg-white';
            newService.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Service Name</label>
                        <input type="text" name="additional_services[${additionalServiceIndex}][name]" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                               placeholder="e.g., Hot Stone Massage">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Service Description</label>
                        <input type="text" name="additional_services[${additionalServiceIndex}][description]" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                               placeholder="Brief description of the service">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Service Photo (Circular)</label>
                        <input type="file" name="additional_services[${additionalServiceIndex}][image]" 
                               accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100"
                               onchange="previewAdditionalServiceImage(event, ${additionalServiceIndex})">
                        <div id="additionalServiceImagePreview${additionalServiceIndex}" class="mt-2">
                            <img src="/placeholder.svg" alt="Preview" class="w-16 h-16 object-cover rounded-full bg-gray-200 hidden border-2 border-gray-300">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Photo will be displayed in circular form</p>
                    </div>
                </div>
                <button type="button" class="mt-2 text-red-600 hover:text-red-800 text-sm" onclick="removeAdditionalService(this)">
                    Remove Service
                </button>
            `;
            container.appendChild(newService);
            additionalServiceIndex++;
        }

        function removeAdditionalService(button) {
            button.closest('.additional-service-item').remove();
        }

        document.getElementById('addAdditionalService').addEventListener('click', addAdditionalService);

        document.getElementById('location_maps').addEventListener('input', function(e) {
            const mapsCode = e.target.value.trim();
            const previewContainer = document.getElementById('mapsPreview');
            
            if (mapsCode && mapsCode.includes('<iframe') && mapsCode.includes('</iframe>')) {
                previewContainer.innerHTML = `
                    <div class="border rounded-lg p-4 bg-white">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Map Preview:</h4>
                        <div class="w-full h-64 rounded-lg overflow-hidden">
                            ${mapsCode}
                        </div>
                    </div>
                `;
            } else if (mapsCode) {
                previewContainer.innerHTML = `
                    <div class="border-2 border-dashed border-yellow-300 rounded-lg p-8 text-center bg-yellow-50">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-2"></i>
                        <p class="text-yellow-700">Please paste a complete iframe embed code from Google Maps</p>
                    </div>
                `;
            } else {
                previewContainer.innerHTML = `
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                        <i class="fas fa-map text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500">No map embedded yet. Paste your Google Maps iframe code above to see preview.</p>
                    </div>
                `;
            }
        });
    </script>

    <style>
        .border-red-500 {
            border-color: #ef4444 !important;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
@endsection
        
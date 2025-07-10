<x-admin-layout>
    <x-slot name="title">Edit Yoga Detail: {{ $yoga->nama }}</x-slot>
    
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Yoga Detail Page</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $yoga->nama }}</p>
                    </div>
                    <a href="{{ route('admin.yoga-details.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.yoga-details.update', $yoga->id_yoga) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Hero Section -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Hero Section</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                                <input type="text" name="hero_title" id="hero_title"
                                       value="{{ old('hero_title', $yoga->detailConfig->hero_title ?? '') }}"
                                       placeholder="Leave empty to use yoga name"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('hero_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                                <input type="text" name="hero_subtitle" id="hero_subtitle"
                                       value="{{ old('hero_subtitle', $yoga->detailConfig->hero_subtitle ?? '') }}"
                                       placeholder="Find your inner peace and strength"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('hero_subtitle')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Gallery Images</h3>
                        
                        <div class="mb-4">
                            <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">Upload New Images</label>
                            <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-sm text-gray-500 mt-1">Select multiple images for the gallery. Recommended size: 800x600px</p>
                            @error('gallery_images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($yoga->detailConfig && $yoga->detailConfig->gallery_images)
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Current Gallery Images</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($yoga->detailConfig->gallery_images as $image)
                                        <div class="relative">
                                            <img src="{{ asset($image) }}" alt="Gallery Image" class="w-full h-24 object-cover rounded-lg">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Facilities -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Facilities</h3>
                        
                        <div id="facilities-container" class="space-y-4">
                            @if($yoga->detailConfig && $yoga->detailConfig->facilities)
                                @foreach($yoga->detailConfig->facilities as $index => $facility)
                                    <div class="facility-item p-4 border border-gray-200 rounded-lg">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                                                <input type="text" name="facilities[{{ $index }}][title]" 
                                                       value="{{ $facility['title'] ?? '' }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                                                <input type="text" name="facilities[{{ $index }}][icon]" 
                                                       value="{{ $facility['icon'] ?? '' }}"
                                                       placeholder="fa-solid fa-person-walking"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                                <textarea name="facilities[{{ $index }}][description]" rows="2"
                                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $facility['description'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <button type="button" onclick="removeFacility(this)" 
                                                class="mt-2 text-red-600 hover:text-red-800 text-sm">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Remove Facility
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <button type="button" onclick="addFacility()" 
                                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Facility
                        </button>
                    </div>
                </div>

                <!-- Booking Policy -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Booking Policy</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="booking_policy_title" class="block text-sm font-medium text-gray-700 mb-2">Policy Title</label>
                                <input type="text" name="booking_policy_title" id="booking_policy_title"
                                       value="{{ old('booking_policy_title', $yoga->detailConfig->booking_policy_title ?? 'BOOKING POLICY') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="booking_policy_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Policy Subtitle</label>
                                <input type="text" name="booking_policy_subtitle" id="booking_policy_subtitle"
                                       value="{{ old('booking_policy_subtitle', $yoga->detailConfig->booking_policy_subtitle ?? 'YOUR WELLNESS PLANS') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display Options -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Display Options</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="show_opening_hours" value="1" id="show_opening_hours"
                                       {{ old('show_opening_hours', $yoga->detailConfig->show_opening_hours ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <label for="show_opening_hours" class="ml-2 text-sm text-gray-700">Show Opening Hours Section</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="show_location_map" value="1" id="show_location_map"
                                       {{ old('show_location_map', $yoga->detailConfig->show_location_map ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <label for="show_location_map" class="ml-2 text-sm text-gray-700">Show Location Map</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="show_class_types" value="1" id="show_class_types"
                                       {{ old('show_class_types', $yoga->detailConfig->show_class_types ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <label for="show_class_types" class="ml-2 text-sm text-gray-700">Show Class Types</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Theme & Style -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Theme & Style</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="theme_color" class="block text-sm font-medium text-gray-700 mb-2">Theme Color</label>
                                <input type="color" name="theme_color" id="theme_color"
                                       value="{{ old('theme_color', $yoga->detailConfig->theme_color ?? '#10B981') }}"
                                       class="h-10 w-20 rounded border border-gray-300">
                            </div>
                            
                            <div>
                                <label for="layout_style" class="block text-sm font-medium text-gray-700 mb-2">Layout Style</label>
                                <select name="layout_style" id="layout_style"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="default" {{ old('layout_style', $yoga->detailConfig->layout_style ?? 'default') == 'default' ? 'selected' : '' }}>Default</option>
                                    <option value="modern" {{ old('layout_style', $yoga->detailConfig->layout_style ?? 'default') == 'modern' ? 'selected' : '' }}>Modern</option>
                                    <option value="minimal" {{ old('layout_style', $yoga->detailConfig->layout_style ?? 'default') == 'minimal' ? 'selected' : '' }}>Minimal</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom CSS -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Custom CSS</h3>
                        
                        <div>
                            <label for="custom_css" class="block text-sm font-medium text-gray-700 mb-2">Additional CSS</label>
                            <textarea name="custom_css" id="custom_css" rows="6" 
                                      class="w-full rounded-md border-gray-300 shadow-sm font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="/* Add custom CSS here */">{{ old('custom_css', $yoga->detailConfig->custom_css ?? '') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Add custom CSS to override default styles</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.yoga-details.preview', $yoga->id_yoga) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let facilityIndex = {{ $yoga->detailConfig && $yoga->detailConfig->facilities ? count($yoga->detailConfig->facilities) : 0 }};

        function addFacility() {
            const container = document.getElementById('facilities-container');
            const facilityHtml = `
                <div class="facility-item p-4 border border-gray-200 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="facilities[${facilityIndex}][title]" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                            <input type="text" name="facilities[${facilityIndex}][icon]" 
                                   placeholder="fa-solid fa-person-walking"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="facilities[${facilityIndex}][description]" rows="2"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                    <button type="button" onclick="removeFacility(this)" 
                            class="mt-2 text-red-600 hover:text-red-800 text-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Remove Facility
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', facilityHtml);
            facilityIndex++;
        }

        function removeFacility(button) {
            button.closest('.facility-item').remove();
        }
    </script>
</x-admin-layout>

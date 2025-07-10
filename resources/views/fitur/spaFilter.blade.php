<x-app-layout>
    @include('layouts.pencarian')
    {{-- slide 2 --}}
    <div class="flex justify-center items-center">
        <div class="font-sans">
            <div class="container mx-auto py-4 grid grid-cols-2 gap-5">
                <div class="bg-white rounded-lg shadow-2xl p-12">
                    <!-- spa1 -->
                    @foreach ($spaTotal as $spa)
                        <div class="flex">
                            <div class="flex items-center border-b border-gray-500 pb-2">
                                <div class="w-16 h-16 rounded-full bg-gray-200 mr-6"></div>
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $spa->nama }}</h2>
                                    <p class="text-gray-500 text-lg">Relaxation</p>
                                    <p class="text-gray-500 text-lg">16 years experience overall</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 bg-green-100 px-6 py-3 rounded-md inline-flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fillRule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clipRule="evenodd" />
                            </svg>
                            <span class="text-green-500 font-medium text-lg">99% 93 Patient Stories</span>
                        </div>
                        <div class="mt-6">
                            <p class="text-gray-500 text-lg">{{ $spa->alamat }}</p>
                            <p class="text-gray-500 text-lg">The most famous relaxation spa</p>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-lg">more</a>
                        </div>
                        <div class="mt-6">
                            <p class="text-gray-500 text-lg">Rp.{{ number_format($spa->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-6">
                            <p class="text-gray-500 text-lg">{{ $spa->noHP }}</p>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded text-lg mr-4">Book
                                FREE Spa Visit</button>
                            <button
                                class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded text-lg">Schedule</button>
                        </div>

                        <!-- Available -->
                        <div class="mt-10">
                            <h3 class="text-xl font-bold">Available Today</h3>
                            <div class="grid grid-cols-3 gap-6 mt-6">
                                <div>
                                    <p class="text-m text-gray-500 text-lg">{{ $spa->waktuBuka }}</p>
                                </div>
                                {{-- <div>
                                    <p class="text-gray-500 text-lg">Morning</p>
                                    <p class="font-medium text-lg">11:30 AM</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-lg">Afternoon</p>
                                    <p class="font-medium text-lg">12:00 PM</p>
                                    <p class="font-medium text-lg">12:30 PM</p>
                                    <p class="font-medium text-lg">01:30 PM</p>
                                    <p class="font-medium text-lg">02:00 PM</p>
                                    <p class="font-medium text-lg">02:30 PM</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-lg">Evening</p>
                                    <p class="font-medium text-lg">06:00 PM</p>
                                    <p class="font-medium text-lg">06:30 PM</p>
                                    <p class="font-medium text-lg">07:00 PM</p>
                                    <p class="font-medium text-lg">07:30 PM</p>
                                </div> --}}
                            </div>
                        </div>
                </div>
                @endforeach
                <div>
                    <h2 class="text-2xl font-bold mb-6">Maps Location</h2>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15813.61369824441!2d110.33927410840992!3d-7.746962888130327!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a58f2f0a9180d%3A0x65615dadf02a4713!2sAnjani%20Spa!5e0!3m2!1sen!2sid!4v1717088008917!5m2!1sen!2sid"
                        width="100%" height="400" style="border: none" allowFullScreen="" loading="lazy"
                        referrerPolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')
</x-app-layout>

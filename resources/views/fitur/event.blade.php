<x-app-layout>
    {{-- @include('layouts.carievent') --}}
    <div class="flex justify-center items-center pt-24">
        <div class="bg-gray-100 rounded-2xl shadow-lg w-full max-w-5xl p-8">
            <form action="{{ route('search.events') }}" method="GET">
                <div class="flex justify-between items-center mb-6 w-full">
                    <!-- Pencarian Event Wisata Kesehatan -->
                    <div class="flex-grow mr-4">
                        <input type="text" name="nama_event" id="nama_event"
                            class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                            placeholder="Cari event wisata kesehatan" />
                    </div>

                    <!-- Tombol Cari -->
                    <div>
                        <button type="submit"
                            class="bg-blue-600 text-white rounded-md py-1.5 px-6 text-sm hover:bg-blue-700 transition duration-300">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="text-sm">Cari</span>
                            </div>
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap justify-start items-center w-full gap-4">
                    <!-- Filter Lokasi -->
                    <div class="w-64">
                        <select name="lokasi" id="lokasi"
                            class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600">
                            <option value="">Pilih Lokasi</option>
                            <option value="sleman">Sleman</option>
                            <option value="kota_yogyakarta">Kota Yogyakarta</option>
                            <option value="bantul">Bantul</option>
                            <option value="kulon_progo">Kulon Progo</option>
                            <option value="gunung_kidul">Gunung Kidul</option>
                        </select>
                    </div>

                    <!-- Filter Tanggal -->
                    <div class="flex space-x-2">
                        <div class="w-36">
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                                placeholder="Tanggal Mulai" />
                        </div>
                        <div class="w-36">
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                                class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                                placeholder="Tanggal Akhir" />
                        </div>
                    </div>

                    <!-- Filter Jenis Event Kesehatan -->
                    <div class="w-64">
                        <select name="jenis_event" id="jenis_event"
                            class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600">
                            <option value="">Jenis Event Kesehatan</option>
                            <option value="retret_yoga">Retret Yoga</option>
                            <option value="workshop_meditasi">Workshop Meditasi</option>
                            <option value="hari_spa">Hari Spa</option>
                            <option value="bootcamp_kebugaran">Bootcamp Kebugaran</option>
                            <option value="seminar_nutrisi">Seminar Nutrisi</option>
                            <option value="sesi_penyembuhan_holistik">Sesi Penyembuhan Holistik</option>
                            <option value="festival_kesehatan">Festival Kesehatan</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="flex items-center pt-8 pl-36">
        <h3 class="text-3xl font-bold text-gray-800">Event</h3>
    </div>

    <div class="flex flex-col items-center mt-8 mx-auto px-4 sm:px-6 lg:px-8 pb-4">
        @if ($eventTotal->isEmpty())
            <p class="text-gray-600">Tidak ada event yang ditemukan.</p>
        @else
            @foreach ($eventTotal as $event)
                <div class="bg-white border rounded-lg p-4 mb-4 w-full max-w-5xl">
                    <div class="flex flex-row items-start space-x-4">
                        <img src="{{ asset($event->image) }}" alt="{{ $event->nama }}"
                            class="w-40 h-28 object-cover rounded-md flex-shrink-0" />
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">{{ $event->nama }}</h3>
                            <p class="text-gray-600">{{ $event->deskripsi }}</p>
                            <p class="text-gray-600">{{ $event->tanggal }}</p>
                            <p class="text-gray-600">{{ $event->alamat }}</p>
                            <p class="text-gray-600">{{ number_format($event->harga, 0, ',', '.') }} IDR</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="text-green-500 font-bold">{{ $event->noHP }}</span>
                            </div>
                            <div class="mt-4 text-right">
                                {{-- <a href="{{ route('events.show', $event->id) }}"
                                    class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    Lihat Detail Event
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @include('layouts.footer')
</x-app-layout>
<!-- Check -->
{{-- <div class="flex flex-col max-w-xs">
            <!-- Check box -->
            <div class="bg-white border rounded-lg p-4 flex-1">
                <div class="space-y-4">
                    <div class="flex items-center justify-start">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="event-type" value="all-event" class="form-radio text-blue-500"
                                checked>
                            <span class="text-gray-900 font-medium">All event</span>
                        </label>
                    </div>
                    <div class="flex items-center justify-start">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="event-type" value="about-checkout"
                                class="form-radio text-blue-500">
                            <span class="text-gray-900 font-medium">About Checkout</span>
                        </label>
                    </div>
                    <p class="text-sm text-gray-600">Show only the events available for registration on VitaLife.
                        Quick
                        and easy.</p>
                </div>
            </div>

            <!-- Option (outside the check box) -->
            <div class="mt-4">
                <div class="flex flex-wrap gap-2">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Running
                        <button>
                            X
                        </button>
                    </span>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Marathon
                        <button>
                            X
                        </button>
                    </span>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Indonesia
                        <button>
                            X
                        </button>
                    </span>
                </div>
                <div class="mt-2 text-left">
                    <button class="text-blue-500 text-sm font-medium">Clear all</button>
                </div>
            </div>
            <div class="bg-white border rounded-lg p-4 mt-5">
                <div class="space-y-4">
                    <div>
                        <h2 class="font-semibold text-lg mb-2">Sport</h2>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="sport" class="form-radio text-blue-500" checked>
                                <span class="ml-2">Running</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="sport" class="form-radio text-blue-500">
                                <span class="ml-2">Trail running</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="sport" class="form-radio text-blue-500">
                                <span class="ml-2">Dog run</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <h2 class="font-semibold text-lg mb-2">Other sport</h2>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="other-sport" class="form-radio text-blue-500">
                                <span class="ml-2">Trail running</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="other-sport" class="form-radio text-blue-500">
                                <span class="ml-2">Dog run</span>
                            </label>
                        </div>
                        <button class="text-blue-500 text-sm mt-2">Show more</button>
                    </div>

                    <div>
                        <h2 class="font-semibold text-lg mb-2">Distance</h2>
                        <div class="flex items-center mb-2">
                            <input type="checkbox" class="form-checkbox text-blue-500 mr-2">
                            <span>Apply custom range</span>
                        </div>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="distance" class="form-radio text-blue-500" checked>
                                <span class="ml-2">Marathon</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="distance" class="form-radio text-blue-500">
                                <span class="ml-2">Ultramarathon</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="distance" class="form-radio text-blue-500">
                                <span class="ml-2">Half marathon</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="distance" class="form-radio text-blue-500">
                                <span class="ml-2">5 Km</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="distance" class="form-radio text-blue-500">
                                <span class="ml-2">10 Km</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="distance" class="form-radio text-blue-500">
                                <span class="ml-2">Fixed time race</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="distance" class="form-radio text-blue-500">
                                <span class="ml-2">No distance race</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

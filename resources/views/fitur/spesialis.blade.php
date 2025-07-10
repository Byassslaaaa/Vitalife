<x-app-layout>

    {{-- pencarian --}}
    <div class="flex justify-center items-center pt-24">
        <div class="bg-gray-100 rounded-2xl shadow-lg w-full max-w-4xl p-8">
            <!-- Search form -->
            <form action="{{ route('spesialis') }}" method="GET" class="space-y-6">
                <div class="flex justify-start items-center w-full space-x-4">
                    <div class="flex-grow">
                        <input type="text" name="nama" id="nama"
                            class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                            placeholder="Enter specialist name" value="{{ request('nama') }}" />
                    </div>
                    <button type="submit" class="bg-blue-600 text-white rounded-md py-2 px-6 text-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Search</span>
                    </button>
                </div>
                <!-- Price range filter -->
                <div class="flex justify-start items-center w-full space-x-4">
                    <label for="min_price" class="text-sm font-medium text-gray-700">Price Range:</label>
                    <input type="number" id="min_price" name="min_price"
                        class="rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                        placeholder="Min" value="{{ request('min_price') }}" />
                    <span class="text-gray-500">-</span>
                    <input type="number" id="max_price" name="max_price"
                        class="rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                        placeholder="Max" value="{{ request('max_price') }}" />
                </div>
            </form>
        </div>
    </div>

    <div class="flex items-center pl-48 pt-8">
        <h3 class="text-3xl font-bold text-gray-800">Spesialist</h3>
    </div>

    <div class="flex justify-center items-start mt-8 mx-auto px-48 mb-10">
        <div class="bg-white border rounded-lg p-8 mr-8 w-64">
            <h2 class="font-bold mb-4 text-lg">Location</h2>
            <hr class="w-full border-gray-300 mb-4" />
            <form id="locationForm" action="{{ route('spesialis') }}" method="GET">
                <!-- Include other existing search parameters -->
                <input type="hidden" name="nama" value="{{ request('nama') }}">
                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                <input type="hidden" name="max_price" value="{{ request('max_price') }}">

                <div class="space-y-2 text-sm">
                    <div class="flex items-center">
                        <input type="radio" id="di-yogyakarta" name="location" value="D.I Yogyakarta" class="mr-2"
                            onchange="this.form.submit()"
                            {{ request('location') == 'D.I Yogyakarta' ? 'checked' : '' }}>
                        <label for="di-yogyakarta">D.I Yogyakarta</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="sleman" name="location" value="Sleman" class="mr-2"
                            onchange="this.form.submit()" {{ request('location') == 'Sleman' ? 'checked' : '' }}>
                        <label for="sleman">Sleman</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="bantul" name="location" value="Bantul" class="mr-2"
                            onchange="this.form.submit()" {{ request('location') == 'Bantul' ? 'checked' : '' }}>
                        <label for="bantul">Bantul</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="kulon-progo" name="location" value="Kulon Progo" class="mr-2"
                            onchange="this.form.submit()" {{ request('location') == 'Kulon Progo' ? 'checked' : '' }}>
                        <label for="kulon-progo">Kulon Progo</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="Gunung-Kidul" name="location" value="Gunung Kidul" class="mr-2"
                            onchange="this.form.submit()" {{ request('location') == 'Gunung Kidul' ? 'checked' : '' }}>
                        <label for="Gunung-Kidul">Gunung Kidul</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-5xl mx-auto">
            @foreach ($spesLihat as $spesialis)
            <div class="flex flex-col md:flex-row items-center md:items-start border rounded-lg p-6 mb-6">
                <div
                    class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-gray-200 mb-4 md:mb-0 md:mr-8 overflow-hidden flex-shrink-0">
                    <img src="{{ asset($spesialis->image) }}" alt="Profile Picture" class="w-full h-full object-cover">
                </div>
                <div class="flex-grow text-center md:text-left">
                    <h2 class="text-2xl font-bold mb-2">{{ $spesialis->nama }}</h2>
                    <p class="text-gray-600 text-xl mb-1">{{ $spesialis->Anatomy }}</p>
                    <p class="text-gray-600 text-lg font-semibold mb-1">{{ $spesialis->spesialisasi }}</p>
                    <p class="text-gray-600 text-sm">{{ $spesialis->tempatTugas }}</p>
                    <p class="text-gray-600 text-sm">{{ $spesialis->alamat }}</p>
                </div>
                <div class="mt-4 md:mt-0 md:ml-8 text-center md:text-right">
                    <p class="text-gray-800 text-xl font-semibold mb-3">
                        Rp.{{ number_format($spesialis->harga, 0, ',', '.') }}
                    </p>
                    <a href="{{ route('spesialis.bayar', ['id_spesialis' => $spesialis->id_spesialis]) }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded text-base inline-block">
                        Bayar
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <hr class="w-full border-gray-300 mb-6" />
    </div>

    @include('layouts.footer')
</x-app-layout>
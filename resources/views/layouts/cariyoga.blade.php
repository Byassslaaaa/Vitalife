<div class="flex justify-center items-center pt-24">
    <div class="bg-gray-100 rounded-2xl shadow-lg w-full max-w-5xl p-8">
        <form action="#" method="GET">
            <div class="flex justify-between items-center mb-6 w-full">
                <!-- Yoga Place Search -->
                <div class="flex-grow mr-4">
                    <input type="text" name="yoga_place" id="yoga_place"
                        class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                        placeholder="Search for yoga places" />
                </div>

                <!-- Search Button -->
                <div>
                    <button type="submit"
                        class="bg-blue-600 text-white rounded-md py-1.5 px-6 text-sm hover:bg-blue-700 transition duration-300">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="text-sm">Search</span>
                        </div>
                    </button>
                </div>
            </div>

            <div class="flex flex-wrap justify-start items-center w-full gap-4">
                <!-- Location Filter -->
                <div class="w-64">
                    <select name="location" id="location"
                        class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600">
                        <option value="">Select Location</option>
                        <option value="sleman">Sleman</option>
                        <option value="kota_yogyakarta">Kota Yogyakarta</option>
                        <option value="bantul">Bantul</option>
                        <option value="kulon_progo">Kulon Progo</option>
                        <option value="gunung_kidul">Gunung Kidul</option>
                    </select>
                </div>

                <!-- Price Range Filter -->
                <div class="w-64">
                    <select name="price_range" id="price_range"
                        class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600">
                        <option value="">Price Range</option>
                        <option value="0-50">$0 - $50</option>
                        <option value="51-100">$51 - $100</option>
                        <option value="101-200">$101 - $200</option>
                        <option value="201+">$201+</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

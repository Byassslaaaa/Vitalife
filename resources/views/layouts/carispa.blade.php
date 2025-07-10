<div class="flex justify-center items-center pt-24">
    <div class="bg-gray-100 rounded-2xl shadow-lg w-full max-w-5xl p-8">
        <form action="#" method="GET">
            <div class="flex justify-between items-center mb-6 w-full">
                <!-- Spa Place Search -->
                <div class="flex-grow mr-4">
                    <input type="text" name="spa_place" id="spa_place"
                        class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                        placeholder="Search for spa places" />
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
                        <option value="0-100000">Rp 0 - Rp 100,000</option>
                        <option value="100001-250000">Rp 100,001 - Rp 250,000</option>
                        <option value="250001-500000">Rp 250,001 - Rp 500,000</option>
                        <option value="500001+">Rp 500,001+</option>
                    </select>
                </div>

                <!-- Spa Type Filter -->
                <div class="w-64">
                    <select name="spa_type" id="spa_type"
                        class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600">
                        <option value="">Spa Type</option>
                        <option value="massage">Massage</option>
                        <option value="facial">Facial</option>
                        <option value="body_treatment">Body Treatment</option>
                        <option value="aromatherapy">Aromatherapy</option>
                        <option value="hot_stone">Hot Stone Therapy</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

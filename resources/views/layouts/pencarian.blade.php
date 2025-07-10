<div class="flex justify-center items-center pt-24">
    <div class="bg-gray-100 rounded-2xl shadow-lg w-full max-w-4xl p-8">
        <!-- Search form -->
        <form class="space-y-6">
            <!-- Location input -->
            <div class="flex justify-start items-center w-full">
                <div class="flex-grow mr-4">
                    <input type="text" name="location" id="location"
                        class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                        placeholder="Enter location" />
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
                <label for="min-price" class="text-sm font-medium text-gray-700">Price Range:</label>
                <input type="number" id="min-price" name="min-price"
                    class="rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                    placeholder="Min" />
                <span class="text-gray-500">-</span>
                <input type="number" id="max-price" name="max-price"
                    class="rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600"
                    placeholder="Max" />
            </div>

            <!-- Sort options -->
            {{-- <div class="flex justify-start items-center w-full">
                <label for="sort" class="text-sm font-medium text-gray-700 mr-2">Sort By:</label>
                <select id="sort" name="sort"
                    class="rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600">
                    <option value="relevance">Relevance</option>
                    <option value="price-low-high">Price: Low to High</option>
                    <option value="price-high-low">Price: High to Low</option>
                    <option value="name-asc">Name: A to Z</option>
                    <option value="name-desc">Name: Z to A</option>
                </select>
            </div> --}}
        </form>
    </div>
</div>

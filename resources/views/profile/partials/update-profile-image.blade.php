<div class="max-w-5xl mx-auto p-4 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-semibold mb-4">Upload Picture</h2>
    <form action="{{ route('profile.update.image') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
                <div class="mt-1 flex items-center">
                    <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                        <img src="admin/{{ Auth::user()->image }}" alt="Profile Photo"
                            class="h-12 w-12 object-cover">
                    </span>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        onchange="previewImage(event)">
                </div>
                <div id="imagePreview" class="mt-2">
                    <img src="" alt="Preview" class="w-32 h-32 object-cover rounded-full bg-gray-200 hidden">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    Simpan Data
                </button>
            </div>
        </div>
    </form>
    <ul>
    </ul>
</div>

@if(session('image'))
    <img src="{{ asset('images/' . session('photo')) }}" alt="Profile Photo
@endif

<div id="preview"></div>

<script>
    const photoInput = document.getElementById('image');
    const saveButton = document.getElementById('save-button');
    const previewDiv = document.getElementById('preview');

    photoInput.addEventListener('change', () => {
        const file = photoInput.files[0];
        const reader = new FileReader();

        reader.onload = () => {
            const img = document.createElement('img');
            img.src = reader.result;
            previewDiv.innerHTML = '';
            previewDiv.appendChild(img);
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    });

    saveButton.addEventListener('click', () => {
        // Add your save logic here
    });
</script>
<x-guest-layout>
    <div class="grid grid-cols-2 min-h-screen bg-gray-100">
        <!-- Left Column -->
        <div class="relative flex flex-col justify-center items-center px-6 text-white min-h-screen bg-cover bg-center rounded-l-lg"
            style="background-image: url('../image/login.jpg');">
            <div class="absolute inset-0 bg-gradient-to-l from-blue-700 to-transparent opacity-70"></div>
            <div class="absolute top-4 left-4 z-20 px-4 py-28">
                <img src="../image/LOGO_1.png" alt="Logo" class="w-32 h-auto">
            </div>
            <div class="relative z-10 text-start px-3">
                <h2 class="text-4xl font-bold mb-4">Hello, Welcome</h2>
                <p class="mb-8">Hello healthy traveller friends, register an account then continue travelling</p>
                <!-- <a href="{{ route('register') }}"
                    class="inline-block border border-white text-white py-2 px-8 rounded-full hover:bg-white hover:text-black transition duration-300">
                    SIGN UP
                </a> -->
            </div>
        </div>

        <!-- Right Column - Create Account -->
        <div class="flex flex-col justify-center items-center px-6 bg-white">
            <div class="w-full max-w-md">
                <h2 class="text-3xl font-bold mb-8 text-emerald-500">Sign in to Vitalife</h2>

                <!-- Social Media Links -->
                <div class="flex space-x-6 mb-6">
                    <a href="{{ url('auth/google') }}" class="text-gray-400 hover:text-red-500"
                        title="Login with Google">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.24 10.285V14.4h6.806c-.275 1.765-2.056 5.174-6.806 5.174-4.095 0-7.439-3.389-7.439-7.574s3.345-7.574 7.439-7.574c2.33 0 3.891.989 4.785 1.849l3.254-3.138C18.189 1.186 15.479 0 12.24 0c-6.635 0-12 5.365-12 12s5.365 12 12 12c6.926 0 11.52-4.869 11.52-11.726 0-.788-.085-1.39-.189-1.989H12.24z" />
                        </svg>
                    </a>
                    <a href="{{ url('auth/facebook') }}" class="text-gray-400 hover:text-blue-600"
                        title="Login with Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                </div>

                <p class="text-gray-500 mb-6">or use your email account:</p>
                <!-- Registration Form -->
                <form id="loginForm" method="POST" action="{{ route('login') }}" class="w-full">
                    @csrf
                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <!-- Password -->
                    <div class="mt-4 relative">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-inputPW id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex justify-between items-center mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded dark:bg-white border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-900 dark:text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                        @endif
                    </div>

                    <div class="mt-4 flex flex-col items-center">
                        <div class="w-full">
                            <button type="submit"
                                class="w-full bg-blue-500 text-white py-2 px-4 rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 transition duration-150 ease-in-out uppercase font-semibold transform active:scale-90 active:bg-blue-700"
                                onclick="animateButton(this)">
                                {{ __('Log in') }}
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Tambahan: Link untuk registrasi -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        do you have an account? 
                        <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700 font-medium">
                            sign up now
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Periksa apakah semua field yang diperlukan sudah diisi
                const requiredFields = form.querySelectorAll('[required]');
                let allFilled = true;
                requiredFields.forEach(field => {
                    if (!field.value) {
                        allFilled = false;
                    }
                });

                if (!allFilled) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Silakan isi semua field yang diperlukan!',
                    });
                } else {
                    // Kirim form menggunakan AJAX
                    fetch(form.action, {
                            method: form.method,
                            body: new FormData(form),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                }).then(() => {
                                    window.location.href = data.redirect;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Login Gagal',
                                    text: data.message ||
                                        'Terjadi kesalahan. Silakan coba lagi.',
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi.',
                            });
                        });
                }
            });
        });
    </script>
</x-guest-layout>
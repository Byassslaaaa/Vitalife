<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body>
    <section
        class="h-screen bg-white dark:bg-gray-900 relative absolute top-0 left-0 right-0 bottom-0 bg-cover bg-center flex items-center"
        style="background-image: url('../image/bgspa.jpg');" id="background-section">
        <div class="absolute top-0 left-0 right-0 bottom-0 bg-black bg-opacity-60 -z-10 animate-fadeIn">
            <div class="container px-4 mx-auto pt-40">
                <div class="max-w-5xl flex flex-col items-center text-center mx-auto mb-24 animate-fadeIn">
                    <div>
                        <h1 class="text-3xl font-bold leading-tight md:text-6xl mb-6 text-white animate-fadeIn">What is
                            <span
                                class="text-blue-300 ml-2 my-4 text-6xl font-bold leading-tight animate-fadeIn">Vitalife</span>?
                        </h1>

                        <!-- Login and Register buttons -->
                        <div class="mt-6 flex justify-center space-x-4 w-full">
                            @if (auth()->user())
                                <a href="{{ route('dashboard') }}"
                                    class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 px-6 py-3 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white rounded-md">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 px-6 py-3 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white rounded-md">Log
                                    in</a>
                                <a href="{{ route('register') }}"
                                    class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 px-6 py-3 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white rounded-md">Register</a>
                            @endif
                        </div>
                        <div class="max-w-lg flex justify-center mx-auto animate-fadeIn">
                            <div class="text-center text-white">
                                <p class="text-xl opacity-80 leading-snug animate-fadeIn">Project Vitalife is a mobile application development project "Vitalife" which aims to increase health and fitness tourism in Indonesia. This application will help users in finding the best yoga and spa centers, latest sports and events, consult doctors, track their progress, and get feedback about the application's services.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <section class="ezy__copyright10 light py-12 bg-gray-800 dark:bg-gray-900 text-white dark:text-white">
            <div class="container px-4">
                <div class="grid grid-cols-12">
                    <div class="col-span-12 md:col-span-8 md:col-start-3">
                        <div class="flex flex-col justify-center items-center text-center">
                            <div class="flex items-center justify-center mb-6">
                                <div>
                                    <img src="../image/LOGO_1.png" alt="" class="max-w-full h-auto w-24 h-24">
                                </div>
                                <div>
                                    <p class="ml-3">&copy; Copyright {{ date('Y') }}</p>
                                </div>
                            </div>
                            <p class="opacity-50 mb-6">Isheaven male their dry doesn't without him set saw two him man
                                itself second fifth light over fish over which creepeth void don't. Image darkness
                                gathering. All hath don't it, abundantly darkness can't forth appear, in.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </footer>
    <script>
        const backgroundSection = document.getElementById('background-section');
        const images = [
            '../image/bgspa.jpg',
            '../image/bgyoga3.jpg',
            '../image/bgrun.jpg',
        ];
        let currentIndex = 0;

        function changeBackground() {
            backgroundSection.style.backgroundImage = `url(${images[currentIndex]})`;
            currentIndex = (currentIndex + 1) % images.length;
        }

        setInterval(changeBackground, 2500);
    </script>
</body>

</html>

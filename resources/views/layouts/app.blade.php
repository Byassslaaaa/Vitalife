<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ScrollReveal Library -->
    <script src="https://unpkg.com/scrollreveal@4.0.9/dist/scrollreveal.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .slider-image {
            transition: order 0.3s ease-in-out;
        }

        * {
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }

        *::-webkit-scrollbar {
            display: none !important;
        }

        html,
        body {
            overflow-y: auto;
            overflow-x: hidden;
        }

        .overflow-container {
            overflow-y: auto;
            height: 100vh;
        }
    </style>
    <script>
        // Global authentication status for JavaScript
        window.userAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased overflow-container">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="bg-blue-100">
            {{ $slot }}
        </main>
    </div>

    <!-- ScrollReveal Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            ScrollReveal().reveal('.reveal', {
                delay: 200,
                distance: '50px',
                duration: 1000,
                easing: 'cubic-bezier(0.5, 0, 0, 1)',
                interval: 0,
                opacity: 0,
                origin: 'bottom',
                scale: 1,
                cleanup: false,
                container: document.documentElement,
                desktop: true,
                mobile: true,
                reset: false,
                useDelay: 'always',
                viewFactor: 0.0,
                viewOffset: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0,
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const counters = [{
                    id: 'counter1',
                    target: 5000
                },
                {
                    id: 'counter2',
                    target: 200
                },
                {
                    id: 'counter3',
                    target: 1000
                },
                {
                    id: 'counter4',
                    target: 700
                }
            ];

            const animateCounter = (counter) => {
                const element = document.getElementById(counter.id);
                let animated = false;

                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting && !animated) {
                        anime({
                            targets: element,
                            innerHTML: [0, counter.target],
                            easing: 'linear',
                            round: 1,
                            duration: 3000,
                            begin: () => {
                                animated = true;
                            }
                        });
                        observer.unobserve(element);
                    }
                }, {
                    threshold: 0.5
                });

                observer.observe(element);
            };

            counters.forEach(animateCounter);
        });

        function handleResize() {
            const width = window.innerWidth;
            const elements = document.querySelectorAll('.responsive-element');

            elements.forEach(el => {
                if (width < 640) {
                    el.classList.add('sm-screen');
                } else if (width < 768) {
                    el.classList.add('md-screen');
                } else {
                    el.classList.add('lg-screen');
                }
            });

            const typedText = document.getElementById('typed-text');
            if (typedText) {
                if (width < 640) {
                    typedText.style.fontSize = '1rem';
                } else if (width < 768) {
                    typedText.style.fontSize = '1.25rem';
                } else {
                    typedText.style.fontSize = '1.5rem';
                }
            }
        }

        window.addEventListener('load', handleResize);
        window.addEventListener('resize', handleResize);

        // Kode Typed.js
        document.addEventListener('DOMContentLoaded', function() {
            var text =
                "We are the solution for travelling in a healthy condition and we provide health specialists...";

            function startTyping() {
                var typed = new Typed('#typed-text', {
                    strings: [text],
                    typeSpeed: 65,
                    startDelay: 1000,
                    showCursor: false,
                    cursorChar: '|',
                    onComplete: function(self) {
                        setTimeout(function() {
                            self.destroy();
                            setTimeout(startTyping, 500);
                        }, 1000);
                    }
                });
            }

            startTyping();
        });

        // HAPUS: Swal loading popup
        // document.addEventListener('DOMContentLoaded', function() {
        //     Swal.fire({
        //         title: 'Loading...',
        //         html: 'Please wait while we prepare your content.',
        //         allowOutsideClick: false,
        //         showConfirmButton: false,
        //         willOpen: () => {
        //             Swal.showLoading();
        //         },
        //     });
        // });

        // HAPUS: Swal.close() saat window load
        // window.addEventListener('load', function() {
        //     Swal.close();
        // });

        // Bahasa
        document.addEventListener('DOMContentLoaded', function() {
            const changeLanguageBtn = document.getElementById('changeLanguageBtn');
            if (changeLanguageBtn) {
                changeLanguageBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Change language button clicked');
                    const currentLang = this.getAttribute('data-lang');
                    const newLang = currentLang === 'en' ? 'id' : 'en';

                    fetch('/api/change-language', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                lang: newLang
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message === 'Language changed successfully') {
                                console.log('Language changed successfully. Reloading page...');
                                window.location.reload();
                            } else {
                                console.error('Failed to change language:', data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            } else {
                console.error('Change language button not found');
            }
        });

        document.body.addEventListener('wheel', function(e) {
            if (e.ctrlKey) {
                e.preventDefault();
            }
        }, {
            passive: false
        });
    </script>
</body>

</html>

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
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
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

        /* Fix dropdown z-index and visibility */
        [x-cloak] {
            display: none !important;
        }

        /* Ensure dropdowns appear above other content */
        nav .relative {
            position: relative;
            z-index: 10;
        }

        nav .absolute {
            z-index: 9999 !important;
        }

        /* Prevent click issues */
        a, button, [role="button"] {
            pointer-events: auto !important;
            cursor: pointer !important;
        }

        /* Ensure links are clickable */
        a:not([disabled]) {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
    <script>
        // Global authentication status for JavaScript
        // This is rendered server-side on EVERY page load - NOT cached
        // So it will always reflect the current authentication state
        window.userAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

        // Debug log to verify authentication status on page load
        console.log('[AUTH STATUS] User authenticated:', window.userAuthenticated);
        console.log('[AUTH STATUS] Page loaded at:', new Date().toISOString());
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased overflow-container bg-white">
    <div class="min-h-screen bg-white">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="bg-white pt-20">
            {{ $slot }}
        </main>

        <!-- Footer -->
        @include('layouts.footer')

        <!-- Chatbot Widget - Global -->
        <x-chatbot-widget />
    </div>

    <script>
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

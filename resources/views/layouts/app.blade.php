<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Asdev Tools</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css">

    <!-- Load Prism Core -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/atom-one-dark.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">

    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .menu-card {
            animation: fadeIn 0.5s ease-out forwards;
            animation-delay: calc(var(--order) * 0.1s);
            opacity: 0;
        }

        .logo-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
    <!-- <header class="w-full max-w-7xl mx-auto px-6 py-4">

    </header> -->

    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center px-6 py-12 sm:py-16 lg:py-20">
        <div class="w-full max-w-7xl mx-auto">
            <div class="flex justify-center mb-12 logo-float">
                <a href="{{ url('/') }}" class="cursor-pointer hover:opacity-80 transition-opacity duration-200">
                    <img src="{{ asset('images/logo.png') }}" alt="Your Logo" class="h-25 w-auto">
                </a>
            </div>

            @yield('content')

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-6">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-500 dark:text-gray-400 text-sm mb-4 md:mb-0">
                    © 2025 - {{ date('Y') }} Asdev Digital Solution Studio |
                    <span class="hidden md:inline">⚡ Powered by Cutting-Edge Tech</span>
                    <span class="md:hidden">⚡ Next-Gen Tech</span>
                </div>
                <div class="flex space-x-6">
                    <a href="https://www.instagram.com/abadbatok_/"
                        target="_blank"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm">
                        <i class="fab fa-instagram mr-1"></i> Instagram
                    </a>
                    <a href="https://www.linkedin.com/in/ahmad-subadri"
                        target="_blank"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm">
                        <i class="fab fa-linkedin mr-1"></i> Linkedin
                    </a>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=ahmadsubadri953@gmail.com"
                        target="_blank"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm">
                        <i class="fas fa-envelope mr-1"></i> Gmail
                    </a>
                    <a href="https://github.com/AhmadSubadri" target="_blank" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm">
                        GitHub
                    </a>
                </div>
            </div>
        </div>
    </footer>
    @stack('scripts')

</body>

</html>
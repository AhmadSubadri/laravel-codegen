@extends('layouts.app')

@section('title', 'Asdev Development Suite')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Code Generator -->
    <div class="menu-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1" style="--order: 1">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/50 mr-4">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Code Generator</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                Generate boilerplate code instantly for models, controllers, APIs, and more suggestions.
            </p>
            <div class="space-y-2">
                <a href="{{ route('tools.migration-generator') }}" class="block px-3 py-2 bg-blue-50 dark:bg-blue-900/30 rounded-md text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                    Database Migration + Model Generator Laravel
                </a>
                <a href="https://aistudio.instagram.com/ai/2110212979457644/?utm_source=share" target="_blank" class="block px-3 py-2 bg-blue-50 dark:bg-blue-900/30 rounded-md text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <!-- Ikon AI/chatbot alternatif 1: Buble chat dengan otak -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 8a3 3 0 11-6 0 3 3 0 016 0z" fill="currentColor"></path>
                    </svg>
                    Asdev AI Assistant
                </a>
            </div>
        </div>
    </div>

    <!-- Database Architect -->
    <div class="menu-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1" style="--order: 2">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/50 mr-4">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Database Architect</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                Visual database designer with schema migration generator and relationship manager.
            </p>
            <a href="#" class="inline-flex items-center text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium">
                Design Schema
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- API Studio -->
    <div class="menu-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1" style="--order: 3">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-lg bg-purple-100 dark:bg-purple-900/50 mr-4">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">API Studio</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                Design, test, and document RESTful APIs with interactive documentation and mock endpoints.
            </p>
            <div class="flex space-x-2">
                <a href="#" class="px-3 py-1.5 bg-purple-50 dark:bg-purple-900/30 rounded-md text-xs font-medium text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors">
                    Swagger Docs
                </a>
                <a href="#" class="px-3 py-1.5 bg-purple-50 dark:bg-purple-900/30 rounded-md text-xs font-medium text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors">
                    Test Endpoints
                </a>
            </div>
        </div>
    </div>

    <!-- Cloud Deploy -->
    <div class="menu-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1" style="--order: 4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-lg bg-orange-100 dark:bg-orange-900/50 mr-4">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Cloud Deploy</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                One-click deployment to AWS, DigitalOcean, and Kubernetes with CI/CD pipelines.
            </p>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                    AWS
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                    Kubernetes
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                    CI/CD
                </span>
            </div>
        </div>
    </div>

    <!-- Performance Monitor -->
    <div class="menu-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1" style="--order: 5">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-lg bg-red-100 dark:bg-red-900/50 mr-4">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Performance Monitor</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                Real-time application performance monitoring with alerts and optimization suggestions.
            </p>
            <div class="flex items-center">
                <span class="flex h-2.5 w-2.5 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                <span class="text-xs text-gray-500 dark:text-gray-400">All systems operational</span>
            </div>
        </div>
    </div>

    <!-- AI Assistant -->
    <div class="menu-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1" style="--order: 6">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 mr-4">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">AI Dev Assistant</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                Get intelligent code suggestions, debug errors, and optimize your applications with AI.
            </p>
            <a href="#" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                Ask AI
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
<div class="mt-16 py-8">
    <h3 class="text-2xl font-bold text-center mb-8 text-gray-800 dark:text-white">My Tech Stack</h3>

    <div class="relative overflow-hidden group">
        <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-white dark:from-gray-900 to-transparent z-10"></div>
        <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-white dark:from-gray-900 to-transparent z-10"></div>

        <div class="flex">
            <div class="flex animate-slide space-x-6 pr-6">
                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/laravel-2.svg" alt="Laravel" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">Laravel</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/react-2.svg" alt="React" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">React</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/vue-9.svg" alt="Vue" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">Vue.js</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/javascript-1.svg" alt="JavaScript" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">JavaScript</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/nodejs-icon.svg" alt="Node.js" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">Node.js</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/docker-4.svg" alt="Docker" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">Docker</span>
                </div>


                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/codeigniter.svg" alt="CodeIgniter 3" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">CI3</span>
                </div>
            </div>
            <div class="flex animate-slide space-x-6 pr-6">
                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/laravel-2.svg" alt="Laravel" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">Laravel</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/react-2.svg" alt="React" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">React</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/vue-9.svg" alt="Vue" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">Vue.js</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/javascript-1.svg" alt="JavaScript" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">JavaScript</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/nodejs-icon.svg" alt="Node.js" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">Node.js</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/docker-4.svg" alt="Docker" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">Docker</span>
                </div>

                <div class="skill-card flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <img src="https://cdn.worldvectorlogo.com/logos/codeigniter.svg" alt="CodeIgniter 3" class="h-12 w-12 mb-3">
                    <span class="text-sm font-semibold">CI3</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@extends('layouts.app')

@section('title', 'Asdev AI Assistant')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="text-center mb-8">
        <a href="{{ url('/') }}" class="inline-block">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Asdev AI Assistant</h1>
        </a>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Ask our professional AI assistant anything</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden flex flex-col" style="height: 80vh;">
        <div id="chatContainer" class="flex-1 p-4 overflow-y-auto space-y-4">
            <div class="flex">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">AI</div>
                </div>
                <div class="flex-1">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Asdev AI • Sekarang</div>
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 text-gray-800 dark:text-gray-200 transition-colors">
                        <p>Hello! I am Asdev AI Assistant. How can I help you?</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-700">
            <form id="aiAsistenForm" class="flex gap-2">
                @csrf
                <textarea
                    id="prompt"
                    name="prompt"
                    rows="1"
                    class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white resize-none transition-all"
                    placeholder="Ketik pesan Anda..."
                    required></textarea>
                <button
                    type="submit"
                    id="submitBtn"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center justify-center w-12 h-12 self-end">
                    <svg id="sendIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429 1.429-5a1 1 0 00-1.408-1.169l-3.5 1-3.5-1 7-14z" />
                    </svg>
                    <svg id="loadingSpinner" class="hidden h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Core highlight.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
@vite(['resources/js/tools/asdev-ai.js'])
@vite(['resources/css/tools/ai.css'])

@endsection
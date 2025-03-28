@extends('layouts.app')

@section('title', 'SQL to Migration and Models Laravel Generator')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-all duration-500 ease-out opacity-0 animate-[fadeIn_0.5s_ease-out_forwards]">
        <div class="mb-6 text-center opacity-0 animate-[fadeIn_0.4s_ease-out_0.1s_forwards]">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">MySQL to Laravel Migration and Models Generator</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2 opacity-0 animate-[fadeIn_0.4s_ease-out_0.2s_forwards]">
                Convert SQL CREATE TABLE statements to Laravel migrations and models
            </p>
        </div>

        <div class="w-full mb-6">
            <form id="migrationForm" method="POST" class="space-y-4">
                <div class="opacity-0 animate-[fadeIn_0.4s_ease-out_0.3s_forwards]">
                    <label for="sqlInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        SQL CREATE TABLE Statements
                    </label>
                    <textarea
                        id="sqlInput"
                        rows="15"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition hover:shadow-md focus:shadow-lg"
                        placeholder="CREATE TABLE `users` (...)"></textarea>
                </div>

                <div class="flex items-center opacity-0 animate-[fadeIn_0.4s_ease-out_0.4s_forwards]">
                    <input
                        type="checkbox"
                        id="generateModel"
                        name="generate_model"
                        class="h-4 w-4 text-indigo-600 dark:text-indigo-400 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700"
                        checked>
                    <label for="generateModel" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                        Generate Models at Once?
                    </label>
                </div>

                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition hover:scale-[1.02] active:scale-[0.98] opacity-0 animate-[fadeIn_0.4s_ease-out_0.5s_forwards]">
                    <i class="fas fa-cog mr-2"></i> Generate Migrations
                </button>
            </form>
        </div>

        <div class="w-full opacity-0 animate-[fadeIn_0.5s_ease-out_0.6s_forwards]">
            <div id="resultContainer" class="border border-gray-300 dark:border-gray-600 rounded-md p-6 bg-gray-50 dark:bg-gray-700 transition hover:shadow-sm">
                <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                    <i class="fas fa-code text-4xl mb-2 transition-transform hover:scale-110"></i>
                    <p class="text-sm">Generated migrations will appear here</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@vite(['resources/js/tools/migration-generator.js'])
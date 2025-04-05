@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 min-h-screen">
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Floating Back Button -->
        <div class="fixed top-6 left-6 z-10">
            <a href="{{ route('posts.index') }}" class="inline-flex items-center bg-white dark:bg-gray-800 shadow-lg rounded-full px-4 py-2 text-blue-600 dark:text-blue-400 hover:shadow-xl transition-all duration-300 group">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-sm font-medium">All Articles</span>
            </a>
        </div>

        <!-- Header Section -->
        <header class="mb-12 text-center">
            <div class="flex justify-center mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $post->category_color }}-100 text-{{ $post->category_color }}-800 dark:bg-{{ $post->category_color }}-900/30 dark:text-{{ $post->category_color }}-300">
                    {{ $post->category }}
                </span>
            </div>

            <h1 class="text-4xl font-bold text-gray-900 dark:text-white sm:text-5xl leading-tight bg-clip-text">
                {{ $post->title }}
            </h1>

            <div class="mt-8 flex flex-col items-center">
                <div class="flex items-center">
                    <img class="h-12 w-12 rounded-full border-2 border-white dark:border-gray-700 shadow"
                        src="{{ $post->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&color=7F9CF5&background=EBF4FF' }}"
                        alt="{{ $post->user->name }}">
                    <div class="ml-4 text-left">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $post->user->name }}
                        </p>
                        <div class="flex space-x-2 text-sm text-gray-500 dark:text-gray-400">
                            <span>{{ $post->published_at->format('M j, Y') }}</span>
                            <span>•</span>
                            <span>{{ $post->reading_time }} min read</span>
                            <span>•</span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ $post->views_count }} views
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        @if($post->featured_image)
        <div class="mb-12 rounded-2xl overflow-hidden shadow-2xl transform hover:scale-[1.01] transition-transform duration-500">
            <img src="{{ asset('storage/' . $post->featured_image) }}"
                alt="{{ $post->title }}"
                class="w-full h-auto max-h-[32rem] object-cover">
        </div>
        @endif

        <!-- Tags -->
        @if(!empty($post->tags) && is_array($post->tags))
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            @foreach($post->tags as $tag)
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                #{{ $tag }}
            </span>
            @endforeach
        </div>
        @endif

        <!-- Content -->
        <div class="prose dark:prose-invert prose-lg max-w-none mx-auto">
            {!! $post->content !!}
        </div>

        <!-- Engagement Section -->
        <div class="mt-16 py-8 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-6">
                <!-- Author Bio -->
                <div class="flex items-center">
                    <img class="h-16 w-16 rounded-full border-2 border-white dark:border-gray-700 shadow-lg"
                        src="{{ $post->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&color=7F9CF5&background=EBF4FF' }}"
                        alt="{{ $post->user->name }}">
                    <div class="ml-4">
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Written by {{ $post->user->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Published on {{ $post->published_at->format('F j, Y') }}</p>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="flex flex-col items-center sm:items-end">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Share this article</h3>
                    <div class="flex space-x-4">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                            target="_blank"
                            class="p-2 rounded-full bg-blue-50 dark:bg-gray-700 text-blue-500 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-gray-600 transition-colors"
                            aria-label="Share on Twitter">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank"
                            class="p-2 rounded-full bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-500 hover:bg-blue-100 dark:hover:bg-gray-600 transition-colors"
                            aria-label="Share on Facebook">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}"
                            target="_blank"
                            class="p-2 rounded-full bg-blue-50 dark:bg-gray-700 text-blue-700 dark:text-blue-600 hover:bg-blue-100 dark:hover:bg-gray-600 transition-colors"
                            aria-label="Share on LinkedIn">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Like Button -->
        <div class="fixed bottom-6 right-6 z-10">
            <button class="p-4 bg-white dark:bg-gray-800 shadow-lg rounded-full text-pink-500 hover:text-pink-600 dark:hover:text-pink-400 hover:shadow-xl transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        </div>
    </article>
</div>
@endsection
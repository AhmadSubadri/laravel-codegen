@extends('layouts.app')

@section('title', 'Tech News & Articles')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 dark:text-white">Latest Tech News & Articles</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($posts as $post)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            @if($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
            @endif
            <div class="p-6">
                <div class="flex items-center mb-2">
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                        {{ $post->category }}
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">
                        {{ $post->published_at->format('M d, Y') }}
                    </span>
                </div>
                <a href="{{ route('posts.show', $post) }}" class="block mb-2 text-xl font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    {{ $post->title }}
                </a>
                <p class="mb-4 text-gray-600 dark:text-gray-300">
                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}
                </p>
                <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                    Read more
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <article class="max-w-4xl mx-auto">
        <header class="mb-8">
            <div class="flex items-center mb-4">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                    {{ $post->category }}
                </span>
                <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">
                    {{ $post->published_at->format('M d, Y') }} • {{ $post->user->name }}
                </span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ $post->title }}
            </h1>
            @if($post->tags)
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($post->tags as $tag)
                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                    #{{ $tag }}
                </span>
                @endforeach
            </div>
            @endif
            @if($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full rounded-lg mb-6">
            @endif
        </header>

        <div class="prose dark:prose-invert max-w-none">
            {!! $post->content !!}
        </div>

        <div class="mt-12 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('posts.index') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to all posts
            </a>
        </div>
    </article>
</div>
@endsection
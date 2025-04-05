<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')
            ->where('published', true)
            ->latest('published_at')
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->increment('views_count');

        Post::where('id', $post->id)->increment('views_count');
        $post->refresh();

        return view('posts.show', compact('post'));
    }
}

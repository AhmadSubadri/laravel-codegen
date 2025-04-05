<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('published', true)
            ->latest('published_at')
            ->paginate(6);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        if (!$post->published) {
            abort(404);
        }

        return view('posts.show', compact('post'));
    }

    // The following methods are for admin panel (you can remove if not needed)
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable',
            'category' => 'required',
            'tags' => 'nullable',
            'published' => 'boolean',
        ]);

        $post = new Post($validated);
        $post->slug = Str::slug($request->title);
        $post->user_id = auth()->id();

        if ($request->published) {
            $post->published_at = now();
        }

        $post->save();

        return redirect()->route('posts.show', $post);
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable',
            'category' => 'required',
            'tags' => 'nullable',
            'published' => 'boolean',
        ]);

        $post->fill($validated);

        if ($request->published && !$post->published) {
            $post->published_at = now();
        }

        $post->save();

        return redirect()->route('posts.show', $post);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index');
    }
}

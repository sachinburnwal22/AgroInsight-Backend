<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index($community_id)
    {
        $posts = Post::where('community_id', $community_id)
            ->latest()
            ->get();
            
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'community_id' => 'required|exists:communities,id',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120'
        ]);

        $post = new Post();
        $post->user_id = $request->user()->id;
        $post->community_id = $request->community_id;
        $post->content = $request->content;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image = '/storage/' . $path;
        }

        $post->save();

        // Load relationships to return the fresh post
        $post->load(['user', 'likes', 'comments']);

        return response()->json($post, 201);
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get a list of all posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::all();

        return response()->json($posts);
    }

    /**
     * Get a specific post by ID.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        return response()->json($post);
    }

    /**
     * Create a new post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts',
            'abstract' => 'required',
            'content' => 'required',
        ]);

        $post = Post::create([
            'title' => $request->input('title'),
            'abstract' => $request->input('abstract'),
            'content' => $request->input('content'),
            'posted' => now(),
        ]);

        return response()->json($post, 201);
    }

    /**
     * Delete a post by ID.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}

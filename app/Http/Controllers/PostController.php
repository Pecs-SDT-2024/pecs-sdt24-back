<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * PostController constructor.
     * Apply the 'auth:api' middleware to all methods to ensure only authenticated users can access the endpoints.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get a list of all posts.
     * This method retrieves all posts from the database and returns them as a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all posts from the database
        $posts = Post::all();

        // Return the posts as a JSON response
        return response()->json($posts);
    }

    /**
     * Get a specific post by ID.
     * This method retrieves a single post based on its ID.
     * If the post is found, it is returned as a JSON response.
     * If the post is not found, an error message is returned.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($postId)
    {
        // Find the post by its ID
        $post = Post::find($postId);

        // If the post is not found, return a 404 error response
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        // Return the post as a JSON response
        return response()->json($post);
    }

    /**
     * Create a new post.
     * This method validates the incoming request, creates a new post, and saves it to the database.
     * The new post is then returned as a JSON response with a 201 status code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'title' => 'required|unique:posts',
            'abstract' => 'required',
            'content' => 'required',
        ]);

        // Create a new post using the validated data
        $post = Post::create([
            'title' => $request->input('title'),
            'abstract' => $request->input('abstract'),
            'content' => $request->input('content'),
            'posted' => now(), // Set the posted date to the current timestamp
        ]);

        // Return the newly created post as a JSON response with a 201 status code
        return response()->json($post, 201);
    }

    /**
     * Delete a post by ID.
     * This method deletes a post based on its ID.
     * If the post is found and deleted, a success message is returned.
     * If the post is not found, an error message is returned.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($postId)
    {
        // Find the post by its ID
        $post = Post::find($postId);

        // If the post is not found, return a 404 error response
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        // Delete the post
        $post->delete();

        // Return a success message as a JSON response
        return response()->json(['message' => 'Post deleted successfully']);
    }
}

<?php

namespace Tests\Unit;

use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the index method of PostController.
     *
     * @return void
     */
    public function testIndex()
    {
        // Generate some dummy posts
        Post::factory()->count(5)->create();

        // Send a GET request to the index endpoint
        $response = $this->get('/api/posts');

        // Assert that the response has a successful status code (200)
        $response->assertStatus(200);

        // Assert that the response contains the correct number of posts
        $response->assertJsonCount(5);
    }

    /**
     * Test the show method of PostController.
     *
     * @return void
     */
    public function testShow()
    {
        // Create a new post
        $post = Post::factory()->create();

        // Send a GET request to the show endpoint with the post's ID
        $response = $this->get('/api/posts/' . $post->id);

        // Assert that the response has a successful status code (200)
        $response->assertStatus(200);

        // Assert that the response contains the correct post data
        $response->assertJson([
            'id' => $post->id,
            'title' => $post->title,
            'abstract' => $post->abstract,
            'content' => $post->content,
            'posted' => $post->posted,
        ]);
    }

    /**
     * Test the store method of PostController.
     *
     * @return void
     */
    public function testStore()
    {
        // Generate dummy post data
        $postData = [
            'title' => $this->faker->sentence,
            'abstract' => $this->faker->paragraph,
            'content' => $this->faker->text,
        ];

        // Send a POST request to the store endpoint with the post data
        $response = $this->post('/api/posts', $postData);

        // Assert that the response has a successful status code (201)
        $response->assertStatus(201);

        // Assert that the response contains the correct post data
        $response->assertJson($postData);

        // Assert that the post has been saved in the database
        $this->assertDatabaseHas('posts', $postData);
    }

    /**
     * Test the destroy method of PostController.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Create a new post
        $post = Post::factory()->create();
    
        // Send a DELETE request to the destroy endpoint with the post's ID
        $response = $this->delete('/api/posts/' . $post->id);
    
        // Assert that the response has a successful status code (200)
        $response->assertStatus(200);
    
        // Assert that the response contains the correct success message
        $response->assertJson(['message' => 'Post deleted successfully']);
    
        // Assert that the post has been deleted from the database
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();
    }

    /**
     * Test retrieving a list of all posts.
     *
     * @return void
     */
    public function test_index()
    {
        // Create test posts
        Post::factory()->count(5)->create();

        // Make authenticated request
        $response = $this->actingAs($this->user, 'api')->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'abstract',
                    'content',
                    'posted',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /**
     * Test retrieving a specific post by ID.
     *
     * @return void
     */
    public function test_show()
    {
        // Create a test post
        $post = Post::factory()->create();

        // Make authenticated request
        $response = $this->actingAs($this->user, 'api')->getJson('/api/posts/' . $post->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'abstract',
                'content',
                'posted',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test creating a new post.
     *
     * @return void
     */
    public function test_store()
    {
        // Define post data
        $postData = [
            'title' => $this->faker->sentence,
            'abstract' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'posted' => now()->toDateTimeString(),
        ];

        // Make authenticated request
        $response = $this->actingAs($this->user, 'api')->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'abstract',
                'content',
                'posted',
            ]);

        // Assert the post is in the database
        $this->assertDatabaseHas('posts', $postData);
    }

    /**
     * Test deleting a post by ID.
     *
     * @return void
     */
    public function test_destroy()
    {
        // Create a test post
        $post = Post::factory()->create();

        // Make authenticated request
        $response = $this->actingAs($this->user, 'api')->deleteJson('/api/posts/' . $post->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Post deleted successfully',
            ]);

        // Assert the post is deleted from the database
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}


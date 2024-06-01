<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_posts()
    {
        $posts = Post::factory()->count(10)->create();

        $this->assertCount(10, $posts);
    }
}

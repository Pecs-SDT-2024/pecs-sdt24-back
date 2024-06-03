<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration.
     *
     * @return void
     */
    public function test_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com'
        ]);
    }

    /**
     * Test user login.
     *
     * @return void
     */
    public function test_login_post()
    {
        // Create a test user
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in'
                ]
            ]);
    }

    /**
     * Test failed login attempt.
     *
     * @return void
     */
    public function test_failed_login_post()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrongemail@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
    }

    /**
     * Test user logout.
     *
     * @return void
     */
    public function test_logout()
    {
        // Create a test user and login
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password'),
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('access_token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);
    }

    /**
     * Test fetching authenticated user details.
     *
     * @return void
     */
    public function test_me()
    {
        // Create a test user and login
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password'),
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('access_token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test refreshing JWT token.
     *
     * @return void
     */
    public function test_refresh()
    {
        // Create a test user and login
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password'),
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('access_token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }
}


<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Post::factory(10)->create();

        \App\Models\User::factory(10)->create();

        \App\Models\Role::factory(10)->create();

        \App\Models\UserRoleMapping::factory()->create([
            'user_id' => 1,
            'role_id' => 1
        ]);

        \App\Models\UserRoleMapping::factory()->create([
            'user_id' => 1,
            'role_id' => 3
        ]);
    }
}

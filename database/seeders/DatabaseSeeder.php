<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use Database\Factories\ProductFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory(10)->create();
        Post::factory(20)->create();

        Post::create([
            'name' => 'Manual',
            'caption' => 'Manual',
            'is_archived' => false,
            'likes' => 100,
            'user_id' => 1
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // User::insert([
        //     'name' => 'Name'
        // ]);
    }
}

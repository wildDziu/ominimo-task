<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        for ($i = 0; $i < 50; $i++) {
            Post::create([
                'user_id' => $users->random()->id,
                'title' => fake()->sentence(),
                'content' => fake()->paragraphs(3, true),
            ]);
        }
    }
}

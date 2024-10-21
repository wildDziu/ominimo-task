<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $posts = Post::all();

        for ($i = 0; $i < 200; $i++) {
            Comment::create([
                'user_id' => $users->random()->id,
                'post_id' => $posts->random()->id,
                'comment' => fake()->paragraph(),
            ]);
        }
    }
}

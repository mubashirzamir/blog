<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\CommentFactory;
use Database\Factories\PostFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function (User $user) {
            $posts = PostFactory::new([
                'user_id' => $user->id,
            ])->count(5)->create();

            $posts->each(function (Post $post) use ($user) {
                CommentFactory::new([
                    'user_id' => User::inRandomOrder()->first()->id,
                    'post_id' => $post->id,
                ])->count(3)->create();
            });
        });
    }
}

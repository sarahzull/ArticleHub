<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $users = User::pluck('id')->toArray();
        $categories = Category::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            Article::create([
                'author_id' => $faker->randomElement($users),
                'is_premium' => $faker->boolean(),
                'category_id' => $faker->randomElement($categories),
                'title' => $faker->sentence(),
                'content' => $faker->paragraph(),
                'published_at' => $faker->dateTimeThisMonth(),
            ]);
        }
    }
}

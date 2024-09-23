<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Technology']);
        Category::create(['name' => 'Science']);

        //new categories
        Category::create(['name' => 'Business']);
        Category::create(['name' => 'Health']);
        Category::create(['name' => 'Sports']);
        Category::create(['name' => 'Entertainment']);
        Category::create(['name' => 'Travel']);
        Category::create(['name' => 'Food']);
        Category::create(['name' => 'Fashion']);
        Category::create(['name' => 'Lifestyle']);
        Category::create(['name' => 'Education']);
        Category::create(['name' => 'Automotive']);
        Category::create(['name' => 'Real Estate']);
        Category::create(['name' => 'Environment']);
        Category::create(['name' => 'Politics']);
        Category::create(['name' => 'Culture']);
        Category::create(['name' => 'History']);
        Category::create(['name' => 'Religion']);
        Category::create(['name' => 'Philosophy']);
        Category::create(['name' => 'Psychology']);
        Category::create(['name' => 'Sociology']);
    }
}

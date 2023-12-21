<?php

namespace Moveon\Image\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Moveon\Image\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Nature',
                'slug' => Str::slug('Nature')
            ],
            [
                'name' => 'Travel',
                'slug' => Str::slug('Travel')
            ],
            [
                'name' => 'Technology',
                'slug' =>Str::slug('Technology')
            ],
            [
                'name' => 'Animals',
                'slug' => Str::slug('Animals')
            ],
            [
                'name' => 'Fashion',
                'slug' => Str::slug('Fashion')
            ],
            [
                'name' => 'Sports',
                'slug' => Str::slug('Sports')
            ],
            [
                'name' => 'Art and Design',
                'slug' => Str::slug('Art and Design')
            ],
            [
                'name' => 'Health and Wellness',
                'slug' => Str::slug('Health and Wellness')
            ],
            [
                'name' => 'Architecture',
                'slug' => Str::slug('Architecture')
            ],
            [
                'name' => 'Others',
                'slug' => Str::slug('Others')
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

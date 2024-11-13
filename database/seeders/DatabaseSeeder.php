<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\NewsAuthor;
use App\Models\NewsCategory;
use App\Models\NewsSource;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        NewsSource::factory()->count(3)->create();
        NewsCategory::factory()->count(187)->create();
        NewsAuthor::factory()->count(101)->create();
        Article::factory()->count(10)->create();
    }
}

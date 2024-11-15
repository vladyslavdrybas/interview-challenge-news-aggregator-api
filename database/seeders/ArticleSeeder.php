<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\NewsAuthor;
use App\Models\NewsCategory;
use App\Models\NewsSource;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::factory()->count(1511)
            ->create()
            ->each(function ($article) {
                $newsSource = NewsSource::inRandomOrder()->first();
                $categories = NewsCategory::inRandomOrder()->take(rand(3,27))->pluck('id');
                $authors = NewsAuthor::inRandomOrder()->take(rand(1,7))->pluck('id');

                // many-to-one
                $article->source()->associate($newsSource);
                $article->save();

                // many-to-many
                $article->categories()->attach($categories);

                // many-to-many
                $article->authors()->attach($authors);
            })
        ;
    }
}

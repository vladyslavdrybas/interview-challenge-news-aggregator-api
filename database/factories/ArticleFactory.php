<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\NewsSource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use function fake;
use function rand;
use function uniqid;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->text(rand(15, 255)),
            'content' => fake()->paragraph(rand(3,30)),
            'published_at' => rand(0,10) < 6 ? $this->faker->dateTimeThisYear : null,
        ];
    }
}

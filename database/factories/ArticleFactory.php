<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use function fake;

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
            'title' => fake()->jobTitle,
            'content' => fake()->paragraph(rand(3,30)),
            'published_at' => rand(0,10) < 6 ? $this->faker->dateTimeThisYear : null,
        ];
    }
}

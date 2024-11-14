<?php

namespace Database\Factories;

use App\Models\NewsCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use function fake;
use function hash;
use function uniqid;

/**
 * @extends Factory<NewsCategory>
 */
class NewsAuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => sprintf( '%s %s', fake()->firstName, fake()->lastName),
            'slug' => hash('sha512', uniqid(). Str::random(50)),
        ];
    }
}

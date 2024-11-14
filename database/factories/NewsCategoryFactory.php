<?php

namespace Database\Factories;

use App\Models\NewsCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use function fake;
use function hash;
use function rand;
use function uniqid;

/**
 * @extends Factory<NewsCategory>
 */
class NewsCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => trim(fake()->unique()->text(rand(5,255))),
            'slug' => hash('sha512', uniqid(). Str::random(50)),
        ];
    }
}

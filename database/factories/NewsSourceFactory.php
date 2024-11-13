<?php

namespace Database\Factories;

use App\Models\NewsSource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use function config;
use function fake;
use function uniqid;

/**
 * @extends Factory<NewsSource>
 */
class NewsSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->jobTitle();

        return [
            'title' => $title,
            'base_url' => config('app.url') . '/api/v1/fakenews/' . Str::slug($title),
            'apikey' => uniqid('key_', true),
        ];
    }
}

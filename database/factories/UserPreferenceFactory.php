<?php

namespace Database\Factories;

use App\Models\NewsAuthor;
use App\Models\NewsCategory;
use App\Models\NewsSource;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserPreference>
 */
class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition()
    {

        return [
            'user_id' => User::inRandomOrder()->get()->first()->id,
            'title' => $this->faker->word,
            'slug' => $this->faker->slug,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (UserPreference $preference) {
            $categories = NewsCategory::inRandomOrder()->take(5)->pluck('id');
            $authors = NewsAuthor::inRandomOrder()->take(3)->pluck('id');
            $sources = NewsSource::inRandomOrder()->take(2)->pluck('id');

            $preference->categories()->attach($categories);
            $preference->authors()->attach($authors);
            $preference->sources()->attach($sources);
        });
    }
}

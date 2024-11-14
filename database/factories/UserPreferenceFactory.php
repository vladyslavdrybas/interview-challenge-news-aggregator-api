<?php

namespace Database\Factories;

use App\Models\NewsAuthor;
use App\Models\NewsCategory;
use App\Models\NewsSource;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use function hash;
use function uniqid;

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
            'title' => $this->faker->unique()->word,
            'slug' => hash('sha512', uniqid(). Str::random(50)),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (UserPreference $preference) {
            $categories = NewsCategory::inRandomOrder()->take(51)->pluck('id');
            $authors = NewsAuthor::inRandomOrder()->take(21)->pluck('id');
            $sources = NewsSource::inRandomOrder()->take(55)->pluck('id');

            $preference->categories()->attach($categories);
            $preference->authors()->attach($authors);
            $preference->sources()->attach($sources);
        });
    }
}

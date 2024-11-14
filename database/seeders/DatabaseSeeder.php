<?php

namespace Database\Seeders;

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

        $this->call([
            NewsSourceSeeder::class,
            NewsCategorySeeder::class,
            NewsAuthorSeeder::class,
            ArticleSeeder::class,
            UserPreferenceSeeder::class,
        ]);
    }
}

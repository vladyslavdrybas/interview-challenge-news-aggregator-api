<?php

namespace Database\Seeders;

use App\Models\NewsAuthor;
use Illuminate\Database\Seeder;

class NewsAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewsAuthor::factory()->count(101)->create();
    }
}

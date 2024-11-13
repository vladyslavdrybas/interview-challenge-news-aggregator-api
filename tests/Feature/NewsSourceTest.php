<?php

namespace Tests\Feature;

use App\Constants\AnswerType;
use App\Models\NewsSource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NewsSourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_news_sources(): void
    {
        $newsSources = NewsSource::factory()->count(3)->create();
        $user = User::factory()->create();
        $token = Sanctum::actingAs($user);

        $newsSources = $newsSources->toArray();

        $response = $this->getJson(
            $this->apiRoute('/news-sources'),
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        );

        $response->assertStatus(200)
            ->assertJson(
                [
                    'type' => AnswerType::LIST->value,
                    'pagination' => [
                        'current_page' => 1,
                        'total_pages' => 1,
                        'total_items' => 3
                    ],
                    'data' => $newsSources,
                ]
            )
        ;
    }

    public function test_list_news_sources_pagination(): void
    {
        $newsSources = NewsSource::factory()->count(3)->create();
        $user = User::factory()->create();
        $token = Sanctum::actingAs($user);

        $newsSources = $newsSources->toArray();

        $response = $this->getJson(
            $this->apiRoute('/news-sources?per_page=1&page=2'),
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        );

        $response->assertStatus(200)
            ->assertJson(
                [
                    'type' => AnswerType::LIST->value,
                    'pagination' => [
                        'current_page' => 2,
                        'total_pages' => 3,
                        'total_items' => 3,
                    ],
                    'data' => [$newsSources[1]],
                ]
            )
        ;
    }

    public function test_list_news_sources_end_of_pagination(): void
    {
        NewsSource::factory()->count(3)->create();
        $user = User::factory()->create();
        $token = Sanctum::actingAs($user);

        $response = $this->getJson(
            $this->apiRoute('/news-sources?page=200'),
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        );

        $response->assertStatus(200)
            ->assertJson(
                [
                    'type' => AnswerType::LIST->value,
                    'pagination' => [
                        'current_page' => 200,
                        'total_pages' => 1,
                        'total_items' => 3,
                    ],
                    'data' => [],
                ]
            )
        ;
    }
}

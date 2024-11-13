<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    public function test_show_single_article(): void
    {
        $article = Article::factory()->count(1)->create()->first();
        $user = User::factory()->create(['password' => 'password']);
        $token = Sanctum::actingAs($user);

        $response = $this->getJson(
            $this->apiRoute('/articles/' . $article->id),
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        );

        $response->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        'id' => $article->id,
                        'title' => $article->title,
                        'content' => $article->content,
                    ]
                ]
            )
        ;
    }
}

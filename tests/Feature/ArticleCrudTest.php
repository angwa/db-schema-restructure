<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Provider;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ArticleCrudTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    public function testCreateArticle()
    {
        $response = $this->postJson('/api/articles',  [
            'provider_id' => Provider::factory()->create(['provider_no' => 103])->id,
            'article_no' => $this->faker->numberBetween(10, 100),
            'article' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 1000)
        ]);

        $response->assertStatus(HTTP_CREATED);
        $this->assertIsObject($response);
    }

    public function testReadArticle()
    {
        $response = $this->getJson('/api/articles');

        $response->assertStatus(HTTP_SUCCESS);
        $this->assertIsObject($response);
    }

    public function testUpdateArticle()
    {
        $article = Article::factory()->create()->id;

        $response = $this->putJson('/api/articles/' . $article,  [
            'article' => $this->faker->sentence,
        ]);

        $response->assertStatus(HTTP_SUCCESS);
        $this->assertIsObject($response);
    }

    public function testDeleteArticle()
    {
        $article = Article::factory()->create()->id;

        $response = $this->deleteJson('/api/articles/' . $article);

        $response->assertStatus(HTTP_SUCCESS);
        $this->assertIsObject($response);
    }
}

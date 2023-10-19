<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleProvider;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'article_no' => $this->faker->unique()->numberBetween(1, 100),
            'article' => $this->faker->sentence,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Article $article) {
            ArticleProvider::factory()->create([
                'article_id' => $article->id,
                'provider_id' => Provider::factory()->create()->id,
                'price' => $this->faker->randomFloat(2, 10, 1000),
            ]);
        });
    }
}





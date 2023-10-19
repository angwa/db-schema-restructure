<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleProvider>
 */
class ArticleProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomProvider = Provider::inRandomOrder()->first();

        if ($randomProvider) {
            $provider = $randomProvider->id;
        } else {
            $provider = Provider::factory()->create()->id;
        }

        return [
            'provider_id' => $provider,
            'price' => $this->faker->randomFloat(2, 10, 1000)
        ];
    }
}

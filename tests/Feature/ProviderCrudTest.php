<?php

namespace Tests\Feature;

use App\Models\Provider;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProviderCrudTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    public function testCreateProvider()
    {
        $response = $this->postJson('/api/providers',  [
            'provider_no' => $this->faker->numberBetween(101, 150),
            'provider' => $this->faker->company(),
        ]);

        $response->assertStatus(HTTP_CREATED);
        $this->assertIsObject($response);
    }

    public function testReadProvider()
    {
        $response = $this->getJson('/api/providers');

        $response->assertStatus(HTTP_SUCCESS);
        $this->assertIsObject($response);
    }

    public function testUpdateProvider()
    {
        $randomProvider = Provider::inRandomOrder()->first();

        if ($randomProvider) {
            $provider = $randomProvider->id;
        } else {
            $provider = Provider::factory()->create()->id;
        }
        $response = $this->putJson('/api/providers/' . $provider,  [
            'provider_name' => $this->faker->company(),
        ]);

        $response->assertStatus(HTTP_SUCCESS);
        $this->assertIsObject($response);
    }

    public function testDeleteProvider()
    {
        $randomProvider = Provider::inRandomOrder()->first();

        if ($randomProvider) {
            $provider = $randomProvider->id;
        } else {
            $provider = Provider::factory()->create()->id;
        }

        $response = $this->deleteJson('/api/providers/' . $provider);

        $response->assertStatus(HTTP_SUCCESS);
        $this->assertIsObject($response);
    }
}

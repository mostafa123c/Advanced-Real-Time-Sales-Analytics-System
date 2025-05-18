<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RecommendationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function recommendations_endpoint_returns_expected_status_and_structure()
    {
        $response = $this->getJson('/api/v1/recommendations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'sales_recommendations',
                'weather_recommendations'
            ]);
    }

    #[Test]
    public function pricing_suggestions_endpoint_returns_expected_status_and_structure()
    {
        $response = $this->getJson('/api/v1/pricing-suggestions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'pricing_adjustments'
            ]);
    }

    #[Test]
    public function weather_data_is_cached_for_pricing_suggestions()
    {
        Cache::forget('weather_Cairo');

        $this->getJson('/api/v1/pricing-suggestions');

        $this->assertTrue(Cache::has('weather_Cairo'), 'Weather data should be cached');

        $cachedWeather = Cache::get('weather_Cairo');

        $this->assertIsArray($cachedWeather);
        $this->assertArrayHasKey('temperature', $cachedWeather);
        $this->assertArrayHasKey('humidity', $cachedWeather);
        $this->assertArrayHasKey('description', $cachedWeather);
    }
}
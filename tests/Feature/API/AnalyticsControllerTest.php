<?php

namespace Tests\Feature\API;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AnalyticsControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_get_analytics_data()
    {
        $user = User::factory()->create();

        $product1 = Product::factory()->create(['price' => 100]);
        $product2 = Product::factory()->create(['price' => 200]);

        Order::factory()->create([
            'product_id' => $product1->id,
            'user_id' => $user->id,
            'quantity' => 2,
            'total_price' => 200
        ]);

        Order::factory()->create([
            'product_id' => $product2->id,
            'user_id' => $user->id,
            'quantity' => 1,
            'total_price' => 200
        ]);

        $response = $this->getJson('/api/v1/analytics');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'total_revenue',
                    'top_products',
                    'revenue_changes',
                    'orders_count'
                ]
            ]);

        $responseData = $response->json('data');
        $this->assertEquals(400, $responseData['total_revenue']);
        $this->assertEquals(2, $responseData['orders_count']);
    }

    #[Test]
    public function it_returns_empty_data_when_no_orders_exist()
    {
        $response = $this->getJson('/api/v1/analytics');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'total_revenue' => 0,
                    'orders_count' => 0
                ]
            ]);
    }
}

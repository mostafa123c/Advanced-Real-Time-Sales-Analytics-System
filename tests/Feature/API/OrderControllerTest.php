<?php

namespace Tests\Feature\API;

use App\Events\AnalyticsUpdated;
use App\Events\OrderCreated;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_an_order()
    {
        $product = Product::factory()->create([
            'price' => 100,
            'stock_quantity' => 10
        ]);

        $orderData = [
            'product_id' => $product->id,
            'quantity' => 2
        ];

        $response = $this->postJson('/api/v1/orders', $orderData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Order placed successfully'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'total_order_price',
                    'product' => [
                        'id',
                        'name',
                        'price',
                        'quantity'
                    ],
                    'created_at'
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'product_id' => $product->id,
            'quantity' => 2,
            'total_price' => 200
        ]);

        $this->assertEquals(8, $product->fresh()->stock_quantity);
    }

    #[Test]
    public function it_validates_product_stock_availability()
    {
        $product = Product::factory()->create([
            'price' => 100,
            'stock_quantity' => 2
        ]);

        $orderData = [
            'product_id' => $product->id,
            'quantity' => 5
        ];

        $response = $this->postJson('/api/v1/orders', $orderData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['quantity']);

        $this->assertDatabaseCount('orders', 0);

        $this->assertEquals(2, $product->fresh()->stock_quantity);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/v1/orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id', 'quantity']);
    }

    #[Test]
    public function it_dispatches_events_when_order_is_created()
    {
        Event::fake([OrderCreated::class, AnalyticsUpdated::class]);

        $product = Product::factory()->create([
            'price' => 100,
            'stock_quantity' => 10
        ]);

        $response = $this->postJson('/api/v1/orders',  [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        Event::assertDispatched(OrderCreated::class, function ($event) {
            return $event->order->product_id === Product::first()->id;
        });

        Event::assertDispatched(AnalyticsUpdated::class);
    }
}

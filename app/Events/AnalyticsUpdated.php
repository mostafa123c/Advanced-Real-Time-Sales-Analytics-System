<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnalyticsUpdated implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $analytics;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        $this->analytics = [
            'total_revenue' => Order::calculateTotalRevenue(),
            'top_products' => Order::getTopProducts(),
            'revenue_changes' => Order::getRevenueChanges(),
            'orders_count' => Order::lastMinute()->count()
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('sales-analytics'),
        ];
    }

    public function broadcastAs()
    {
        return 'analytics.updated';
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function getAnalytics()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => Order::calculateTotalRevenue(),
                'top_products' => Order::getTopProducts(),
                'revenue_changes' => Order::getRevenueChanges(),
                'orders_count' => Order::lastMinute()->count()
            ]
        ]);
    }
}

<?php

namespace App\Models;

use App\Events\AnalyticsUpdated;
use App\Events\OrderCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'total_price',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($order) {
    //         event(new OrderCreated($order ));
    //         event(new AnalyticsUpdated());
    //     });
    // }


    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }


    public static function LastMinute()
    {
        return DB::table('orders')->where('created_at', '>=', now()->subMinute())->get();
    }


    public static function calculateTotalRevenue()
    {
        return round(DB::table('orders')->sum('total_price'), 3);
    }


    public static function getTopProducts($limit = 6)
    {
        return DB::table('orders')->select('product_id', 'products.name')
            ->selectRaw('SUM(quantity) AS total_sold_quantity, ROUND(SUM(total_price), 3) AS total_revenue')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();
    }


    public static function getRevenueChanges()
    {
        $lastMinuteRevenue = self::LastMinute()->sum('total_price');
        $previousMinuteRevenue = DB::table('orders')
            ->whereBetween('created_at', [now()->subMinute(2), now()->subMinutes()])
            ->sum('total_price');

        $percentage = $previousMinuteRevenue > 0 ? (($lastMinuteRevenue - $previousMinuteRevenue) / $previousMinuteRevenue) * 100 : 0;

        return [
            'last_minute_revenue' => round($lastMinuteRevenue, 3),
            'revenue_change_percentage' => round($percentage, 2)
        ];
    }
}

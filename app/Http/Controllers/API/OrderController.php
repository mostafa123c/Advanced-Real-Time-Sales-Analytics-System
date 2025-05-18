<?php

namespace App\Http\Controllers\API;

use App\Events\AnalyticsUpdated;
use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\OrderDetailsResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        $product = DB::table('products')->where('id', $data['product_id'])->firstOrFail();

        $totalPrice = $product->price * $data['quantity'];

        $order = DB::transaction(function () use ($product, $data, $totalPrice) {

            if ($product->stock_quantity < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Quantity is not available In stock'
                ]);
            }

            DB::table('products')->where('id', $product->id)->decrement('stock_quantity', $data['quantity']);

            $orderId = DB::table('orders')->insertGetId([
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'total_price' => $totalPrice,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return DB::table('orders')
                ->join('products', 'orders.product_id', '=', 'products.id')
                ->select('orders.*', 'products.name', 'products.price')
                ->where('orders.id',  $orderId)
                ->first();
        });

        event(new OrderCreated($order));
        event(new AnalyticsUpdated());

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully',
            'data' => new OrderDetailsResource($order)
        ], 201);
    }
}

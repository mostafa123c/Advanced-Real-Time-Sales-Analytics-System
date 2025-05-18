<?php

use App\Http\Controllers\API\AnalyticsController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\RecommendationController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/analytics', [AnalyticsController::class, 'getAnalytics']);

    Route::get('/recommendations', [RecommendationController::class, 'getRecommendations']);
    Route::get('/pricing-suggestions', [RecommendationController::class, 'getDynamicPricing']);
});
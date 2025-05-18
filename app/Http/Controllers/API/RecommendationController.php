<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AIRecommendationService;
use App\Services\WeatherRecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    private $AIService;
    private $weatherService;

    public function __construct(AIRecommendationService $AIService, WeatherRecommendationService $weatherService)
    {
        $this->AIService = $AIService;
        $this->weatherService = $weatherService;
    }

    public function getRecommendations()
    {
        $recommendations = $this->AIService->openAIRecommendation();
        $weather = $this->weatherService->getWeatherRecommendation();

        return response()->json([
            'sales_recommendations' => $recommendations,
            'weather_recommendations' => $weather
        ], 200);
    }

    public function getDynamicPricing()
    {
        $pricing = $this->weatherService->suggestDynamicPricing();

        return response()->json([
            'pricing_adjustments' => $pricing
        ], 200);
    }
}
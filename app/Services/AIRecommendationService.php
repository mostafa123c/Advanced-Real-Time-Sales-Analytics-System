<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIRecommendationService
{

    private $promptGenerator;

    public function __construct(AIPromptGenerator $promptGenerator)
    {
        $this->promptGenerator = $promptGenerator;
    }


    public function openAIRecommendation()
    {
        $topProducts = Order::getTopProducts(10);
        $totalRevenue = Order::calculateTotalRevenue();

        $userPrompt = $this->promptGenerator->generateUserPrompt($totalRevenue, $topProducts);
        $systemPrompt = $this->promptGenerator->generateSystemMessage();

        return Cache::remember('openai_recommendation', 600, function () use ($userPrompt, $systemPrompt) {

            // open_ai_url https://api.openai.com/v1/chat/completions
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_GITHUB_TOKEN'),
                'Content-Type' => 'application/json',
            ])->post('https://models.github.ai/inference/chat/completions', [
                'model' => 'openai/gpt-4.1-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ]
            ]);
            if ($response->failed()) {
                Log::error($response->json());
                throw new Exception('An error occurred while processing your request. Please try again later.');
            }

            $recommendations = $response->json('choices')[0]['message']['content'];

            return $recommendations;
        });
    }
}
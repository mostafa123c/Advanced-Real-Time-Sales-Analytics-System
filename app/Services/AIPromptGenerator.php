<?php

namespace App\Services;


class AIPromptGenerator
{

    private const TEMPLATES = [

        "user_prompt" =>
        "The user has provided the following sales data for analysis:
            %s
            Based on this information, please provide:
            - Strategic product promotion recommendations to maximize revenue
            - Data-driven pricing adjustment suggestions (increase/decrease)
            - Effective product bundling opportunities
            - Targeted cross-selling strategies
            - Optimization approaches for underperforming products
            - Market positioning insights based on sales patterns",

        "system_prompt" => "You are a senior business strategist specialized in retail sales optimization.Your task is to provide data-driven recommendations based on product sales data.
            Ensure that your analysis is comprehensive and actionable.
            Always return the answer in the following JSON structure and nothing else:
            {
            'recommendations': [
                {
                'title': 'Recommendation title',
                'details': 'Details about the recommendation'
                },
                // up to 6 recommendations
            ],
            'products': [
                {
                'name': 'Product name',
                'reason': 'Why this product should be promoted',
                'action': 'Specific action to take'
                },
                // up to 5 products
            ],
            'pricing_suggestions': [
                {
                'name': 'Product name',
                'suggestion': 'Pricing suggestion details',
                'percentage': 'Percentage change (e.g., +5%, -10%)',
                'rationale': 'Reasoning behind this suggestion'
                },
                // up to 4 pricing suggestions
            ],
            'product_bundling_suggestions': [
                {
                'products': ['Product 1', 'Product 2'],
                'name': 'Bundle name',
                'reason': 'Why these products should be bundled',
                'expected_impact': 'Expected revenue impact'
                },
                // up to 4 bundling suggestions
            ],
            'product_cross_selling_suggestions': [
                {
                'main_product': 'Primary product',
                'cross_sell_products': ['Product 1', 'Product 2'],
                'reason': 'Cross-selling rationale',
                'target_customer': 'Customer segment to target'
                },
                // up to 4 cross-selling suggestions
            ],
            'analysis_summary': 'A concise paragraph summarizing the overall analysis and key trends.'
            }
            "
    ];

    public function generateUserPrompt(string $totalRevenue, Object $topProducts): string
    {
        $message = "Total Revenue: " . $totalRevenue . "\n\n";
        $message .= "Top Products:\n";
        $message .= json_encode($topProducts, JSON_PRETTY_PRINT);

        return sprintf(self::TEMPLATES['user_prompt'], $message);
    }

    public function generateSystemMessage(): string
    {
        return self::TEMPLATES['system_prompt'];
    }
}

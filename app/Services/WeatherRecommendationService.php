<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WeatherRecommendationService
{

    public function getcurrentWeather($city = 'Cairo')
    {

        return Cache::remember("weather_{$city}", 600, function () use ($city) {

            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $city,
                'units' => 'metric',
                'appid' => env('OPENWEATHER_API_KEY')
            ]);

            if ($response->failed()) {
                throw new Exception('An error occurred while processing your request. Please try again later.');
            }

            $weather = $response->json();

            return [
                'temperature' => $weather['main']['temp'],
                'humidity' => $weather['main']['humidity'],
                'description' => $weather['weather'][0]['main']
            ];
        });
    }

    public function getWeatherRecommendation($city = 'Cairo')
    {
        $weather = $this->getcurrentWeather($city);

        $recommendations = [];

        $temp = $weather['temperature'];
        $humidity = $weather['humidity'];
        $weather_description = strtolower($weather['description']);



        // Temperature
        if ($temp >= 35) {
            $recommendations[] = "Promote iced drinks, smoothies, and fresh fruit juices.";
            $recommendations[] = "Offer sunscreen and summer accessories like hats and sunglasses.";
            $recommendations[] = "Push lightweight, breathable clothing.";
        } elseif ($temp >= 25 && $temp < 35) {
            $recommendations[] = "Promote cold beverages and light snacks.";
            $recommendations[] = "Suggest outdoor gear for warm weather.";
        } elseif ($temp >= 15 && $temp < 25) {
            $recommendations[] = "Recommend moderate drinks like iced tea or lukewarm beverages.";
            $recommendations[] = "Suggest casual clothing suitable for mild weather.";
        } elseif ($temp >= 5 && $temp < 15) {
            $recommendations[] = "Promote warm drinks such as coffee and tea.";
            $recommendations[] = "Highlight sweaters and light jackets.";
        } elseif ($temp < 5) {
            $recommendations[] = "Push hot drinks and hearty meals.";
            $recommendations[] = "Offer winter gear like scarves, gloves, and heavy jackets.";
        }


        // Weather description
        $recommendations = array_merge($recommendations, match ($weather_description) {
            'rain', 'drizzle', 'thunderstorm' => [
                "Promote umbrellas, raincoats, and waterproof footwear.",
                "Suggest indoor activities and comfort food.",
                "Push hot beverages and soups.",
            ],
            'snow' => [
                "Highlight snow gear and winter clothing bundles.",
                "Promote hot drinks and warming products.",
            ],
            'clear' => [
                "Suggest outdoor products such as sunglasses and hats.",
                "Promote light and breathable clothing.",
            ],
            'clouds', 'mist', 'fog' => [
                "Suggest casual wear and mild weather gear.",
                "Recommend visibility gear if foggy.",
            ],
            default => [
                "Check out our latest collection for any weather!",
            ],
        });

        // Humidity
        if ($humidity >= 80) {
            $recommendations[] = "Suggest breathable fabrics and cooling towels.";
            $recommendations[] = "Promote hydration products and skincare for humid weather.";
        } elseif ($humidity >= 50 && $humidity < 80) {
            $recommendations[] = "Recommend regular skincare and comfortable clothing.";
        } elseif ($humidity < 50) {
            $recommendations[] = "Promote moisturizing skincare and hydration drinks.";
        }


        return [
            'weather' => $weather,
            'recommendations' => $recommendations
        ];
    }

    public function suggestDynamicPricing($city = 'Cairo')
    {
        $weather = $this->getcurrentWeather($city);


        $temp = $weather['temperature'];
        $humidity = $weather['humidity'];
        $weather_description = strtolower($weather['description']);

        $pricingEdits = [];

        // Temperature
        if ($temp >= 30) {
            $pricingEdits[] = [
                'product_category' => 'Cold Beverages & Summer Essentials',
                'price_adjustment' => '+12%',
                'reason' => 'High temperature increases demand for cooling products',
            ];
        } elseif ($temp < 10) {
            $pricingEdits[] = [
                'product_category' => 'Hot Drinks & Winter Wear',
                'price_adjustment' => '+15%',
                'reason' => 'Low temperature increases demand for warmth-related products',
            ];
        } else {
            $pricingEdits[] = [
                'product_category' => 'Seasonal Basics & Accessories',
                'price_adjustment' => '+3%',
                'reason' => 'Moderate temperature allows for standard seasonal pricing'
            ];
        }

        // Humidity
        if ($humidity >= 80) {
            $pricingEdits[] = [
                'product_category' => 'Dehumidifiers & Breathable Clothing',
                'price_adjustment' => '+8%',
                'reason' => 'High humidity affects comfort and increases demand for airflow and dryness solutions',
            ];
        }

        // Weather description
        match ($weather_description) {
            'rain', 'drizzle', 'thunderstorm' => $pricingEdits[] = [
                'product_category' => 'Rain Gear & Comfort Foods',
                'price_adjustment' => '+10%',
                'reason' => 'Rainy conditions boost interest in cozy, protective items',
            ],
            'snow' => $pricingEdits[] = [
                'product_category' => 'Winter Gear & Heating Products',
                'price_adjustment' => '+15%',
                'reason' => 'Snow increases demand for warmth and protection',
            ],
            'clear' => $pricingEdits[] = [
                'product_category' => 'Outdoor & Travel Essentials',
                'price_adjustment' => '+5%',
                'reason' => 'Clear skies encourage outdoor activities',
            ],
            'clouds' => $pricingEdits[] = [
                'product_category' => 'Lightweight & Breathable Clothing',
                'price_adjustment' => '+3%',
                'reason' => 'Cloudy weather can be unpredictable, so we adjust prices to reflect the varying conditions',
            ],
            default => null,
        };

        return [
            'weather' => $weather,
            'pricing_edits' => $pricingEdits
        ];
    }
}
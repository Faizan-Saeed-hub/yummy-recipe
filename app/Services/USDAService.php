<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class USDAService
{
    private $apiKey;
    private $baseUrl = 'https://api.nal.usda.gov/fdc/v1/foods/search';

    public function __construct()
    {
        $this->apiKey = env('USDA_API_KEY', 'DEMO_KEY'); // Fallback to DEMO_KEY if not set
    }

    /**
     * Fetch nutrition data per 100g for a specific query
     *
     * @param string $query
     * @return array|null
     */
    public function fetchNutrientsPer100g($query)
    {
        $cacheKey = 'usda_nutrient_' . md5($query);

        return Cache::rememberForever($cacheKey, function () use ($query) {
            $response = Http::get($this->baseUrl, [
                'api_key' => $this->apiKey,
                'query' => $query,
                'dataType' => 'Foundation,SR Legacy',
                'pageSize' => 1
            ]);

            if ($response->successful() && isset($response->json()['foods'][0])) {
                $food = $response->json()['foods'][0];
                return $this->extractMacros($food['foodNutrients']);
            }

            return null; // Return null if not found
        });
    }

    /**
     * Extract specific macronutrients/micronutrients from USDA response
     * 
     * @param array $nutrients
     * @return array
     */
    private function extractMacros($nutrients)
    {
        $extracted = [
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fat' => 0,
            'fiber' => 0,
            'sugar' => 0,
            'sodium' => 0,
        ];

        // USDA Nutrient IDs
        // Energy: 1008 (kcal)
        // Protein: 1003 (g)
        // Carbohydrate: 1005 (g)
        // Total lipid (fat): 1004 (g)
        // Fiber: 1079 (g)
        // Sugars: 2000 (g)
        // Sodium: 1093 (mg)
        
        $nutrientMap = [
            1008 => 'calories',
            2047 => 'calories', // Energy (Atwater General Factors)
            2048 => 'calories', // Energy (Atwater Specific Factors)
            1003 => 'protein',
            1005 => 'carbs',
            1004 => 'fat',
            1079 => 'fiber',
            2000 => 'sugar',
            1093 => 'sodium'
        ];

        foreach ($nutrients as $nutrient) {
            $id = $nutrient['nutrientId'];
            if (isset($nutrientMap[$id])) {
                $key = $nutrientMap[$id];
                // Only set if not already set or if it's 0, because we have multiple energy IDs
                if ($extracted[$key] === 0 || $key !== 'calories' || !isset($extracted['calories_found'])) {
                    $extracted[$key] = $nutrient['value'] ?? 0;
                    if ($key === 'calories') {
                        $extracted['calories_found'] = true;
                    }
                }
            }
        }

        return $extracted;
    }
}

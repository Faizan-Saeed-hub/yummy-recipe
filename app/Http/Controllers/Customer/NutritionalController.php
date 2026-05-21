<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\USDAService;
use Illuminate\Support\Facades\File;

class NutritionalController extends Controller
{
    private $usdaService;

    public function __construct(USDAService $usdaService)
    {
        $this->usdaService = $usdaService;
    }

    public function index(){
        return view('customer.layout.nutritional');
    }

    public function getRecipes()
    {
        $path = base_path('models/pakistani_recipes.json');
        if (!File::exists($path)) {
            return response()->json([]);
        }
        $recipes = json_decode(File::get($path), true);
        
        $recipeList = collect($recipes)->map(function ($recipe) {
            return [
                'name' => $recipe['name'],
                'diet_tags' => $recipe['diet_tags'] ?? []
            ];
        })->values();

        return response()->json($recipeList);
    }

    public function getIngredients(Request $request)
    {
        $recipeName = $request->query('recipe');
        if (!$recipeName) {
            return response()->json(['error' => 'Recipe name required'], 400);
        }

        // Load recipes
        $recipesPath = base_path('models/pakistani_recipes.json');
        $recipes = json_decode(File::get($recipesPath), true);
        
        $recipe = collect($recipes)->firstWhere('name', $recipeName);
        if (!$recipe) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        // Load dictionary
        $dictionaryPath = storage_path('app/ingredient_dictionary.json');
        $dictionary = json_decode(File::get($dictionaryPath), true);

        $ingredients = [];
        foreach ($recipe['ingredients'] as $ingredientName) {
            $ingredientNameLower = strtolower($ingredientName);
            if (isset($dictionary[$ingredientNameLower])) {
                $mapped = $dictionary[$ingredientNameLower];
                $ingredients[] = [
                    'display_name' => ucfirst($ingredientName),
                    'usda_query' => $mapped['usda_query'],
                    'default_grams' => $mapped['default_grams']
                ];
            } else {
                // Fallback if not in dictionary
                $ingredients[] = [
                    'display_name' => ucfirst($ingredientName),
                    'usda_query' => $ingredientNameLower . ' raw',
                    'default_grams' => 100
                ];
            }
        }

        return response()->json([
            'recipe' => $recipeName,
            'ingredients' => $ingredients,
            'diet_tags' => $recipe['diet_tags'] ?? []
        ]);
    }

    public function calculate(Request $request)
    {
        $data = $request->validate([
            'ingredients' => 'required|array',
            'ingredients.*.usda_query' => 'required|string',
            'ingredients.*.grams' => 'required|numeric|min:1',
            'diet_tags' => 'nullable|array'
        ]);

        $totals = [
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fats' => 0, // Maps to fat from USDA
            'fiber' => 0,
            'sugar' => 0,
            'sodium' => 0
        ];

        $ingredientBreakdown = [];

        foreach ($data['ingredients'] as $item) {
            $baseNutrients = $this->usdaService->fetchNutrientsPer100g($item['usda_query']);
            
            if ($baseNutrients) {
                $multiplier = $item['grams'] / 100;
                
                $calculated = [
                    'name' => $item['display_name'] ?? $item['usda_query'],
                    'grams' => $item['grams'],
                    'calories' => round($baseNutrients['calories'] * $multiplier, 2),
                    'protein' => round($baseNutrients['protein'] * $multiplier, 2),
                    'carbs' => round($baseNutrients['carbs'] * $multiplier, 2),
                    'fats' => round($baseNutrients['fat'] * $multiplier, 2),
                    'fiber' => round($baseNutrients['fiber'] * $multiplier, 2),
                    'sugar' => round($baseNutrients['sugar'] * $multiplier, 2),
                    'sodium' => round($baseNutrients['sodium'] * $multiplier, 2)
                ];

                $ingredientBreakdown[] = $calculated;

                // Add to totals
                $totals['calories'] += $calculated['calories'];
                $totals['protein'] += $calculated['protein'];
                $totals['carbs'] += $calculated['carbs'];
                $totals['fats'] += $calculated['fats'];
                $totals['fiber'] += $calculated['fiber'];
                $totals['sugar'] += $calculated['sugar'];
                $totals['sodium'] += $calculated['sodium'];
            }
        }

        // Round totals
        foreach ($totals as $key => $value) {
            $totals[$key] = round($value, 2);
        }

        // Generate Health Labels
        $labels = $this->generateHealthLabels($totals, $data['diet_tags'] ?? []);

        return response()->json([
            'totals' => $totals,
            'breakdown' => $ingredientBreakdown,
            'labels' => $labels
        ]);
    }

    private function generateHealthLabels($totals, $dietTags)
    {
        $labels = [];

        // Medical/Rule-based Labels
        if ($totals['protein'] > 20) {
            $labels[] = ['name' => 'High Protein', 'class' => 'bg-success'];
        }
        if ($totals['carbs'] < 20) {
            $labels[] = ['name' => 'Keto Friendly', 'class' => 'bg-info'];
        }
        if ($totals['sodium'] > 800) {
            $labels[] = ['name' => 'High Sodium', 'class' => 'bg-danger'];
        }
        if ($totals['sugar'] > 25) {
            $labels[] = ['name' => 'High Sugar', 'class' => 'bg-warning text-dark'];
        }
        if ($totals['calories'] > 800) {
            $labels[] = ['name' => 'High Calorie', 'class' => 'bg-danger'];
        }

        // Tag-based Labels (from JSON dataset)
        if (in_array('vegetarian', $dietTags)) {
            $labels[] = ['name' => 'Vegetarian', 'class' => 'bg-success'];
        }
        if (in_array('vegan', $dietTags)) {
            $labels[] = ['name' => 'Vegan', 'class' => 'bg-success'];
        }

        return $labels;
    }
}

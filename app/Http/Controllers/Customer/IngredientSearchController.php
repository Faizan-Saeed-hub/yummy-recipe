<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IngredientSearchController extends Controller
{
    public function index()
    {
        $ingredients = $this->loadIngredients();
        $featuredRecipes = $this->loadFeaturedRecipes();

        return view('Customer.Layout.ingredient_search', [
            'ingredients' => $ingredients,
            'featuredRecipes' => $featuredRecipes,
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'ingredients' => 'required|array|min:1|max:10',
            'ingredients.*' => 'string',
            'search_mode' => 'required|in:general,profile',
        ]);

        $ingredients = $this->loadIngredients();
        $recipes = $this->loadRecipes();
        $selectedIngredients = array_values(array_unique(array_map('trim', $request->input('ingredients', []))));
        $searchMode = $request->input('search_mode', 'general');
        $message = null;

        $matched = [];
        $useProfile = false;

        if ($searchMode === 'profile' && Auth::check()) {
            $user = Auth::user();
            if ($user->use_profile_suggestion) {
                $ageGroup = $this->resolveAgeGroup($user->age);
                $dietTags = $this->resolveDietTags($user->dietary_restrictions ?? []);
                $healthTags = $this->resolveHealthTags($user->health_conditions ?? []);
                $matched = $this->filterRecipes($recipes, $selectedIngredients, true, $ageGroup, $dietTags, $healthTags);
                $useProfile = true;
                if (empty($matched)) {
                    $message = 'No recipes are available for your current health, dietary and age profile. We will update soon.';
                }
            } else {
                $message = 'Profile-based suggestions are disabled for your account. Showing general recipes instead.';
            }
        } elseif ($searchMode === 'profile') {
            $message = 'You must login to use profile-based recipe suggestions. Showing general recipes instead.';
        }

        if (empty($matched)) {
            $matched = $this->filterRecipes($recipes, $selectedIngredients, false);
            if (empty($matched) && !$message) {
                $message = 'No general recipes were found for the selected ingredients. Try different ingredients.';
            }
        }

        $resultRecipes = array_slice($matched, 0, 3);

        return view('Customer.Layout.ingredient_search', [
            'ingredients' => $ingredients,
            'recipes' => $resultRecipes,
            'selectedIngredients' => $selectedIngredients,
            'searchMode' => $searchMode,
            'profileMode' => $useProfile,
            'message' => $message,
        ]);
    }

    private function loadIngredients(): array
    {
        $path = base_path('models/pakistani_ingredients.json');
        if (!file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?: [];
    }

    private function loadRecipes(): array
    {
        $path = base_path('models/pakistani_recipes.json');
        if (!file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?: [];
    }

    private function loadFeaturedRecipes(): array
    {
        $recipes = $this->loadRecipes();
        // Return first 3 recipes as featured
        return array_slice($recipes, 0, 3);
    }

    private function resolveAgeGroup($age): string
    {
        if (!$age) {
            return 'all';
        }

        if ($age < 18) {
            return 'young';
        }

        if ($age > 50) {
            return 'senior';
        }

        return 'adult';
    }

    private function resolveDietTags(array $dietary): array
    {
        $map = [
            'Vegetarian' => 'vegetarian',
            'Non-vegetarian' => 'non_vegetarian',
            'Vegan' => 'vegan',
            'Low sugar' => 'low_sugar',
            'Low salt' => 'low_salt',
            'Low fat' => 'low_fat',
            'Gluten free' => 'gluten_free',
            'Dairy free' => 'dairy_free',
            'Heart friendly' => 'heart_friendly',
            'High protein' => 'high_protein',
        ];

        $tags = [];
        foreach ($dietary as $item) {
            if (isset($map[$item])) {
                $tags[] = $map[$item];
            }
        }

        return array_values(array_unique($tags));
    }

    private function resolveHealthTags(array $health): array
    {
        $map = [
            'Diabetes' => 'diabetes_friendly',
            'High blood pressure' => 'hypertension_friendly',
            'Low blood pressure' => 'low_blood_pressure_friendly',
            'Heart disease' => 'heart_friendly',
            'Thyroid' => 'thyroid_friendly',
            'PCOS' => 'pcos_friendly',
            'Kidney issue' => 'kidney_friendly',
            'Joint pain' => 'joint_pain_friendly',
            'Anemia' => 'anemia_friendly',
            'Stomach sensitivity' => 'stomach_friendly',
        ];

        $tags = [];
        foreach ($health as $item) {
            if (isset($map[$item])) {
                $tags[] = $map[$item];
            }
        }

        return array_values(array_unique($tags));
    }

    private function filterRecipes(array $recipes, array $selectedIngredients, bool $profileMode = false, string $ageGroup = 'all', array $dietTags = [], array $healthTags = []): array
    {
        $selectedLower = array_map('strtolower', $selectedIngredients);
        $matches = [];

        foreach ($recipes as $recipe) {
            $recipeIngredients = array_map('strtolower', $recipe['ingredients'] ?? []);
            $matchCount = count(array_intersect($selectedLower, $recipeIngredients));
            if ($matchCount === 0) {
                continue;
            }

            if ($profileMode) {
                $ageGroups = array_map('strtolower', $recipe['age_groups'] ?? ['all']);
                if (!in_array('all', $ageGroups, true) && !in_array($ageGroup, $ageGroups, true)) {
                    continue;
                }

                $recipeTags = array_merge($recipe['diet_tags'] ?? [], $recipe['health_tags'] ?? []);
                $recipeTags = array_map('strtolower', $recipeTags);

                foreach ($dietTags as $tag) {
                    if (!in_array($tag, $recipeTags, true)) {
                        continue 2;
                    }
                }

                foreach ($healthTags as $tag) {
                    if (!in_array($tag, $recipeTags, true)) {
                        continue 2;
                    }
                }
            }

            $matches[] = array_merge($recipe, ['matchCount' => $matchCount]);
        }

        usort($matches, function ($a, $b) {
            return $b['matchCount'] <=> $a['matchCount'];
        });

        return $matches;
    }
}

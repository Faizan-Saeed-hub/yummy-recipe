<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MealPlannerController extends Controller
{
    /**
     * Show the Meal Planner form.
     */
    public function index()
    {
        $user = session('customer');
        return view('Customer.Layout.meal_planner', compact('user'));
    }

    /**
     * Generate a personalised meal plan by calling the Flask AI engine.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'goal'           => 'required|in:gain,loss',
            'current_weight' => 'required|numeric|min:20|max:300',
            'target_weight'  => 'required|numeric|min:20|max:300',
            'duration'       => 'required|in:7,14',
        ]);

        $user = session('customer');

        // Pull health data — prefer form override, fall back to profile
        $healthConditions    = [];
        $dietaryRestrictions = [];

        if ($user && $user->use_profile_suggestion) {
            $healthConditions    = $user->health_conditions    ?? [];
            $dietaryRestrictions = $user->dietary_restrictions ?? [];
        }

        $payload = [
            'goal'                 => $request->goal,
            'weight_kg'            => (float) $request->current_weight,
            'target_weight_kg'     => (float) $request->target_weight,
            'height_cm'            => 170.0, // Default average height
            'age'                  => (int)   ($user && $user->age ? $user->age : 30), // Default age 30
            'gender'               => 'male', // Default gender
            'activity_level'       => 'moderately_active', // Default activity
            'duration_days'        => (int) $request->duration,
            'health_conditions'    => $healthConditions,
            'dietary_restrictions' => $dietaryRestrictions,
        ];

        try {
            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('http://localhost:5000/meal-plan', $payload);

            if ($response->successful()) {
                $plan = $response->json();
                return view('Customer.Layout.meal_plan_result', [
                    'plan'   => $plan,
                    'inputs' => $request->all(),
                    'user'   => $user,
                ]);
            }

            return back()->withErrors([
                'engine' => 'Meal plan engine returned an error: ' . $response->body(),
            ])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors([
                'engine' => 'Could not connect to the meal plan engine. Please ensure the Flask API is running on port 5000.',
            ])->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PredictionController extends Controller
{
    /**
     * Get food predictions from ML model
     * Calls the Flask API running on localhost:5000
     */
    public function predict(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
            'ingredients' => 'nullable|array',
        ]);

        try {
            // Get the uploaded image
            $image = $request->file('image');
            $imagePath = $image->getRealPath();

            // Prepare ingredients metadata
            $ingredients = $request->input('ingredients', []);
            $metadata = [
                'ingredients' => array_map('strtolower', $ingredients),
                'timestamp' => now()->toIso8601String(),
            ];

            // Send request to Flask ML API
            $response = Http::attach(
                'image',
                file_get_contents($imagePath),
                $image->getClientOriginalName()
            )
            ->post('http://localhost:5000/predict', [
                'metadata' => json_encode($metadata),
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'predictions' => $response->json(),
                    'message' => 'Prediction successful',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'ML model prediction failed',
                'error' => $response->body(),
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing prediction',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get model status
     */
    public function modelStatus()
    {
        try {
            $response = Http::timeout(5)->get('http://localhost:5000/status');

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'status' => $response->json(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Model API not responding correctly',
            ], 503);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ML API server not available',
                'error' => $e->getMessage(),
            ], 503);
        }
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageRecognitionControlller extends Controller
{
    public function index(){
        return view('Customer.Layout.image_recognition');
    }

    public function predict(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $imagePath = $request->file('image')->getPathname();
        $imageName = $request->file('image')->getClientOriginalName();

        try {
            $response = Http::attach(
                'image', file_get_contents($imagePath), $imageName
            )->post('http://127.0.0.1:5000/predict');

            if ($response->successful()) {
                $data = $response->json();
                
                // Also pass the image back so it can be displayed
                $imageFile = base64_encode(file_get_contents($imagePath));

                return view('Customer.Layout.image_result', [
                    'prediction' => $data['prediction'],
                    'confidence' => $data['confidence'],
                    'metadata'   => $data['metadata'],
                    'top3'       => $data['top3'],
                    'imageSrc'   => 'data:image/jpeg;base64,' . $imageFile
                ]);
            } else {
                return back()->withErrors(['error' => 'Prediction failed from model API.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Could not connect to the Prediction API.']);
        }
    }
}

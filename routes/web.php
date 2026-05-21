<?php

use App\Http\Controllers\Admin\CategotyController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\MessagesController;
use App\Http\Controllers\Admin\SimpleinputController;
use App\Http\Controllers\Admin\MultipleInputController;
// use App\Http\Controllers\Customer\CustoemrHomeController

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Customer\CustoemrHomeController;
use App\Http\Controllers\Customer\IngredientSearchController;
use App\Http\Controllers\Customer\ImageRecognitionControlller;
use App\Http\Controllers\Customer\CustomerAuthController;
use App\Http\Controllers\Customer\MealPlannerController;
use App\Http\Controllers\Customer\NutritionalController;
use App\Http\Controllers\Customer\CustomerMessagesController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\PredictionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;






//    admin routes

Route::group(['middleware' => ['admin']], function () {
    Route::get('/admin', [AuthController::class, 'index']);
});
Route::post('/authenticate', [AuthController::class, 'authenticate']);
Route::get('/login', [AuthController::class, 'index']);

Route::get('/dashboard', [DashBoardController::class, 'index']);
Route::get('/admin/users', [UsersController::class, 'users']);
Route::get('/admin/messages', [MessagesController::class, 'messages']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// CustoemrHomeController;
Route::get('/', [CustoemrHomeController::class, 'index']);


// contact form
Route::post('/contact', [CustomerMessagesController::class, 'store']);





    //  middleware for customer authentication
Route::middleware('customerAuth')->group(function () {


    // IngredientSearchController
    Route::get('/ingredient/search', [IngredientSearchController::class, 'index']);
    Route::post('/ingredient/search', [IngredientSearchController::class, 'search'])->name('ingredient.search.submit');


    // ImageRecognitionControlller
    Route::get('/image/recognition', [ImageRecognitionControlller::class, 'index']);
    Route::post('/predict', [ImageRecognitionControlller::class, 'predict']);

    // mealplanner
    Route::get('/meal/planner', [MealPlannerController::class, 'index']);
    Route::post('/meal/planner/generate', [MealPlannerController::class, 'generate'])->name('meal.planner.generate');

    //  nutritional
    Route::get('/nutritional/info', [NutritionalController::class, 'index']);
    
    // Nutrition API Routes
    Route::get('/api/recipes', [NutritionalController::class, 'getRecipes']);
    Route::get('/api/recipe-ingredients', [NutritionalController::class, 'getIngredients']);
    Route::post('/api/nutrition/calculate', [NutritionalController::class, 'calculate']);
});




// CustomerAuthController
Route::get('/customer/login', [CustomerAuthController::class, 'index'])->name('login');
Route::get('/customer/registration', [CustomerAuthController::class, 'register']);
Route::post('/customer/store', [CustomerAuthController::class, 'store']);
Route::post('/user/authenticate', [CustomerAuthController::class, 'authenticate']);
Route::get('/customer/logout', [CustomerAuthController::class, 'logout']);



//admin messages deletion
Route::delete('/admin/messages/{id}', [MessagesController::class, 'destroy'])
    ->name('admin.messages.delete');

// ML Model Prediction API Routes
Route::post('/api/predict', [PredictionController::class, 'predict'])->name('predict');
Route::get('/api/model/status', [PredictionController::class, 'modelStatus'])->name('model.status');

// Web Database Migration Route for Vercel/Serverless
Route::get('/migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true, '--seed' => true]);
        return 'Database migrations and seeders ran successfully! Output: <pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return 'Error running migrations: ' . $e->getMessage();
    }
});



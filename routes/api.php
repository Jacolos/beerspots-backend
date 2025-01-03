<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BeerSpotController;
use App\Http\Controllers\API\BeerController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\FavoriteSpotController;
use Illuminate\Support\Facades\Route;

// Trasy publiczne
Route::get('beer-spots', [BeerSpotController::class, 'index']);
Route::get('beer-spots/nearby', [BeerSpotController::class, 'nearby']);
Route::get('beer-spots/nearbywithbeers', [BeerSpotController::class, 'nearbyWithBeers']);
Route::get('beer-spots/{beerSpot}', [BeerSpotController::class, 'show']);
Route::get('beer-spots/{beerSpot}/beers', [BeerController::class, 'index']);
Route::get('beer-spots/{beerSpot}/reviews', [ReviewController::class, 'index']);
Route::get('beer-spots/{beerSpot}/spot-reviews', [ReviewController::class, 'showSpotReviews']);

// Autentykacja
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Trasy chronione - wymagają zalogowania
Route::middleware('auth:sanctum')->group(function () {
    // Zarządzanie punktami sprzedaży
    Route::post('beer-spots', [BeerSpotController::class, 'store']);
    Route::put('beer-spots/{beerSpot}', [BeerSpotController::class, 'update']);
    Route::delete('beer-spots/{beerSpot}', [BeerSpotController::class, 'destroy']);
    Route::put('beer-spots/{beerSpot}/update-status', [BeerSpotController::class, 'updateStatus'])
    ->name('api.beer-spots.update-status');
    
    // Zarządzanie piwami
    Route::post('beer-spots/{beerSpot}/beers', [BeerController::class, 'store']);
    Route::put('beers/{beer}', [BeerController::class, 'update']);
    Route::delete('beers/{beer}', [BeerController::class, 'destroy']);
    
    // Zarządzanie opiniami
    Route::post('beer-spots/{beerSpot}/reviews', [ReviewController::class, 'store']);
    Route::put('reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy']);
    
    // Reports
    Route::middleware(['auth:sanctum', 'throttle:reports'])->group(function () {
        Route::post('reports', [ReportController::class, 'store']);
    });
    Route::get('beer-spots/{beerSpot}/reports', [ReportController::class, 'index']);
    Route::get('reports/{report}', [ReportController::class, 'show']);
    Route::put('reports/{report}', [ReportController::class, 'update']);
    Route::delete('reports/{report}', [ReportController::class, 'destroy']);


    Route::get('favorites', [FavoriteSpotController::class, 'index']);
    Route::post('favorites/{beerSpot}', [FavoriteSpotController::class, 'toggle']);
    Route::get('favorites/{beerSpot}/check', [FavoriteSpotController::class, 'check']);

    // Profil użytkownika
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});

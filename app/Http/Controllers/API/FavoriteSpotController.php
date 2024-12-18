<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BeerSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteSpotController extends Controller
{
    const MAX_FAVORITES = 200;

    public function index()
    {
        $favorites = auth()->user()->favoriteSpots()
            ->with(['beers' => function($query) {
                $query->where('status', 'available')
                      ->orderBy('price', 'asc');
            }])
            ->withCount(['reviews' => function($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        $remainingSlots = self::MAX_FAVORITES - $favorites->count();

        return response()->json([
            'data' => $favorites,
            'meta' => [
                'total' => $favorites->count(),
                'max_allowed' => self::MAX_FAVORITES,
                'remaining_slots' => $remainingSlots
            ]
        ]);
    }

    public function toggle(BeerSpot $beerSpot)
    {
        $user = auth()->user();
        
        // Sprawdź czy punkt jest już ulubiony
        $isFavorite = $user->favoriteSpots()
            ->where('beer_spot_id', $beerSpot->id)
            ->exists();

        if ($isFavorite) {
            // Jeśli jest ulubiony - usuń z ulubionych
            $user->favoriteSpots()->detach($beerSpot->id);
            return response()->json([
                'message' => 'Punkt został usunięty z ulubionych',
                'is_favorite' => false,
                'meta' => [
                    'total' => $user->favoriteSpots()->count(),
                    'max_allowed' => self::MAX_FAVORITES,
                    'remaining_slots' => self::MAX_FAVORITES - $user->favoriteSpots()->count()
                ]
            ]);
        }

        // Sprawdź limit przed dodaniem
        $currentCount = $user->favoriteSpots()->count();
        if ($currentCount >= self::MAX_FAVORITES) {
            return response()->json([
                'message' => 'Osiągnięto maksymalny limit ulubionych miejsc (100). Usuń niektóre miejsca, aby dodać nowe.',
                'is_favorite' => false,
                'meta' => [
                    'total' => $currentCount,
                    'max_allowed' => self::MAX_FAVORITES,
                    'remaining_slots' => 0
                ]
            ], 400);
        }

        // Dodaj do ulubionych
        $user->favoriteSpots()->attach($beerSpot->id);

        return response()->json([
            'message' => 'Punkt został dodany do ulubionych',
            'is_favorite' => true,
            'meta' => [
                'total' => $currentCount + 1,
                'max_allowed' => self::MAX_FAVORITES,
                'remaining_slots' => self::MAX_FAVORITES - ($currentCount + 1)
            ]
        ]);
    }

    public function check(BeerSpot $beerSpot)
    {
        $user = auth()->user();
        $isFavorite = $user->favoriteSpots()
            ->where('beer_spot_id', $beerSpot->id)
            ->exists();

        $currentCount = $user->favoriteSpots()->count();

        return response()->json([
            'is_favorite' => $isFavorite,
            'meta' => [
                'total' => $currentCount,
                'max_allowed' => self::MAX_FAVORITES,
                'remaining_slots' => self::MAX_FAVORITES - $currentCount,
                'can_add_more' => $currentCount < self::MAX_FAVORITES
            ]
        ]);
    }
}
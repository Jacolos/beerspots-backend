<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BeerSpot;
use App\Models\Beer;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewBeerSpotNotification;
use Illuminate\Support\Facades\Notification;

class BeerSpotController extends Controller
{
    public function index(Request $request)
    {
        $query = BeerSpot::query();

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $searchTerm = '%' . $request->search . '%';
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('address', 'like', $searchTerm)
                  ->orWhereHas('beers', function($q) use ($searchTerm) {
                      $q->where('name', 'like', $searchTerm);
                  });
            });
        }

        $beerSpots = $query->where('status', 'active')
            ->withCount(['reviews' => function($query) {
                $query->where('status', 'approved');
            }])
            ->with(['beers' => function($query) {
                $query->where('status', 'available')
                      ->orderBy('price', 'asc');
            }])
            ->paginate(20);
        
        return response()->json([
            'data' => $beerSpots
        ]);
    }

    public function nearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'numeric|min:0|max:50'
        ]);

        $userLat = $request->latitude;
        $userLng = $request->longitude;
        $radius = $request->radius ?? 20;

        $beerSpots = BeerSpot::query()
            ->where('status', 'active')
            ->selectRaw("
                *,
                (
                    6371 * acos(
                        cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    )
                ) AS distance
            ", [$userLat, $userLng, $userLat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();

        return response()->json([
            'data' => $beerSpots
        ]);
    }

public function nearbyWithBeers(Request $request)
{
   $request->validate([
       'latitude' => 'required|numeric|between:-90,90',
       'longitude' => 'required|numeric|between:-180,180',
       'radius' => 'numeric|min:0|max:500' // zwiększamy maksymalny promień do 500 km
   ]);

   $centerLat = $request->latitude;
   $centerLng = $request->longitude;
   $radius = $request->radius ?? 50; // domyślnie 50 km

    // Debugowanie
    \Log::info('Searching spots with parameters:', [
        'center_lat' => $centerLat,
        'center_lng' => $centerLng,
        'radius' => $radius
    ]);

    $beerSpots = BeerSpot::query()
        ->where('status', 'active')
        // POPRAWIONA FORMUŁA HAVERSINE
        ->selectRaw("
            *,
            6371 * 2 * ASIN(
                SQRT(
                    POWER(SIN((RADIANS(?) - RADIANS(latitude)) / 2), 2) +
                    COS(RADIANS(?)) * COS(RADIANS(latitude)) * 
                    POWER(SIN((RADIANS(?) - RADIANS(longitude)) / 2), 2)
                )
            ) as distance
        ", [$centerLat, $centerLat, $centerLng])
        ->having('distance', '<=', $radius)
        ->withCount(['reviews' => function($query) {
            $query->where('status', 'approved');
        }])
        ->with(['beers' => function($query) {
            $query->where('status', 'available')
                  ->orderBy('price', 'asc');
        }])
        ->limit(50000)
        ->get()
        ->map(function($spot) {
           // Determine if the spot is currently open
           $currentTime = now();
           $dayOfWeek = strtolower($currentTime->format('l'));
           $openStatus = 'closed';

           // Check if opening hours exist and are properly formatted
           if (!empty($spot->opening_hours) && 
               isset($spot->opening_hours[$dayOfWeek]) && 
               is_array($spot->opening_hours[$dayOfWeek])) {
               
               $openHours = $spot->opening_hours[$dayOfWeek];
               
               // Check for explicit closure
               if (isset($openHours['closed']) && $openHours['closed']) {
                   $openStatus = 'closed';
               } 
               // Ensure both open and close times exist and are strings
               elseif (
                   isset($openHours['open']) && 
                   isset($openHours['close']) && 
                   is_string($openHours['open']) && 
                   is_string($openHours['close'])
               ) {
                   try {
                       $openTime = \Carbon\Carbon::createFromTimeString($openHours['open']);
                       $closeTime = \Carbon\Carbon::createFromTimeString($openHours['close']);

                       // Handle late night hours (past midnight)
                       if ($closeTime < $openTime) {
                           $closeTime->addDay();
                       }

                       $openStatus = $currentTime->between($openTime, $closeTime) ? 'open' : 'closed';
                   } catch (\Exception $e) {
                       \Log::warning('Failed to parse opening hours', [
                           'spot_id' => $spot->id,
                           'spot_name' => $spot->name,
                           'open_hours' => $openHours,
                           'error' => $e->getMessage()
                       ]);
                       $openStatus = 'unknown';
                   }
               }
           }

           // Get cheapest available beer
           $cheapestBeer = $spot->beers->where('status', 'available')->sortBy('price')->first();

           return [
               'id' => $spot->id,
               'name' => $spot->name,
               'address' => $spot->address,
               'latitude' => $spot->latitude,
               'longitude' => $spot->longitude,
               'description' => $spot->description,
               'open' => $openStatus,
               'status' => $spot->status,
               'verified' => $spot->verified,
               'average_rating' => $spot->average_rating,
               'review_count' => $spot->reviews_count,
               'cheapest_beer' => $cheapestBeer ? $cheapestBeer->name : null,
               'price' => $cheapestBeer ? $cheapestBeer->price : null,
               'distance' => round($spot->distance, 2) // zaokrąglamy do 2 miejsc po przecinku
           ];
       });

   return response()->json([
       'data' => $beerSpots
   ]);
}

    public function show(BeerSpot $beerSpot)
    {
    $isFavorite = auth()->check() ? 
        auth()->user()->favoriteSpots()->where('beer_spot_id', $beerSpot->id)->exists() : 
        false;

    $data = $beerSpot->load([
        'beers' => function($query) {
            $query->where('status', 'available')
                  ->orderBy('price', 'asc');
        },
        'reviews' => function($query) {
            $query->where('status', 'approved')
                  ->with('user:id,name');
        }
    ]);
    
    $data = $data->toArray();
    $data['is_favorite'] = $isFavorite;

    return response()->json([
        'data' => $data
    ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'opening_hours' => 'nullable|array'
        ]);

        $beerSpot = BeerSpot::create($validated + [
            'status' => 'pending',
            'verified' => false
        ]);

        $admins = User::role('admin')->get();
        Notification::send($admins, new NewBeerSpotNotification($beerSpot));

        return response()->json([
            'data' => $beerSpot,
            'message' => 'Beer spot created successfully'
        ], 201);
    }

    public function update(Request $request, BeerSpot $beerSpot)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
            'latitude' => 'sometimes|required|numeric|between:-90,90',
            'longitude' => 'sometimes|required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'opening_hours' => 'nullable|array'
        ]);

        $beerSpot->update($validated);
        
        return response()->json([
            'data' => $beerSpot,
            'message' => 'Beer spot updated successfully'
        ]);
    }

public function updateStatus(Request $request, BeerSpot $beerSpot)
{
    $validated = $request->validate([
        'status' => 'required|in:active,inactive,pending',
        'verified' => 'required|boolean'
    ]);

    $beerSpot->update($validated);
    
    return response()->json([
        'data' => $beerSpot,
        'message' => 'Beer spot status updated successfully'
    ]);
}

    public function destroy(BeerSpot $beerSpot)
    {
        $beerSpot->delete();
        
        return response()->json([
            'message' => 'Beer spot deleted successfully'
        ]);
    }
}
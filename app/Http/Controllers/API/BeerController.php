<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Beer;
use App\Models\BeerSpot;
use Illuminate\Http\Request;

class BeerController extends Controller
{
    public function index(BeerSpot $beerSpot)
    {
        $beers = $beerSpot->beers()
            ->where('status', 'available')
            ->get();
            
        return response()->json([
            'data' => $beers
        ]);
    }

    public function store(Request $request, BeerSpot $beerSpot)
    {
        \Log::info('Próba dodania piwa', [
            'user' => auth()->user(),
            'data' => $request->all()
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'alcohol_percentage' => 'nullable|numeric|between:0,100',
            'status' => 'nullable|string|in:available,unavailable'
        ]);

        $beer = $beerSpot->beers()->create($validated + [
            'status' => $validated['status'] ?? 'available'
        ]);
        
        return response()->json([
            'data' => $beer,
            'message' => 'Beer created successfully'
        ], 201);
    }

    public function update(Request $request, Beer $beer)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|string|max:255',
            'alcohol_percentage' => 'nullable|numeric|between:0,100',
            'status' => 'sometimes|required|string|in:available,unavailable'
        ]);

        $beer->update($validated);
        
        return response()->json([
            'data' => $beer,
            'message' => 'Beer updated successfully'
        ]);
    }

    public function destroy(Beer $beer)
    {
        $beer->delete();
        
        return response()->json([
            'message' => 'Beer deleted successfully'
        ]);
    }
}
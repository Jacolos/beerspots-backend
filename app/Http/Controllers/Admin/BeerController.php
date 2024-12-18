<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beer;
use App\Models\BeerSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeerController extends Controller
{
    public function index(Request $request)
    {
        $query = Beer::with(['beerSpot'])
            ->when($request->search, function($q) use ($request) {
                $q->where(function($query) use ($request) {
                    $searchTerm = '%' . $request->search . '%';
                    $query->where('name', 'like', $searchTerm)
                          ->orWhere('type', 'like', $searchTerm)
                          ->orWhereHas('beerSpot', function($q) use ($searchTerm) {
                              $q->where('name', 'like', $searchTerm);
                          });
                });
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            });

        $beers = $query->latest()->paginate(20);

        return view('admin.beers.index', compact('beers'));
    }

    public function create()
    {
        $beerSpots = BeerSpot::verified()->pluck('name', 'id');
        return view('admin.beers.create', compact('beerSpots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'beer_spot_id' => 'required|exists:beer_spots,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'alcohol_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:available,unavailable,pending',
        ]);

        Beer::create($validated);

        return redirect()
            ->route('admin.beers.index')
            ->with('success', 'Piwo zostało dodane pomyślnie');
    }

    public function edit(Beer $beer)
    {
        $beerSpots = BeerSpot::verified()->pluck('name', 'id');
        return view('admin.beers.edit', compact('beer', 'beerSpots'));
    }

    public function update(Request $request, Beer $beer)
    {
        $validated = $request->validate([
            'beer_spot_id' => 'sometimes|required|exists:beer_spots,id',
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|string|max:255',
            'alcohol_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'sometimes|required|in:available,unavailable,pending',
        ]);

        $beer->update($validated);

        return redirect()
            ->route('admin.beers.index')
            ->with('success', 'Piwo zostało zaktualizowane pomyślnie');
    }

    public function destroy(Beer $beer)
    {
        try {
            $beer->delete();
            return redirect()
                ->route('admin.beers.index')
                ->with('success', 'Piwo zostało usunięte pomyślnie');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Wystąpił błąd podczas usuwania piwa');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:beers,id'
        ]);

        try {
            $count = Beer::whereIn('id', $validated['ids'])->delete();
            return back()->with('success', "Usunięto pomyślnie {$count} piw");
        } catch (\Exception $e) {
            return back()->with('error', 'Wystąpił błąd podczas masowego usuwania');
        }
    }

    public function bulkUpdateStatus(Request $request, $action)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:beers,id'
        ]);

        $status = match ($action) {
            'makeAvailable' => 'available',
            'makeUnavailable' => 'unavailable',
            default => null,
        };

        if (!$status) {
            return back()->with('error', 'Nieprawidłowa akcja');
        }

        try {
            $count = Beer::whereIn('id', $validated['ids'])->update([
                'status' => $status,
                'updated_at' => now()
            ]);

            $message = match ($status) {
                'available' => 'oznaczono jako dostępne',
                'unavailable' => 'oznaczono jako niedostępne',
            };

            return back()->with('success', "Pomyślnie {$message} {$count} piw");
        } catch (\Exception $e) {
            return back()->with('error', 'Wystąpił błąd podczas aktualizacji statusów');
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeerSpot;
use Illuminate\Http\Request;

class BeerSpotController extends Controller
{
    public function index(Request $request)
    {
        $query = BeerSpot::with(['user', 'beers'])
            ->withCount('beers')
            ->withAvg('reviews', 'rating')
            ->when($request->search, function($q) use ($request) {
                $q->where(function($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%")
                          ->orWhere('address', 'like', "%{$request->search}%");
                });
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->verified !== null, function($q) use ($request) {
                $q->where('verified', $request->verified);
            });

        $stats = [
            'total' => BeerSpot::count(),
            'active' => BeerSpot::where('status', 'active')->count(),
            'pending' => BeerSpot::where('status', 'pending')->count(),
            'inactive' => BeerSpot::where('status', 'inactive')->count(),
        ];

        $beerSpots = $query->latest()->paginate(20);

        return view('admin.beer-spots.index', compact('beerSpots', 'stats'));
    }

    public function create()
    {
        return view('admin.beer-spots.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateBeerSpot($request);
        
        $beerSpot = BeerSpot::create($validated + [
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.beer-spots.show', $beerSpot)
            ->with('success', 'Punkt sprzedaży został dodany pomyślnie.');
    }

    public function show(BeerSpot $beerSpot)
    {
        $beerSpot->load([
            'beers',
            'reviews' => function($query) {
                $query->with('user')
                      ->latest()
                      ->take(5);
            },
            'user'
        ]);

        return view('admin.beer-spots.show', compact('beerSpot'));
    }

    public function edit(BeerSpot $beerSpot)
    {
        return view('admin.beer-spots.edit', compact('beerSpot'));
    }

    public function update(Request $request, BeerSpot $beerSpot)
    {
        $validated = $this->validateBeerSpot($request, $beerSpot);
        
        $beerSpot->update($validated);

        return redirect()
            ->route('admin.beer-spots.show', $beerSpot)
            ->with('success', 'Punkt sprzedaży został zaktualizowany pomyślnie.');
    }

    public function verify(BeerSpot $beerSpot)
    {
        $beerSpot->update([
            'verified' => true,
            'status' => 'active',
            'verified_at' => now(),
            'verified_by' => auth()->id()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Punkt sprzedaży został zweryfikowany pomyślnie.');
    }
public function unverify(BeerSpot $beerSpot)
{
    $beerSpot->update([
        'verified' => false
    ]);

    return redirect()
        ->back()
        ->with('success', 'Weryfikacja punktu sprzedaży została usunięta');
}

public function bulkUnverify(Request $request)
{
    $validated = $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:beer_spots,id'
    ]);

    BeerSpot::whereIn('id', $validated['ids'])->update([
        'verified' => false
    ]);

    return redirect()
        ->back()
        ->with('success', 'Weryfikacja wybranych punktów sprzedaży została usunięta');
}

    public function destroy(BeerSpot $beerSpot)
    {
        try {
            $beerSpot->delete();
            return redirect()
                ->route('admin.beer-spots.index')
                ->with('success', 'Punkt sprzedaży został usunięty pomyślnie.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Wystąpił błąd podczas usuwania punktu sprzedaży.');
        }
    }

    public function bulkVerify(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:beer_spots,id'
        ]);

        try {
            $count = BeerSpot::whereIn('id', $validated['ids'])->update([
                'verified' => true,
                'status' => 'active',
                'verified_at' => now(),
                'verified_by' => auth()->id()
            ]);

            return back()->with('success', "Zweryfikowano pomyślnie {$count} punktów sprzedaży.");
        } catch (\Exception $e) {
            return back()->with('error', 'Wystąpił błąd podczas masowej weryfikacji.');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:beer_spots,id'
        ]);

        try {
            $count = BeerSpot::whereIn('id', $validated['ids'])->delete();
            return back()->with('success', "Usunięto pomyślnie {$count} punktów sprzedaży.");
        } catch (\Exception $e) {
            return back()->with('error', 'Wystąpił błąd podczas masowego usuwania.');
        }
    }

    public function bulkUpdateStatus(Request $request, $action)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:beer_spots,id'
        ]);

        $status = $action === 'activate' ? 'active' : 'inactive';

        try {
            $count = BeerSpot::whereIn('id', $validated['ids'])->update([
                'status' => $status,
                'updated_at' => now()
            ]);

            $message = $action === 'activate' ? 'aktywowano' : 'dezaktywowano';
            return back()->with('success', "Pomyślnie {$message} {$count} punktów sprzedaży.");
        } catch (\Exception $e) {
            return back()->with('error', 'Wystąpił błąd podczas aktualizacji statusów.');
        }
    }

    private function validateBeerSpot(Request $request, ?BeerSpot $beerSpot = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'opening_hours' => 'nullable|array',
            'opening_hours.*' => 'array',
            'status' => 'required|in:active,inactive,pending',
            'verified' => 'sometimes|boolean',
        ]);
    }
}
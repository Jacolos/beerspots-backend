<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beer;
use App\Models\BeerSpot;
use Illuminate\Http\Request;

class BeerController extends Controller
{
    public function index(Request $request)
    {
        $query = Beer::with('beerSpot')
            ->when($request->search, function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
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
            'type' => 'nullable|string',
            'alcohol_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:available,unavailable',
        ]);

        Beer::create($validated);

        return redirect()
            ->route('admin.beers.index')
            ->with('success', 'Beer added successfully');
    }

    public function edit(Beer $beer)
    {
        $beerSpots = BeerSpot::verified()->pluck('name', 'id');
        return view('admin.beers.edit', compact('beer', 'beerSpots'));
    }

    public function update(Request $request, Beer $beer)
    {
        $validated = $request->validate([
            'beer_spot_id' => 'required|exists:beer_spots,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'alcohol_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:available,unavailable',
        ]);

        $beer->update($validated);

        return redirect()
            ->route('admin.beers.index')
            ->with('success', 'Beer updated successfully');
    }

    public function destroy(Beer $beer)
    {
        $beer->delete();

        return redirect()
            ->route('admin.beers.index')
            ->with('success', 'Beer deleted successfully');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeerSpot;
use App\Models\Beer;
use App\Models\Review;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            // Basic counts
            'total_spots' => BeerSpot::count(),
            'pending_spots' => BeerSpot::where('status', 'pending')->count(),
            'total_beers' => Beer::count(),
            'total_reviews' => Review::count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'total_users' => User::count(),
            
            // Additional statistics
            'verified_spots' => BeerSpot::where('verified', true)->count(),
            'available_beers' => Beer::where('status', 'available')->count(),
            'average_beer_price' => Beer::where('status', 'available')->avg('price'),
            'approved_reviews' => Review::where('status', 'approved')->count(),
            'average_rating' => Review::where('status', 'approved')->avg('rating'),
            'users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            
            // Latest entries
            'latest_spots' => BeerSpot::with('user')
                ->latest()
                ->take(5)
                ->get(),
            'latest_reviews' => Review::with(['user', 'beerSpot'])
                ->latest()
                ->take(5)
                ->get(),
            'latest_users' => User::latest()
                ->take(5)
                ->get(),
            'latest_reports' => Report::with(['user', 'beerSpot'])
                ->latest()
                ->take(5)
                ->get(),

                
            // Monthly statistics
            'monthly_reviews' => Review::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray(),
                
            'monthly_users' => User::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray(),

            // Reports statistics
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'resolved_reports' => Report::where('status', 'resolved')->count(),
            'recent_reports' => Report::with(['user', 'beerSpot'])
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            

        ];

        return view('admin.dashboard', compact('stats'));
    }
}
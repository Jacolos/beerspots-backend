<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Prepare query
        $query = Review::with(['user', 'beerSpot'])
            ->when($request->filled('search'), function($q) use ($request) {
                $searchTerm = '%' . $request->search . '%';
                $q->where(function($query) use ($searchTerm) {
                    $query->whereHas('user', function($q) use ($searchTerm) {
                        $q->where('name', 'like', $searchTerm)
                          ->orWhere('email', 'like', $searchTerm);
                    })
                    ->orWhereHas('beerSpot', function($q) use ($searchTerm) {$q->where('name', 'like', $searchTerm);
                    })
                    ->orWhere('comment', 'like', $searchTerm);
                });
            })
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('rating'), function($q) use ($request) {
                $q->where('rating', $request->rating);
            });

        // Get reviews with pagination
        $reviews = $query->latest()->paginate(20)->withQueryString();

        // Calculate statistics
        $stats = $this->getStatistics();

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'beerSpot']);
        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->update([
            'status' => 'approved',
            'moderated_at' => now(),
            'moderated_by' => auth()->id()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Opinia została zatwierdzona.');
    }

    public function reject(Review $review)
    {
        $review->update([
            'status' => 'rejected',
            'moderated_at' => now(),
            'moderated_by' => auth()->id()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Opinia została odrzucona.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Opinia została usunięta.');
    }

    public function bulk(Request $request, $action)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reviews,id'
        ]);

        $reviews = Review::whereIn('id', $validated['ids']);

        switch ($action) {
            case 'approve':
                $reviews->update([
                    'status' => 'approved',
                    'moderated_at' => now(),
                    'moderated_by' => auth()->id()
                ]);
                $message = 'Wybrane opinie zostały zatwierdzone.';
                break;

            case 'reject':
                $reviews->update([
                    'status' => 'rejected',
                    'moderated_at' => now(),
                    'moderated_by' => auth()->id()
                ]);
                $message = 'Wybrane opinie zostały odrzucone.';
                break;

            case 'delete':
                $reviews->delete();
                $message = 'Wybrane opinie zostały usunięte.';
                break;

            default:
                return redirect()->back()->with('error', 'Nieprawidłowa akcja.');
        }

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', $message);
    }

    private function getStatistics()
    {
        // Get current month start and end dates
        $currentMonthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();

        // Get total reviews counts
        $totalReviews = Review::count();
        $pendingReviews = Review::where('status', 'pending')->count();
        
        // Get current month reviews count
        $currentMonthReviews = Review::whereBetween('created_at', [
            $currentMonthStart,
            Carbon::now()
        ])->count();

        // Get last month reviews count
        $lastMonthReviews = Review::whereBetween('created_at', [
            $lastMonthStart,
            $lastMonthStart->copy()->endOfMonth()
        ])->count();

        // Calculate month-over-month change
        $monthlyChange = $lastMonthReviews > 0 
            ? round((($currentMonthReviews - $lastMonthReviews) / $lastMonthReviews) * 100, 1)
            : 100;

        // Get average rating
        $averageRating = Review::where('status', 'approved')
            ->avg('rating');

        return [
            [
                'label' => 'Wszystkie opinie',
                'value' => $totalReviews,
                'change' => $monthlyChange
            ],
            [
                'label' => 'Oczekujące na moderację',
                'value' => $pendingReviews
            ],
            [
                'label' => 'Średnia ocena',
                'value' => number_format($averageRating ?? 0, 1)
            ],
            [
                'label' => 'W tym miesiącu',
                'value' => $currentMonthReviews
            ]
        ];
    }
}
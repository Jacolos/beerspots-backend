<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\BeerSpot;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewReviewNotification;
use Illuminate\Support\Facades\Notification;

class ReviewController extends Controller
{
    public function index(BeerSpot $beerSpot)
    {
        $reviews = $beerSpot->reviews()
            ->with('user')
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'data' => $reviews
        ]);
    }

    public function showSpotReviews(BeerSpot $beerSpot)
    {
        $reviews = $beerSpot->reviews()
            ->with('user')
            ->where('status', 'approved')
            ->select('id', 'user_id', 'rating', 'comment', 'visit_date', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => [
                        'name' => $review->user->name,
                        'id' => $review->user->id
                    ],
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'visit_date' => $review->visit_date->format('Y-m-d'),
                    'created_at' => $review->created_at->format('Y-m-d H:i:s')
                ];
            });

        return response()->json([
            'data' => [
                'spot_id' => $beerSpot->id,
                'spot_name' => $beerSpot->name,
                'average_rating' => round($reviews->avg('rating'), 1),
                'total_reviews' => $reviews->count(),
                'reviews' => $reviews
            ]
        ]);
    }

    public function store(Request $request, BeerSpot $beerSpot)
    {
        $validated = $request->validate([
            'rating' => 'required|numeric|between:1,5',
            'comment' => 'required|string',
            'visit_date' => 'required|date'
        ]);


    $existingReview = $beerSpot->reviews()
        ->where('user_id', auth()->id())
        ->first();

    if ($existingReview) {
        return response()->json([
            'message' => 'Już wystawiłeś opinie w tej lokalizacji, jeśli jeszcze jej nie ma, poczekać na akceptacje.'
        ], 400);
    }


        $review = $beerSpot->reviews()->create($validated + [
            'user_id' => auth()->id(),
            'status' => 'pending'
        ]);
        
    	// Powiadom wszystkich adminów
    	//$admins = User::role('admin')->get();
    	//Notification::send($admins, new NewReviewNotification($review));

        return response()->json([
            'data' => $review,
            'message' => 'Review created successfully'
        ], 201);
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'rating' => 'sometimes|required|numeric|between:1,5',
            'comment' => 'sometimes|required|string',
            'visit_date' => 'sometimes|required|date'
        ]);

        $review->update($validated);
        
        return response()->json([
            'data' => $review,
            'message' => 'Review updated successfully'
        ]);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        
        return response()->json([
            'message' => 'Review deleted successfully'
        ]);
    }
}
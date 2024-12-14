<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'beer_spot_id',
        'rating',
        'comment',
        'visit_date',
        'status'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'rating' => 'float'
    ];

    // Relacje
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beerSpot()
    {
        return $this->belongsTo(BeerSpot::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Events
    protected static function booted()
    {
        static::created(function ($review) {
            $review->beerSpot->recalculateAverageRating();
        });

        static::updated(function ($review) {
            $review->beerSpot->recalculateAverageRating();
        });

        static::deleted(function ($review) {
            $review->beerSpot->recalculateAverageRating();
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class BeerSpot extends Model
{
    use HasFactory;

protected $fillable = [
    'name',
    'address',
    'latitude',
    'longitude',
    'description',
    'opening_hours',
    'status',
    'verified',
    'verified_at',
    'verified_by'
];

    protected $casts = [
        'opening_hours' => 'array',
        'verified' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'average_rating' => 'float'
    ];

    // Relacje
    public function beers()
    {
        return $this->hasMany(Beer::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeNearby($query, $latitude, $longitude, $radius = 5)
    {
        return $query->select(DB::raw('*, 
            ( 6371 * acos( cos( radians(?) ) * 
            cos( radians( latitude ) ) * 
            cos( radians( longitude ) - radians(?) ) + 
            sin( radians(?) ) * 
            sin( radians( latitude ) ) ) ) AS distance'))
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->setBindings([$latitude, $longitude, $latitude]);
    }

    // Metody pomocnicze
    public function recalculateAverageRating()
    {
	    $this->average_rating = $this->reviews()
	        ->where('status', 'approved')
	        ->avg('rating');
	    $this->save();
    }

    public function isOpenNow()
    {
        if (!$this->opening_hours) return false;
        
        $now = now();
        $dayOfWeek = strtolower($now->format('l'));
        $currentTime = $now->format('H:i');
        
        return isset($this->opening_hours[$dayOfWeek]) && 
               $currentTime >= $this->opening_hours[$dayOfWeek]['open'] && 
               $currentTime <= $this->opening_hours[$dayOfWeek]['close'];
    }

    public function getCheapestBeerPrice()
    {
        return $this->beers()->min('price');
    }

    public function getMostExpensiveBeerPrice()
    {
        return $this->beers()->max('price');
    }

public function reports()
{
    return $this->hasMany(Report::class);
}

public function favoriteUsers()
{
    return $this->belongsToMany(User::class, 'favorite_spots')
                ->withTimestamps();
}

}

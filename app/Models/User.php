<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Dodajemy ten import
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relacje
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function beerSpots()
    {
        return $this->hasMany(BeerSpot::class);
    }

    // Metody pomocnicze
    public function hasReviewedBeerSpot($beerSpotId)
    {
        return $this->reviews()
            ->where('beer_spot_id', $beerSpotId)
            ->exists();
    }


public function reports()
{
    return $this->hasMany(Report::class);
}
public function favoriteSpots()
{
    return $this->belongsToMany(BeerSpot::class, 'favorite_spots')
                ->withTimestamps();
}
}
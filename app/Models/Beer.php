<?php

// app/Models/Beer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Beer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'beer_spot_id',
        'description',
        'type',
        'alcohol_percentage',
        'status'
    ];

    protected $casts = [
        'price' => 'float',
        'alcohol_percentage' => 'float'
    ];

    // Relacje
    public function beerSpot()
    {
        return $this->belongsTo(BeerSpot::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }
}
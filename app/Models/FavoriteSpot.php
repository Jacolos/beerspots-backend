// app/Models/FavoriteSpot.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteSpot extends Model
{
    protected $fillable = [
        'user_id',
        'beer_spot_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beerSpot()
    {
        return $this->belongsTo(BeerSpot::class);
    }
}
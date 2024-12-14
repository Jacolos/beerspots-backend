<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\BeerSpot;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Stwórz kilku użytkowników do recenzji
        $users = [];
        for ($i = 1; $i <= 3; $i++) {
            $users[] = User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => bcrypt('password123')
            ]);
        }

        $piwnaPrzystan = BeerSpot::where('name', 'Piwna Przystań')->first();
        $chmielowyOgrodek = BeerSpot::where('name', 'Chmielowy Ogródek')->first();

        // Recenzje dla Piwnej Przystani
        Review::create([
            'user_id' => $users[0]->id,
            'beer_spot_id' => $piwnaPrzystan->id,
            'rating' => 4.5,
            'comment' => 'Świetne miejsce na wieczorne spotkanie. Duży wybór piw w dobrych cenach.',
            'visit_date' => now()->subDays(5),
            'status' => 'approved'
        ]);

        Review::create([
            'user_id' => $users[1]->id,
            'beer_spot_id' => $piwnaPrzystan->id,
            'rating' => 4.0,
            'comment' => 'Dobre piwo, miła obsługa, ale trochę głośno.',
            'visit_date' => now()->subDays(3),
            'status' => 'approved'
        ]);

        // Recenzje dla Chmielowego Ogródka
        Review::create([
            'user_id' => $users[2]->id,
            'beer_spot_id' => $chmielowyOgrodek->id,
            'rating' => 4.5,
            'comment' => 'Fantastyczny ogródek! Idealne miejsce na letnie wieczory.',
            'visit_date' => now()->subDays(2),
            'status' => 'approved'
        ]);

        Review::create([
            'user_id' => $users[0]->id,
            'beer_spot_id' => $chmielowyOgrodek->id,
            'rating' => 3.5,
            'comment' => 'Dobre miejsce, ale ceny mogłyby być niższe.',
            'visit_date' => now()->subDays(1),
            'status' => 'pending'
        ]);
    }
}
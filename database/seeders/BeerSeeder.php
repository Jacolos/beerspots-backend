<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Beer;
use App\Models\BeerSpot;

class BeerSeeder extends Seeder
{
    public function run()
    {
        $piwnaPrzystan = BeerSpot::where('name', 'Piwna Przystań')->first();
        $chmielowyOgrodek = BeerSpot::where('name', 'Chmielowy Ogródek')->first();

        // Piwa dla Piwnej Przystani
        Beer::create([
            'beer_spot_id' => $piwnaPrzystan->id,
            'name' => 'Tyskie Gronie',
            'price' => 8.50,
            'description' => 'Klasyczne polskie piwo jasne',
            'type' => 'lager',
            'alcohol_percentage' => 5.2,
            'status' => 'available'
        ]);

        Beer::create([
            'beer_spot_id' => $piwnaPrzystan->id,
            'name' => 'Żywiec Porter',
            'price' => 9.50,
            'description' => 'Ciemne piwo o intensywnym smaku',
            'type' => 'porter',
            'alcohol_percentage' => 9.5,
            'status' => 'available'
        ]);

        // Piwa dla Chmielowego Ogródka
        Beer::create([
            'beer_spot_id' => $chmielowyOgrodek->id,
            'name' => 'Książęce Pszeniczne',
            'price' => 9.00,
            'description' => 'Orzeźwiające piwo pszeniczne',
            'type' => 'wheat',
            'alcohol_percentage' => 4.8,
            'status' => 'available'
        ]);

        Beer::create([
            'beer_spot_id' => $chmielowyOgrodek->id,
            'name' => 'Perła Export',
            'price' => 7.50,
            'description' => 'Klasyczne jasne piwo',
            'type' => 'lager',
            'alcohol_percentage' => 5.6,
            'status' => 'available'
        ]);
    }
}
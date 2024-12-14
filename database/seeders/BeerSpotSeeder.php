<?php
// database/seeders/BeerSpotSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BeerSpot;

class BeerSpotSeeder extends Seeder
{
    public function run()
    {
        BeerSpot::create([
            'name' => 'Piwna Przystań',
            'address' => 'ul. Piwna 15, Warszawa',
            'latitude' => 52.229676,
            'longitude' => 21.012229,
            'description' => 'Najlepsze piwo rzemieślnicze w okolicy!',
            'opening_hours' => [
                'monday' => ['open' => '12:00', 'close' => '23:00'],
                'tuesday' => ['open' => '12:00', 'close' => '23:00'],
                'wednesday' => ['open' => '12:00', 'close' => '23:00'],
                'thursday' => ['open' => '12:00', 'close' => '23:00'],
                'friday' => ['open' => '12:00', 'close' => '01:00'],
                'saturday' => ['open' => '14:00', 'close' => '01:00'],
                'sunday' => ['open' => '14:00', 'close' => '22:00']
            ],
            'status' => 'active',
            'verified' => true,
            'average_rating' => 4.5
        ]);

        BeerSpot::create([
            'name' => 'Chmielowy Ogródek',
            'address' => 'ul. Chmielna 8, Warszawa',
            'latitude' => 52.235869,
            'longitude' => 21.018493,
            'description' => 'Piwo i grill w ogródku piwnym',
            'opening_hours' => [
                'monday' => ['open' => '15:00', 'close' => '22:00'],
                'tuesday' => ['open' => '15:00', 'close' => '22:00'],
                'wednesday' => ['open' => '15:00', 'close' => '22:00'],
                'thursday' => ['open' => '15:00', 'close' => '23:00'],
                'friday' => ['open' => '15:00', 'close' => '24:00'],
                'saturday' => ['open' => '13:00', 'close' => '24:00'],
                'sunday' => ['closed' => true]
            ],
            'status' => 'active',
            'verified' => true,
            'average_rating' => 4.2
        ]);
    }
}
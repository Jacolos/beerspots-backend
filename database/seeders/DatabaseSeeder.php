<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            BeerSpotSeeder::class,
            BeerSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Tworzenie roli admin jeśli nie istnieje
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Tworzenie użytkownika admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@jacolos.pl',
            'password' => Hash::make('lol123'), // zmień to hasło!
        ]);

        // Przypisanie roli admin
        $admin->assignRole('admin');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'name' => 'Admin Petunia',
            'email' => 'admin@petuniaplay.com',
            'password' => Hash::make('password'),
            'phone' => '+57 305 759 4088',
            'role' => 'admin',
            'birth_date' => '1990-05-15',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Clientes de prueba
        User::create([
            'name' => 'María González',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'phone' => '+57 310 234 5678',
            'role' => 'customer',
            'birth_date' => '1995-08-20',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Carlos Ramírez',
            'email' => 'carlos@example.com',
            'password' => Hash::make('password'),
            'phone' => '+57 320 345 6789',
            'role' => 'customer',
            'birth_date' => '1988-03-10',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Laura Martínez',
            'email' => 'laura@example.com',
            'password' => Hash::make('password'),
            'phone' => '+57 315 456 7890',
            'role' => 'customer',
            'birth_date' => '1992-11-25',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}

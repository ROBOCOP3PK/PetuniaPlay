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
        $faker = \Faker\Factory::create('es_CO');

        // Usuario Administrador
        User::create([
            'name' => $faker->name(),
            'email' => 'admin@petuniaplay.com',
            'password' => Hash::make('2025'),
            'phone' => $faker->phoneNumber(),
            'document' => $faker->numerify('##########'),
            'role' => 'admin',
            'birth_date' => $faker->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d'),
            'is_active' => true,
            'email_notifications' => true,
            'email_verified_at' => now(),
        ]);

        // Usuario Manager
        User::create([
            'name' => $faker->name(),
            'email' => 'manager@petuniaplay.com',
            'password' => Hash::make('2025'),
            'phone' => $faker->phoneNumber(),
            'document' => $faker->numerify('##########'),
            'role' => 'manager',
            'birth_date' => $faker->dateTimeBetween('-50 years', '-22 years')->format('Y-m-d'),
            'is_active' => true,
            'email_notifications' => true,
            'email_verified_at' => now(),
        ]);

        // Generar 20 clientes aleatorios
        for ($i = 0; $i < 20; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('2025'),
                'phone' => $faker->phoneNumber(),
                'document' => $faker->numerify('##########'),
                'role' => 'customer',
                'birth_date' => $faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
                'is_active' => $faker->boolean(90), // 90% activos
                'email_notifications' => $faker->boolean(70), // 70% con notificaciones
                'email_verified_at' => $faker->boolean(85) ? now() : null, // 85% verificados
            ]);
        }

        // Generar algunos usuarios inactivos específicos
        for ($i = 0; $i < 5; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('2025'),
                'phone' => $faker->phoneNumber(),
                'document' => $faker->numerify('##########'),
                'role' => 'customer',
                'birth_date' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'is_active' => false,
                'email_notifications' => false,
                'email_verified_at' => null,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Crea únicamente el usuario administrador inicial.
     * Usar en producción: php artisan db:seed --class=AdminUserSeeder
     */
    public function run(): void
    {
        // Verificar si ya existe un admin
        if (User::where('role', 'admin')->exists()) {
            $this->command->info('Ya existe un usuario administrador.');
            return;
        }

        User::create([
            'name' => 'Administrador',
            'email' => 'admin@petuniaplay.com',
            'password' => Hash::make('CambiarEstaContraseña123!'),
            'phone' => '+57 300 000 0000',
            'document' => '0000000000',
            'role' => 'admin',
            'is_active' => true,
            'email_notifications' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Usuario administrador creado exitosamente.');
        $this->command->warn('IMPORTANTE: Cambia la contraseña después del primer inicio de sesión.');
    }
}

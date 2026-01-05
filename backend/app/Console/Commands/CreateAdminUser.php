<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin
                            {--email= : Email del administrador}
                            {--name= : Nombre del administrador}
                            {--password= : Contraseña (si no se provee, se generará una)}';

    protected $description = 'Crea un usuario administrador';

    public function handle()
    {
        $email = $this->option('email') ?? $this->ask('Email del administrador');
        $name = $this->option('name') ?? $this->ask('Nombre del administrador');
        $password = $this->option('password') ?? $this->secret('Contraseña (mínimo 8 caracteres)');

        // Validar email
        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            [
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Crear usuario
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => '',
            'document' => '',
            'role' => 'admin',
            'is_active' => true,
            'email_notifications' => true,
            'email_verified_at' => now(),
        ]);

        $this->info('');
        $this->info('✓ Usuario administrador creado exitosamente');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Email', $user->email],
                ['Nombre', $user->name],
                ['Rol', 'admin'],
            ]
        );

        return 0;
    }
}

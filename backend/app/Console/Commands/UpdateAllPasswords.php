<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateAllPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza la contraseña de todos los usuarios a "2025"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Actualizando contraseñas de todos los usuarios...');

        $users = User::all();
        $count = $users->count();

        if ($count === 0) {
            $this->warn('No hay usuarios en la base de datos.');
            return;
        }

        foreach ($users as $user) {
            $user->password = Hash::make('2025');
            $user->save();
        }

        $this->info("¡Contraseñas actualizadas exitosamente! Total de usuarios: {$count}");
    }
}

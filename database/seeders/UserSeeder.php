<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Usuários comuns
        User::factory(5)->create();

        $this->command->info('✓ Usuários criados com sucesso!');
    }
}
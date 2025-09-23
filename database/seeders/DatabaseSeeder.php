<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GrupoEconomicoSeeder::class,
            BandeiraSeeder::class,
            UnidadeSeeder::class,
            ColaboradorSeeder::class,
            // AuditLogSeeder::class, // Opcional 
        ]);
    }
}
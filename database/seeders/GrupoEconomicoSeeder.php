<?php

namespace Database\Seeders;

use App\Models\GrupoEconomico;
use Illuminate\Database\Seeder;

class GrupoEconomicoSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            [
                'nome' => 'Grupo Varejo Brasil',
                'created_at' => now()->subMonths(6),
            ],
            [
                'nome' => 'Grupo Alimentício Nacional',
                'created_at' => now()->subMonths(4),
            ],
            [
                'nome' => 'Grupo Fashion Style',
                'created_at' => now()->subMonths(2),
            ],
            [
                'nome' => 'Grupo Tech Solutions',
                'created_at' => now()->subMonth(),
            ],
            [
                'nome' => 'Grupo Construção & Cia',
                'created_at' => now()->subWeeks(2),
            ],
        ];

        foreach ($grupos as $grupo) {
            GrupoEconomico::create($grupo);
        }

        $this->command->info('✓ Grupos Econômicos criados com sucesso!');
    }
}
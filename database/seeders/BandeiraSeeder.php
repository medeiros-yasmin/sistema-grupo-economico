<?php

namespace Database\Seeders;

use App\Models\Bandeira;
use App\Models\GrupoEconomico;
use Illuminate\Database\Seeder;

class BandeiraSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = GrupoEconomico::all();

        $bandeiras = [
            // Grupo Varejo Brasil
            ['nome' => 'SuperMercado Total', 'grupo_economico_id' => $grupos[0]->id],
            ['nome' => 'HiperMax', 'grupo_economico_id' => $grupos[0]->id],
            ['nome' => 'MiniPreço', 'grupo_economico_id' => $grupos[0]->id],

            // Grupo Alimentício Nacional
            ['nome' => 'Restaurante Sabor & Cia', 'grupo_economico_id' => $grupos[1]->id],
            ['nome' => 'FastFood Express', 'grupo_economico_id' => $grupos[1]->id],
            ['nome' => 'Padaria Pão Quente', 'grupo_economico_id' => $grupos[1]->id],

            // Grupo Fashion Style
            ['nome' => 'Loja Elegância', 'grupo_economico_id' => $grupos[2]->id],
            ['nome' => 'Outlet Moda', 'grupo_economico_id' => $grupos[2]->id],
            ['nome' => 'Jeans & Cia', 'grupo_economico_id' => $grupos[2]->id],

            // Grupo Tech Solutions
            ['nome' => 'TechStore', 'grupo_economico_id' => $grupos[3]->id],
            ['nome' => 'GamerZone', 'grupo_economico_id' => $grupos[3]->id],

            // Grupo Construção & Cia
            ['nome' => 'ConstruMart', 'grupo_economico_id' => $grupos[4]->id],
            ['nome' => 'Casa & Decoração', 'grupo_economico_id' => $grupos[4]->id],
        ];

        foreach ($bandeiras as $bandeira) {
            Bandeira::create($bandeira);
        }

        $this->command->info('✓ Bandeiras criadas com sucesso!');
    }
}
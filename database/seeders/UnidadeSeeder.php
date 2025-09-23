<?php

namespace Database\Seeders;

use App\Models\Unidade;
use App\Models\Bandeira;
use Illuminate\Database\Seeder;

class UnidadeSeeder extends Seeder
{
    public function run(): void
    {
        $bandeiras = Bandeira::all();
        $unidades = [];

        foreach ($bandeiras as $bandeira) {
            // Cria 2-4 unidades para cada bandeira
            $quantidade = rand(2, 4);
            
            for ($i = 1; $i <= $quantidade; $i++) {
                $unidades[] = [
                    'nome_fantasia' => $bandeira->nome . ' - Unidade ' . $i,
                    'razao_social' => $bandeira->nome . ' LTDA - Filial ' . $i,
                    'cnpj' => $this->gerarCnpj(), // Agora sem formatação
                    'bandeira_id' => $bandeira->id,
                    'created_at' => now()->subMonths(rand(1, 12)),
                    'updated_at' => now(),
                ];
            }
        }

        // Insere em chunks para melhor performance
        foreach (array_chunk($unidades, 10) as $chunk) {
            Unidade::insert($chunk);
        }

        $this->command->info('✓ ' . count($unidades) . ' unidades criadas com sucesso!');
    }

    private function gerarCnpj(): string
    {
        // Gera um CNPJ válido de 14 dígitos (apenas números)
        $cnpj = '';
        
        // Gera os 12 primeiros dígitos aleatórios
        for ($i = 0; $i < 12; $i++) {
            $cnpj .= rand(0, 9);
        }
        
        // Calcula os dígitos verificadores (simplificado para testes)
        $cnpj .= '00'; // Dígitos verificadores fixos para simplificar
        
        return $cnpj;
    }
    
    // Versão alternativa que gera CNPJs mais realistas
    private function gerarCnpjRealista(): string
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        
        // Base do CNPJ (8 dígitos) + 0001 (filial) + dígitos verificadores
        $base = sprintf('%d%d%d%d%d%d%d%d', $n1, $n2, $n3, $n4, $n5, $n6, $n7, $n8);
        
        return $base . '000100';
    }
}
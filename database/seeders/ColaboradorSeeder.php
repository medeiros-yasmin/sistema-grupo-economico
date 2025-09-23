<?php

namespace Database\Seeders;

use App\Models\Colaborador;
use App\Models\Unidade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ColaboradorSeeder extends Seeder
{
    public function run(): void
    {
        $unidades = Unidade::all();
        
        if ($unidades->isEmpty()) {
            $this->command->error('❌ Nenhuma unidade encontrada. Execute UnidadeSeeder primeiro.');
            return;
        }

        $totalColaboradores = 0;
        $emailsGerados = []; // Controla emails nesta execução

        foreach ($unidades as $unidade) {
            // Cria 3-5 colaboradores para cada unidade
            $quantidade = rand(3, 5);
            
            for ($i = 1; $i <= $quantidade; $i++) {
                $email = $this->gerarEmailUnico($emailsGerados);
                $cpf = $this->gerarCpfUnico();
                
                $colaborador = Colaborador::create([
                    'nome' => $this->gerarNome(),
                    'email' => $email,
                    'cpf' => $cpf,
                    'unidade_id' => $unidade->id,
                    'created_at' => now()->subDays(rand(1, 180)),
                    'updated_at' => now(),
                ]);

                $emailsGerados[] = $email;
                $totalColaboradores++;

                $this->command->info("✅ Colaborador {$totalColaboradores}: {$colaborador->nome} ({$email})");
            }
        }

        $this->command->info("🎉 Total: {$totalColaboradores} colaboradores criados com sucesso!");
    }

    private function gerarNome(): string
    {
        $nomes = ['João', 'Maria', 'Pedro', 'Ana', 'Carlos', 'Juliana', 'Fernando', 'Patricia', 'Ricardo', 'Amanda'];
        $sobrenomes = ['Silva', 'Santos', 'Oliveira', 'Souza', 'Rodrigues', 'Ferreira', 'Alves', 'Pereira'];
        
        return $nomes[array_rand($nomes)] . ' ' . $sobrenomes[array_rand($sobrenomes)];
    }

    private function gerarEmailUnico(array &$emailsGerados): string
    {
        $dominios = ['gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com.br'];
        
        do {
            $nomeBase = Str::ascii($this->gerarNome()); // Remove acentos
            $nomeBase = Str::lower(str_replace(' ', '.', $nomeBase));
            $numero = rand(1, 9999);
            $email = "{$nomeBase}{$numero}@" . $dominios[array_rand($dominios)];
            
        } while (in_array($email, $emailsGerados) || Colaborador::where('email', $email)->exists());
        
        return $email;
    }

    private function gerarCpfUnico(): string
    {
        do {
            $cpf = $this->gerarCpf();
        } while (Colaborador::where('cpf', $cpf)->exists());
        
        return $cpf;
    }

    private function gerarCpf(): string
    {
        $cpf = '';
        for ($i = 0; $i < 11; $i++) {
            $cpf .= rand(0, 9);
        }
        return $cpf;
    }
}
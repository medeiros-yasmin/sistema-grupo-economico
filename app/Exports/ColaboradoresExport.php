<?php

namespace App\Exports;

use App\Models\Colaborador;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ColaboradoresExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Colaborador::with(['unidade.bandeira.grupoEconomico']);

        // Aplica os mesmos filtros do relatório
        if (!empty($this->filters['search_nome'])) {
            $query->where('nome', 'like', '%' . $this->filters['search_nome'] . '%');
        }

        if (!empty($this->filters['search_cpf'])) {
            $cpf = preg_replace('/[^0-9]/', '', $this->filters['search_cpf']);
            $query->where('cpf', 'like', '%' . $cpf . '%');
        }

        if (!empty($this->filters['search_email'])) {
            $query->where('email', 'like', '%' . $this->filters['search_email'] . '%');
        }

        if (!empty($this->filters['unidade_id'])) {
            $query->where('unidade_id', $this->filters['unidade_id']);
        }

        if (!empty($this->filters['bandeira_id'])) {
            $query->whereHas('unidade', function ($q) {
                $q->where('bandeira_id', $this->filters['bandeira_id']);
            });
        }

        if (!empty($this->filters['grupo_economico_id'])) {
            $query->whereHas('unidade.bandeira', function ($q) {
                $q->where('grupo_economico_id', $this->filters['grupo_economico_id']);
            });
        }

        return $query->orderBy('nome')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nome',
            'CPF',
            'E-mail',
            'Unidade',
            'Bandeira', 
            'Grupo Econômico',
            'Data de Criação'
        ];
    }

    public function map($colaborador): array
    {
        return [
            $colaborador->id,
            $colaborador->nome,
            $colaborador->cpf,
            $colaborador->email,
            $colaborador->unidade->nome_fantasia,
            $colaborador->unidade->bandeira->nome,
            $colaborador->unidade->bandeira->grupoEconomico->nome,
            $colaborador->created_at->format('d/m/Y H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para o cabeçalho
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']]
            ],
            
            // Auto size para todas as colunas
            'A:H' => [
                'autoSize' => true
            ]
        ];
    }
}
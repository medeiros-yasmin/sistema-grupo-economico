<?php

namespace App\Exports;

use App\Models\AuditLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuditLogsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = AuditLog::with('user');
        
        // Aplicar filtros
        if (!empty($this->filters['table_name'])) {
            $query->where('table_name', $this->filters['table_name']);
        }
        
        if (!empty($this->filters['action'])) {
            $query->where('action', $this->filters['action']);
        }
        
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->whereBetween('created_at', [
                $this->filters['start_date'] . ' 00:00:00',
                $this->filters['end_date'] . ' 23:59:59'
            ]);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Data/Hora',
            'Ação',
            'Tabela', 
            'ID do Registro',
            'Usuário',
            'IP',
            'Valores Antigos',
            'Valores Novos',
            'User Agent'
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->created_at->format('d/m/Y H:i:s'),
            $log->action_description,
            $log->table_name,
            $log->record_id, 
            $log->user ? $log->user->name : 'Sistema',
            $log->ip_address,
            $this->formatValues($log->old_values),
            $this->formatValues($log->new_values),
            $log->user_agent
        ];
    }

    public function title(): string
    {
        return 'Auditoria';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0']
                ]
            ],
        ];
    }

    private function formatValues($values)
    {
        if (empty($values)) {
            return '-';
        }
        
        // Se for string JSON, decodificar
        if (is_string($values)) {
            $decoded = json_decode($values, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $values = $decoded;
            } else {
                return $values; // Retorna a string original se não for JSON válido
            }
        }
        
        if (is_array($values)) {
            $formatted = [];
            foreach ($values as $key => $value) {
                $formatted[] = "{$key}: " . ($value ?? 'Nulo');
            }
            return implode(', ', $formatted);
        }
        
        return (string) $values;
    }
}
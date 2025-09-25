<?php

namespace App\Http\Controllers;

use App\Exports\AuditLogsExport;
use App\Jobs\ExportAuditLogs;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Exibir formulário de exportação de auditoria
     */
    public function auditReport()
    {
        $tables = [
            'grupo_economicos' => 'Grupos Econômicos',
            'bandeiras' => 'Bandeiras', 
            'unidades' => 'Unidades',
            'colaboradores' => 'Colaboradores'
        ];

        $actions = [
            'CREATE' => 'Criação',
            'UPDATE' => 'Atualização',
            'DELETE' => 'Exclusão'
        ];

        return view('exports.audit', compact('tables', 'actions'));
    }

    /**
     * Processar exportação de auditoria
     */
    public function exportAudit(Request $request)
    {
        // Validar filtros
        $filters = $request->validate([
            'table_name' => 'nullable|string|in:grupo_economicos,bandeiras,unidades,colaboradores',
            'action' => 'nullable|string|in:CREATE,UPDATE,DELETE',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'export_type' => 'required|in:immediate,queue'
        ]);

        // Remover export_type dos filtros (não é para a query)
        $exportType = $filters['export_type'];
        unset($filters['export_type']);

        // Exportação imediata (para pequenos volumes)
        if ($exportType === 'immediate') {
            try {
                $fileName = 'auditoria_' . now()->format('Y_m_d_His') . '.xlsx';
                
                return Excel::download(
                    new AuditLogsExport($filters),
                    $fileName
                );
                
            } catch (\Exception $e) {
                \Log::error('Erro na exportação imediata: ' . $e->getMessage());
                return back()->with('error', 'Erro ao exportar: ' . $e->getMessage());
            }
        }

        // Exportação em fila (para grandes volumes)
        try {
            ExportAuditLogs::dispatch($filters, auth()->id());
            
            return back()->with('success', 'Exportação em processamento. Você receberá uma notificação quando o arquivo estiver pronto.');
            
        } catch (\Exception $e) {
            \Log::error('Erro ao disparar job de exportação: ' . $e->getMessage());
            return back()->with('error', 'Erro ao iniciar exportação: ' . $e->getMessage());
        }
    }
}
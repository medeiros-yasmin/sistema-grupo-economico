<?php

namespace App\Jobs;

use App\Exports\AuditLogsExport;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExportAuditLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filters;
    protected $userId;
    protected $fileName;

    public function __construct($filters, $userId)
    {
        $this->filters = $filters;
        $this->userId = $userId;
        $this->fileName = 'auditoria_' . now()->format('Y_m_d_His') . '.xlsx';
    }

    public function handle()
{
    \Log::info('=== INICIANDO EXPORTAÇÃO ===');
    
    try {
        $export = new AuditLogsExport($this->filters);
        $fileName = 'auditoria_' . now()->format('Y_m_d_His') . '.xlsx';
        
        \Log::info('Salvando arquivo como: ' . $fileName);
        
        // Método 1: Store com path específico
        $path = Excel::store($export, $fileName, 'public');
        \Log::info('Arquivo salvo no path: ' . $path);
        
        // Método 2: Verifique o caminho absoluto
        $fullPath = storage_path('app/public/' . $fileName);
        \Log::info('Caminho absoluto: ' . $fullPath);
        
        // Método 3: Verifique se o arquivo existe
        if (file_exists($fullPath)) {
            \Log::info('✅ ARQUIVO EXISTE: ' . $fullPath);
        } else {
            \Log::error('❌ ARQUIVO NÃO ENCONTRADO: ' . $fullPath);
        }
        
    } catch (\Exception $e) {
        \Log::error('Erro na exportação: ' . $e->getMessage());
    }
}
    
    public function failed(\Exception $exception)
    {
        // Log de falha
        \Log::error('Job ExportAuditLogs falhou: ' . $exception->getMessage());
    }
}
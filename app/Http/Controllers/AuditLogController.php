<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        // Filtros
        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }

        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        $auditLogs = $query->paginate(20);

        $tables = [
            'grupo_economicos' => 'Grupos Econômicos',
            'bandeiras' => 'Bandeiras',
            'unidades' => 'Unidades',
            'colaboradores' => 'Colaboradores'
        ];

        return view('audit.index', compact('auditLogs', 'tables'));
    }

    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user'); // Carrega a relação user
        return view('audit.show', compact('auditLog'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index(Request $request)
    {
        // Query base com relacionamento
        $query = AuditLog::with('user');
        
        // Aplicar filtros
        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
        
        // Paginar resultados (20 por página)
        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Buscar todos os usuários para o filtro
        $users = User::all();
        
        return view('audit.index', compact('auditLogs', 'users'));
    }
}
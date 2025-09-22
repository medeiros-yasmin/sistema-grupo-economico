<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'user_id'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    // Relação com usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Escopo para filtrar por tabela
    public function scopeForTable($query, $tableName)
    {
        return $query->where('table_name', $tableName);
    }

    // Escopo para filtrar por ação
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // Método para formatar a ação - CORRIGIDO
    public function getActionDescriptionAttribute()
    {
        $actions = [
            'CREATE' => 'Criado',
            'UPDATE' => 'Atualizado', 
            'DELETE' => 'Excluído'
        ];

        return $actions[$this->action] ?? $this->action;
    }
}
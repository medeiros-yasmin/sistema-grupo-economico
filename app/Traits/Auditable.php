<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            self::audit('CREATE', $model);
        });

        static::updated(function ($model) {
            self::audit('UPDATE', $model);
        });

        static::deleted(function ($model) {
            self::audit('DELETE', $model);
        });
    }

    protected static function audit($action, $model)
    {
        // Não auditar a própria tabela de auditoria
        if ($model instanceof AuditLog) {
            return;
        }

        $oldValues = $newValues = null;

        if ($action === 'UPDATE') {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();
            
            // Remove campos de timestamp das alterações
            unset($oldValues['created_at'], $oldValues['updated_at']);
            unset($newValues['created_at'], $newValues['updated_at']);
            
            // Se não houve mudanças relevantes, não registra
            if (empty($newValues)) {
                return;
            }
        } elseif ($action === 'CREATE') {
            $newValues = $model->getAttributes();
            unset($newValues['created_at'], $newValues['updated_at']);
        }

        AuditLog::create([
            'action' => $action,
            'table_name' => $model->getTable(),
            'record_id' => $model->getKey(),
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'user_id' => Auth::id(),
        ]);
    }
}
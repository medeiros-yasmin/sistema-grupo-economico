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

        // Verifica se há um usuário autenticado
        $userId = Auth::check() ? Auth::id() : null;

        // Verifica dados essenciais
        if (!$model->getKey() || !$model->getTable()) {
            return; // Não audita se não tem ID ou tabela
        }

        $oldValues = $newValues = null;

        try {
            if ($action === 'UPDATE') {
                $oldValues = $model->getOriginal();
                $newValues = $model->getChanges();
                
                // Remove campos de timestamp
                if ($oldValues) {
                    unset($oldValues['created_at'], $oldValues['updated_at']);
                }
                if ($newValues) {
                    unset($newValues['created_at'], $newValues['updated_at']);
                }
                
                // Se não houve mudanças relevantes, não registra
                if (empty($newValues)) {
                    return;
                }
            } elseif ($action === 'CREATE') {
                $newValues = $model->getAttributes();
                if ($newValues) {
                    unset($newValues['created_at'], $newValues['updated_at']);
                }
            } elseif ($action === 'DELETE') {
                $oldValues = $model->getAttributes();
                if ($oldValues) {
                    unset($oldValues['created_at'], $oldValues['updated_at']);
                }
            }

            // Cria o registro de auditoria
            AuditLog::create([
                'action' => $action,
                'table_name' => $model->getTable(),
                'record_id' => $model->getKey(),
                'old_values' => $oldValues ? json_encode($oldValues, JSON_UNESCAPED_UNICODE) : null,
                'new_values' => $newValues ? json_encode($newValues, JSON_UNESCAPED_UNICODE) : null,
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'user_agent' => Request::userAgent() ?? 'Console/Artisan',
                'user_id' => $userId,
            ]);

        } catch (\Exception $e) {
            // Log do erro, mas não quebra a aplicação
            \Log::error('Erro na auditoria: ' . $e->getMessage());
        }
    }
}
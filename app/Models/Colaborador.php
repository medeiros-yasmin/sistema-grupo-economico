<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class Colaborador extends Model
{
    use HasFactory, Auditable;

    /**
     * Nome da tabela explicitamente definido.
     * Necessário porque o Laravel pluraliza incorretamente "colaborador".
     *
     * @var string
     */

    protected $table = 'colaboradores';

    protected $fillable = [
        'nome',
        'email', 
        'cpf',
        'unidade_id', // Chave estrangeira
    ];

    /**
     * Um Colaborador pertence a uma Unidade.
     */
    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }
}
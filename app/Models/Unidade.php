<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Unidade extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'nome_fantasia',
        'razao_social', 
        'cnpj',
        'bandeira_id', // Chave estrangeira
    ];

    /**
     * Uma Unidade pertence a uma Bandeira.
     */
    public function bandeira(): BelongsTo
    {
        return $this->belongsTo(Bandeira::class);
    }

    public function colaboradores(): HasMany
    {
        return $this->hasMany(Colaborador::class);
    }
}
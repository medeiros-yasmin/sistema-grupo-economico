<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bandeira extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nome',
        'grupo_economico_id', 
    ];

    /**
     * Uma Bandeira pertence a um Grupo Econômico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grupoEconomico(): BelongsTo
    {
        return $this->belongsTo(GrupoEconomico::class);
    }

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidade::class);
    }

}
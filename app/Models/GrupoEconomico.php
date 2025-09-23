<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class GrupoEconomico extends Model
{
   use HasFactory, Auditable;

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array<string>
     */

   protected $fillable = [
        'nome',
    ];


    public function bandeiras(): HasMany
    {
        // Retorna o relacionamento: 'Bandeira::class' é o model relacionado
        return $this->hasMany(Bandeira::class);
    }
}

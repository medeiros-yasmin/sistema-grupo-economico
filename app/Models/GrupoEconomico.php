<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrupoEconomico extends Model
{
   use HasFactory;

   protected $fillable = [
        'nome',
    ];


    public function bandeiras(): HasMany
    {
        // Retorna o relacionamento: 'Bandeira::class' é o model relacionado
        return $this->hasMany(Bandeira::class);
    }
}

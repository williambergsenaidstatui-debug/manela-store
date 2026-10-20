<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioEncomenda extends Model
{
    protected $table = 'horarios_encomenda';

    protected $fillable = [
        'dia',
        'horario',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensagemContato extends Model
{
    protected $table = 'mensagens_contato';

    protected $fillable = [
        'nome',
        'email',
        'whatsapp',
        'assunto',
        'mensagem',
        'lida',
    ];

    protected function casts(): array
    {
        return [
            'lida' => 'boolean',
        ];
    }
}

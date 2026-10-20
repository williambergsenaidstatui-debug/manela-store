<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocesprodutosModel extends Model
{
    use HasFactory;

    protected $table = 'doces';

    protected $fillable = [
        'categoria',
        'nome',
        'sobre',
        'descricao',
        'imagem',
        'id_produto',
        'preco',
        'peso',

    ];
}

<?php

namespace App\Models\Pagamentos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PagamentoCliente extends Model
{
    protected $table = 'pagamentos_clientes';

    protected $fillable = [
        'asaas_customer_id',
        'name',
        'cpf_cnpj',
        'email',
        'phone',
    ];

    public function pedidos(): HasMany
    {
        return $this->hasMany(PagamentoPedido::class, 'pagamento_cliente_id');
    }
}

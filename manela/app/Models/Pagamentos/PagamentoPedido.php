<?php

namespace App\Models\Pagamentos;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagamentoPedido extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';

    protected $table = 'pagamentos_pedidos';

    protected $fillable = [
        'pagamento_cliente_id',
        'pedido_id',
        'code',
        'description',
        'items',
        'customer_data',
        'delivery_data',
        'amount',
        'delivery_fee',
        'status',
        'payment_method',
        'payment_channel',
        'asaas_payment_id',
        'asaas_status',
        'invoice_url',
        'bank_slip_url',
        'pix_payload',
        'pix_encoded_image',
        'pix_expiration_date',
        'paid_at',
        'scheduled_at',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'customer_data' => 'array',
            'delivery_data' => 'array',
            'amount' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'pix_expiration_date' => 'datetime',
            'paid_at' => 'datetime',
            'scheduled_at' => 'datetime',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(PagamentoCliente::class, 'pagamento_cliente_id');
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function markAsPaid(string $asaasStatus): void
    {
        $this->forceFill([
            'status' => self::STATUS_PAID,
            'asaas_status' => $asaasStatus,
            'paid_at' => $this->paid_at ?? now(),
        ])->save();
    }
}

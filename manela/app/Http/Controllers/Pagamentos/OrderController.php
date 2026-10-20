<?php

namespace App\Http\Controllers\Pagamentos;

use App\Http\Controllers\Controller;
use App\Models\Pagamentos\PagamentoPedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function status(PagamentoPedido $pedido): JsonResponse
    {
        return response()->json([
            'status' => $pedido->status,
            'asaas_status' => $pedido->asaas_status,
            'paid_at' => $pedido->paid_at?->toISOString(),
            'invoice_url' => $pedido->invoice_url,
            'pix_payload' => $pedido->pix_payload,
            'pix_encoded_image' => $pedido->pix_encoded_image,
        ]);
    }

    public function updateStatus(Request $request, PagamentoPedido $pedido): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', Rule::in(['Em preparo', 'Entregue', 'Cancelado', 'pending', 'paid', 'failed'])],
        ]);

        $status = [
            'Em preparo' => PagamentoPedido::STATUS_PENDING,
            'Entregue' => PagamentoPedido::STATUS_PAID,
            'Cancelado' => PagamentoPedido::STATUS_FAILED,
        ][$data['status']] ?? $data['status'];

        $pedido->update([
            'status' => $status,
            'paid_at' => $status === PagamentoPedido::STATUS_PAID ? ($pedido->paid_at ?? now()) : $pedido->paid_at,
        ]);

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Status do pagamento atualizado com sucesso',
            'pedido' => $pedido->fresh(),
        ]);
    }
}

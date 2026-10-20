<?php

namespace App\Http\Controllers\Pagamentos;

use App\Http\Controllers\Controller;
use App\Models\Pagamentos\PagamentoPedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsaasWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $expectedToken = config('services.asaas.webhook_token');

        if (filled($expectedToken) && ! hash_equals($expectedToken, (string) $request->header('asaas-access-token'))) {
            return response()->json(['message' => 'Token do webhook invalido.'], 401);
        }

        $event = $request->string('event')->toString();
        $payment = $request->input('payment', []);
        $paymentId = $payment['id'] ?? null;
        $paymentStatus = $payment['status'] ?? null;

        if (blank($paymentId)) {
            Log::warning('Webhook Asaas sem payment id.', ['event' => $event]);
            return response()->json(['message' => 'Webhook sem payment id ignorado.']);
        }

        $pedido = PagamentoPedido::where('asaas_payment_id', $paymentId)->first();

        if (! $pedido) {
            Log::info('Webhook Asaas recebido para pagamento desconhecido.', [
                'event' => $event,
                'payment_id' => $paymentId,
            ]);
            return response()->json(['message' => 'Pagamento nao vinculado a pedido local.']);
        }

        if (in_array($paymentStatus, ['RECEIVED', 'CONFIRMED'], true)) {
            $pedido->markAsPaid($paymentStatus);
            return response()->json(['message' => 'Pedido marcado como pago.']);
        }

        $pedido->update(['asaas_status' => $paymentStatus]);

        return response()->json(['message' => 'Webhook processado.']);
    }
}

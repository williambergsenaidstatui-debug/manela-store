<?php

namespace App\Http\Controllers\Pagamentos;

use App\Http\Controllers\Controller;
use App\Models\DocesprodutosModel;
use App\Models\Pagamentos\PagamentoCliente;
use App\Models\Pagamentos\PagamentoPedido;
use App\Models\Pedido;
use App\Services\Pagamentos\AsaasClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CheckoutController extends Controller
{
    private const DELIVERY_FEE = 12.00;

    public function store(Request $request, AsaasClient $asaas): JsonResponse
    {
        $data = $request->validate([
            'cart_payload' => ['required', 'string'],
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'tipo_pedido' => ['required', Rule::in(['delivery', 'retirada'])],
            'pagamento' => ['required', Rule::in(['dinheiro', 'pix', 'cartao_credito_online', 'cartao_debito_maquininha', 'cartao_credito_maquininha'])],
            'agendamento' => ['nullable', 'date'],
            'cep' => ['required_if:tipo_pedido,delivery', 'nullable', 'string', 'max:20'],
            'rua' => ['required_if:tipo_pedido,delivery', 'nullable', 'string', 'max:255'],
            'numero' => ['required_if:tipo_pedido,delivery', 'nullable', 'string', 'max:30'],
            'bairro' => ['required_if:tipo_pedido,delivery', 'nullable', 'string', 'max:255'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'precisa_troco' => ['nullable', Rule::in(['sim', 'nao'])],
            'troco_para' => ['required_if:precisa_troco,sim', 'nullable', 'numeric', 'min:0'],
        ]);

        $cartPayload = json_decode($data['cart_payload'], true);
        if (! is_array($cartPayload)) {
            throw ValidationException::withMessages(['cart_payload' => 'Carrinho invalido.']);
        }

        $items = $this->normalizeItems($cartPayload['items'] ?? []);
        $subtotal = collect($items)->sum('subtotal');
        $deliveryFee = $data['tipo_pedido'] === 'delivery' ? self::DELIVERY_FEE : 0;
        $total = round($subtotal + $deliveryFee, 2);
        $description = collect($items)
            ->map(fn (array $item) => $item['quantidade'].'x '.$item['nome'])
            ->join('; ');

        $pedidoPagamento = DB::transaction(function () use ($data, $items, $description, $total, $deliveryFee) {
            $pedido = Pedido::create([
                'nome' => $data['nome'],
                'email' => $data['email'] ?? null,
                'whatsapp' => $data['whatsapp'],
                'data_encomenda' => $data['agendamento'] ?? now(),
                'categoria' => 'Carrinho',
                'produto' => $description,
                'observacao' => $this->observacaoPedido($data),
                'status' => 'Em preparo',
            ]);

            $cliente = PagamentoCliente::firstOrCreate(
                ['cpf_cnpj' => preg_replace('/\D+/', '', $data['cpf'])],
                [
                    'name' => $data['nome'],
                    'email' => $data['email'] ?? null,
                    'phone' => $data['whatsapp'],
                ],
            );

            $cliente->fill([
                'name' => $data['nome'],
                'email' => $data['email'] ?? $cliente->email,
                'phone' => $data['whatsapp'],
            ])->save();

            return PagamentoPedido::create([
                'pagamento_cliente_id' => $cliente->id,
                'pedido_id' => $pedido->id,
                'code' => Str::upper(Str::random(10)),
                'description' => $description,
                'items' => $items,
                'customer_data' => [
                    'nome' => $data['nome'],
                    'cpf' => $data['cpf'],
                    'email' => $data['email'] ?? null,
                    'whatsapp' => $data['whatsapp'],
                ],
                'delivery_data' => [
                    'tipo_pedido' => $data['tipo_pedido'],
                    'cep' => $data['cep'] ?? null,
                    'rua' => $data['rua'] ?? null,
                    'numero' => $data['numero'] ?? null,
                    'bairro' => $data['bairro'] ?? null,
                    'complemento' => $data['complemento'] ?? null,
                    'precisa_troco' => $data['precisa_troco'] ?? 'nao',
                    'troco_para' => $data['troco_para'] ?? null,
                ],
                'amount' => $total,
                'delivery_fee' => $deliveryFee,
                'payment_method' => $data['pagamento'],
                'payment_channel' => $this->isOnlinePayment($data['pagamento']) ? 'asaas' : 'local',
                'scheduled_at' => $data['agendamento'] ?? null,
            ]);
        });

        if ($this->isOnlinePayment($data['pagamento'])) {
            try {
                $this->createAsaasPayment($pedidoPagamento->load('cliente'), $asaas);
            } catch (RuntimeException $error) {
                $pedidoPagamento->update(['status' => PagamentoPedido::STATUS_FAILED]);

                return response()->json([
                    'erro' => 's',
                    'mensagem' => $error->getMessage(),
                    'pedido' => $this->formatOrder($pedidoPagamento->fresh('cliente')),
                ], 422);
            }
        }

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Pedido criado com sucesso.',
            'pedido' => $this->formatOrder($pedidoPagamento->fresh('cliente')),
        ], 201);
    }

    private function createAsaasPayment(PagamentoPedido $pedido, AsaasClient $asaas): void
    {
        $cliente = $pedido->cliente;

        if (blank($cliente->asaas_customer_id)) {
            $asaasCustomer = $asaas->createCustomer([
                'name' => $cliente->name,
                'cpf_cnpj' => $cliente->cpf_cnpj,
                'email' => $cliente->email,
                'phone' => $cliente->phone,
            ]);
            $cliente->update(['asaas_customer_id' => $asaasCustomer['id']]);
        }

        $payment = $asaas->createPayment([
            'customer' => $cliente->asaas_customer_id,
            'billing_type' => $this->asaasBillingType($pedido->payment_method),
            'value' => (float) $pedido->amount,
            'due_date' => now()->addDay()->toDateString(),
            'description' => $pedido->description,
            'external_reference' => $pedido->code,
        ]);

        $pedido->update([
            'asaas_payment_id' => $payment['id'],
            'asaas_status' => $payment['status'] ?? null,
            'invoice_url' => $payment['invoiceUrl'] ?? null,
            'bank_slip_url' => $payment['bankSlipUrl'] ?? null,
        ]);

        if ($pedido->payment_method === 'pix') {
            $pixQrCode = $asaas->pixQrCode($payment['id']);
            $pedido->update([
                'pix_payload' => $pixQrCode['payload'] ?? null,
                'pix_encoded_image' => $pixQrCode['encodedImage'] ?? null,
                'pix_expiration_date' => $pixQrCode['expirationDate'] ?? null,
            ]);
        }
    }

    private function normalizeItems(array $items): array
    {
        if (! count($items)) {
            throw ValidationException::withMessages(['cart_payload' => 'Adicione pelo menos um produto ao carrinho.']);
        }

        return collect($items)->map(function (array $item) {
            $produto = DocesprodutosModel::find($item['id'] ?? null);
            if (! $produto) {
                throw ValidationException::withMessages(['cart_payload' => 'Um dos produtos do carrinho nao foi encontrado.']);
            }

            $quantidade = max(1, (int) ($item['quantidade'] ?? $item['quantity'] ?? 1));
            $preco = (float) $produto->preco;

            return [
                'id' => $produto->id,
                'nome' => $produto->nome,
                'categoria' => $produto->categoria,
                'preco' => $preco,
                'quantidade' => $quantidade,
                'subtotal' => round($preco * $quantidade, 2),
            ];
        })->values()->all();
    }

    private function observacaoPedido(array $data): string
    {
        $linhas = [
            'Tipo: '.($data['tipo_pedido'] === 'delivery' ? 'Delivery' : 'Retirada'),
            'Pagamento: '.$this->paymentLabel($data['pagamento']),
        ];

        if (($data['pagamento'] ?? null) === 'dinheiro') {
            $linhas[] = ($data['precisa_troco'] ?? 'nao') === 'sim'
                ? 'Troco para: R$ '.number_format((float) ($data['troco_para'] ?? 0), 2, ',', '.')
                : 'Troco: nao precisa';
        }

        if (! empty($data['agendamento'])) {
            $linhas[] = 'Agendamento: '.$data['agendamento'];
        }

        if ($data['tipo_pedido'] === 'delivery') {
            $linhas[] = 'Endereco: '.trim(($data['rua'] ?? '').', '.($data['numero'] ?? '').' - '.($data['bairro'] ?? ''));
            $linhas[] = 'CEP: '.($data['cep'] ?? '');
            if (! empty($data['complemento'])) {
                $linhas[] = 'Complemento: '.$data['complemento'];
            }
        }

        return implode("\n", array_filter($linhas));
    }

    private function formatOrder(PagamentoPedido $pedido): array
    {
        return [
            'id' => $pedido->id,
            'code' => $pedido->code,
            'status' => $pedido->status,
            'asaas_status' => $pedido->asaas_status,
            'amount' => (float) $pedido->amount,
            'payment_method' => $pedido->payment_method,
            'payment_channel' => $pedido->payment_channel,
            'invoice_url' => $pedido->invoice_url,
            'bank_slip_url' => $pedido->bank_slip_url,
            'pix_payload' => $pedido->pix_payload,
            'pix_encoded_image' => $pedido->pix_encoded_image,
            'pix_expiration_date' => $pedido->pix_expiration_date?->toISOString(),
            'status_url' => route('api.pagamentos.pedidos.status', $pedido),
        ];
    }

    private function isOnlinePayment(string $method): bool
    {
        return in_array($method, ['pix', 'cartao_credito_online'], true);
    }

    private function asaasBillingType(string $method): string
    {
        return $method === 'pix' ? 'PIX' : 'CREDIT_CARD';
    }

    private function paymentLabel(string $method): string
    {
        return [
            'dinheiro' => 'Dinheiro',
            'pix' => 'PIX',
            'cartao_credito_online' => 'Cartao de Credito Online',
            'cartao_debito_maquininha' => 'Cartao de Debito - Maquininha',
            'cartao_credito_maquininha' => 'Cartao de Credito - Maquininha',
        ][$method] ?? $method;
    }
}

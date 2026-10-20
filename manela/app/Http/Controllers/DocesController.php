<?php

namespace App\Http\Controllers;

use App\Models\DocesprodutosModel;
use App\Models\HorarioEncomenda;
use App\Models\HorarioFuncionamento;
use App\Models\MensagemContato;
use App\Models\Pedido;
use App\Models\Pagamentos\PagamentoPedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocesController extends Controller
{
    public function cadastro_doces(Request $request): JsonResponse
    {
        $dados = $request->validate($this->regras());
        $imagem = $dados['imagem_upload'] ?? null;
        unset($dados['imagem_upload']);

        if ($imagem) {
            $dados['imagem'] = 'storage/'.$imagem->store('produtos', 'public');
        }

        $dados['id_produto'] ??= 0;
        $dados['peso'] ??= 0;
        $doce = DocesprodutosModel::create($dados);

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Doce cadastrado com sucesso',
            'doce' => $doce,
        ], 201);
    }

    public function listar_doces(): JsonResponse
    {
        return response()->json(DocesprodutosModel::latest()->get());
    }

    public function cardapio(): JsonResponse
    {
        return response()->json(DocesprodutosModel::latest()->get());
    }

    public function atualizar_doces(Request $request, int $id): JsonResponse
    {
        $doce = DocesprodutosModel::findOrFail($id);
        $dados = $request->validate($this->regras());
        $imagem = $dados['imagem_upload'] ?? null;
        unset($dados['imagem_upload']);

        if ($imagem) {
            $imagemAnterior = $doce->imagem;
            $dados['imagem'] = 'storage/'.$imagem->store('produtos', 'public');
            $this->excluirImagemGerenciada($imagemAnterior);
        }

        $dados['id_produto'] ??= $doce->id_produto ?? 0;
        $dados['peso'] ??= $doce->peso ?? 0;
        $doce->update($dados);

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Doce atualizado com sucesso',
            'doce' => $doce,
        ]);
    }

    public function deletar_doces(int $id): JsonResponse
    {
        $doce = DocesprodutosModel::findOrFail($id);
        $imagem = $doce->imagem;
        $doce->delete();
        $this->excluirImagemGerenciada($imagem);

        return response()->json(['erro' => 'n', 'mensagem' => 'Doce deletado com sucesso']);
    }

    public function buscar_doces(int $id): JsonResponse
    {
        return response()->json(DocesprodutosModel::findOrFail($id));
    }

    public function pedido_doce(Request $request): JsonResponse
    {
        $pedido = Pedido::create($request->validate([
            'nome' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'data_encomenda' => 'required|date|after_or_equal:today',
            'categoria' => 'required|string|max:255',
            'produto' => 'required|string|max:255',
            'observacao' => 'nullable|string|max:2000',
        ]));

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Pedido realizado com sucesso',
            'pedido' => $pedido,
            'whatsapp_url' => $pedido->whatsappUrl(),
        ], 201);
    }

    public function listar_pedidos(): JsonResponse
    {
        return response()->json(Pedido::latest()->get());
    }

    public function buscar_pedidos(int $id): JsonResponse
    {
        return response()->json(Pedido::findOrFail($id));
    }

    public function atualizar_status_pedido(Request $request, int $id): JsonResponse
    {
        $dados = $request->validate([
            'status' => 'required|string|in:Em preparo,Entregue,Cancelado',
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->update($dados);

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Status do pedido atualizado com sucesso',
            'pedido' => $pedido,
        ]);
    }

    public function mensagem_contato(Request $request): JsonResponse
    {
        $mensagem = MensagemContato::create($request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'whatsapp' => 'required|string|max:20',
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string|max:2000',
        ]));

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Mensagem enviada com sucesso',
            'contato' => $mensagem,
        ], 201);
    }

    public function listar_mensagens(): JsonResponse
    {
        return response()->json(MensagemContato::latest()->get());
    }

    public function atualizar_horario_funcionamento(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'dias' => 'required|string|max:255',
            'horario' => 'required|string|max:255',
            'observacao' => 'nullable|string|max:255',
        ]);

        $horario = HorarioFuncionamento::query()->first();
        $horario = $horario
            ? tap($horario)->update($dados)
            : HorarioFuncionamento::create($dados);

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Horario de funcionamento atualizado com sucesso',
            'horarioFuncionamento' => $horario,
        ]);
    }

    public function atualizar_horarios_encomenda(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'horarios' => 'required|array|min:1',
            'horarios.*.dia' => 'required|string|max:255',
            'horarios.*.horario' => 'required|string|max:255',
        ]);

        HorarioEncomenda::query()->delete();

        foreach ($dados['horarios'] as $item) {
            HorarioEncomenda::create([
                'dia' => $item['dia'],
                'horario' => $item['horario'],
                'ativo' => true,
            ]);
        }

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Horarios de encomenda atualizados com sucesso',
            'horariosAgenda' => HorarioEncomenda::where('ativo', true)->get(['dia', 'horario']),
        ]);
    }

    public function dashboard_resumo(): JsonResponse
    {
        $pedidos = Pedido::latest()->take(10)->get();
        $pagamentos = PagamentoPedido::with('cliente')->latest()->take(10)->get();
        $pedidosRecentes = $pedidos
            ->map(fn (Pedido $pedido) => $this->formatarPedido($pedido))
            ->concat($pagamentos->map(fn (PagamentoPedido $pedido) => $this->formatarPagamentoPedido($pedido)))
            ->sortByDesc('sort_date')
            ->take(10)
            ->map(function (array $pedido) {
                unset($pedido['sort_date']);
                return $pedido;
            })
            ->values();

        $horarioFuncionamento = HorarioFuncionamento::query()->first();
        $horariosAgenda = HorarioEncomenda::where('ativo', true)->get(['dia', 'horario']);
        $mensagens = MensagemContato::latest()->take(5)->get();

        return response()->json([
            'metrics' => [
                ['label' => 'Pedidos Realizados', 'value' => Pedido::count() + PagamentoPedido::count(), 'change' => 'Total de pedidos recebidos', 'icon' => 'bag'],
                ['label' => 'Mensagens no Contato', 'value' => MensagemContato::count(), 'change' => 'Mensagens recebidas pelo site', 'icon' => 'message'],
                ['label' => 'Encomendas Agendadas', 'value' => Pedido::whereDate('data_encomenda', '>=', today())->count() + PagamentoPedido::whereDate('scheduled_at', '>=', today())->count(), 'change' => 'Pedidos com data futura', 'icon' => 'calendar'],
                ['label' => 'Pagamentos Online', 'value' => PagamentoPedido::where('payment_channel', 'asaas')->count(), 'change' => 'Pedidos conectados ao Asaas', 'icon' => 'star'],
            ],
            'pedidosRecentes' => $pedidosRecentes,
            'produtos' => DocesprodutosModel::latest()->get(),
            'horarioFuncionamento' => $horarioFuncionamento ?: [
                'dias' => 'Segunda a Sabado',
                'horario' => 'Das 14:30 as 23:30',
                'observacao' => 'Nossa loja funciona todos os dias para melhor atender voce!',
            ],
            'horariosAgenda' => $horariosAgenda,
            'mensagensContato' => $mensagens->map(fn (MensagemContato $mensagem) => [
                'id' => $mensagem->id,
                'nome' => $mensagem->nome,
                'mensagem' => $mensagem->mensagem,
                'assunto' => $mensagem->assunto,
                'email' => $mensagem->email,
                'whatsapp' => $mensagem->whatsapp,
                'data' => optional($mensagem->created_at)->format('d/m - H:i'),
            ])->values(),
        ]);
    }

    public function horario_funcionamento(): JsonResponse
    {
        return response()->json([
            'horario' => 'Segunda a Sabado: 14:30 - 23:30',
        ]);
    }

    private function formatarPedido(Pedido $pedido): array
    {
        return [
            'id' => '#'.$pedido->id,
            'raw_id' => $pedido->id,
            'source' => 'pedido',
            'cliente' => $pedido->nome,
            'produtos' => trim($pedido->categoria.' - '.$pedido->produto),
            'total' => 'A combinar',
            'status' => $pedido->status,
            'data' => optional($pedido->created_at)->format('d/m - H:i'),
            'sort_date' => $pedido->created_at,
            'whatsapp' => $pedido->whatsapp,
            'email' => $pedido->email,
            'observacao' => $pedido->observacao,
        ];
    }

    private function formatarPagamentoPedido(PagamentoPedido $pedido): array
    {
        $status = [
            PagamentoPedido::STATUS_PENDING => 'Em preparo',
            PagamentoPedido::STATUS_PAID => 'Entregue',
            PagamentoPedido::STATUS_FAILED => 'Cancelado',
        ][$pedido->status] ?? 'Em preparo';

        $observacao = collect([
            'Codigo: '.$pedido->code,
            'Pagamento: '.$this->rotuloPagamento($pedido->payment_method),
            $pedido->asaas_status ? 'Status Asaas: '.$pedido->asaas_status : null,
            $pedido->invoice_url ? 'Link de pagamento: '.$pedido->invoice_url : null,
        ])->filter()->join("\n");

        return [
            'id' => '#PG'.$pedido->id,
            'raw_id' => $pedido->id,
            'source' => 'pagamento',
            'cliente' => $pedido->cliente?->name ?? data_get($pedido->customer_data, 'nome', 'Cliente'),
            'produtos' => $pedido->description,
            'total' => 'R$ '.number_format((float) $pedido->amount, 2, ',', '.'),
            'status' => $status,
            'data' => optional($pedido->created_at)->format('d/m - H:i'),
            'sort_date' => $pedido->created_at,
            'whatsapp' => $pedido->cliente?->phone ?? data_get($pedido->customer_data, 'whatsapp'),
            'email' => $pedido->cliente?->email ?? data_get($pedido->customer_data, 'email'),
            'observacao' => $observacao,
        ];
    }

    private function rotuloPagamento(?string $method): string
    {
        return [
            'dinheiro' => 'Dinheiro',
            'pix' => 'PIX',
            'cartao_credito_online' => 'Cartao de Credito Online',
            'cartao_debito_maquininha' => 'Cartao de Debito - Maquininha',
            'cartao_credito_maquininha' => 'Cartao de Credito - Maquininha',
        ][$method] ?? (string) $method;
    }

    private function regras(): array
    {
        return [
            'categoria' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'nome' => 'required|string|max:255',
            'sobre' => 'required|string',
            'descricao' => 'required|string',
            'imagem' => 'nullable|url:http,https|max:255',
            'imagem_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'id_produto' => 'nullable|integer',
            'peso' => 'nullable|numeric|min:0',
        ];
    }

    private function excluirImagemGerenciada(?string $imagem): void
    {
        if (! $imagem || ! str_starts_with($imagem, 'storage/')) {
            return;
        }

        Storage::disk('public')->delete(substr($imagem, strlen('storage/')));
    }
}


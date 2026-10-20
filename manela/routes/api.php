<?php

use App\Http\Controllers\DocesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Pagamentos\AsaasWebhookController;
use App\Http\Controllers\Pagamentos\CheckoutController;
use App\Http\Controllers\Pagamentos\OrderController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\EnsureAdministrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login_api'])->middleware('throttle:5,1');
Route::get('/cardapio', [DocesController::class, 'cardapio']);
Route::post('/pedido_doces', [DocesController::class, 'pedido_doce'])->middleware('throttle:10,1');
Route::post('/mensagem_contato', [DocesController::class, 'mensagem_contato'])->middleware('throttle:10,1');

Route::post('/pagamentos/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('api.pagamentos.checkout');
Route::get('/pagamentos/pedidos/{pedido}/status', [OrderController::class, 'status'])->whereNumber('pedido')->name('api.pagamentos.pedidos.status');
Route::post('/pagamentos/asaas/webhook', AsaasWebhookController::class)->name('api.pagamentos.asaas.webhook');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::middleware(EnsureAdministrador::class)->group(function () {
        Route::get('/dashboard/resumo', [DocesController::class, 'dashboard_resumo']);
        Route::post('/cadastro_usuario', [UsuarioController::class, 'cadastro_usuario']);
        Route::post('/cadastro_doces', [DocesController::class, 'cadastro_doces']);
        Route::get('/listar_doces', [DocesController::class, 'listar_doces']);
        Route::get('/listar_pedidos', [DocesController::class, 'listar_pedidos']);
        Route::get('/listar_mensagens', [DocesController::class, 'listar_mensagens']);
        Route::put('/horario_funcionamento', [DocesController::class, 'atualizar_horario_funcionamento']);
        Route::put('/horarios_encomenda', [DocesController::class, 'atualizar_horarios_encomenda']);
        Route::get('/buscar_pedidos/{id}', [DocesController::class, 'buscar_pedidos'])->whereNumber('id');
        Route::patch('/pedidos/{id}/status', [DocesController::class, 'atualizar_status_pedido'])->whereNumber('id');
        Route::patch('/pagamentos/pedidos/{pedido}/status', [OrderController::class, 'updateStatus'])->whereNumber('pedido');
        Route::put('/atualizar_doces/{id}', [DocesController::class, 'atualizar_doces'])->whereNumber('id');
        Route::delete('/excluir_doces/{id}', [DocesController::class, 'deletar_doces'])->whereNumber('id');
        Route::get('/buscar_doces/{id}', [DocesController::class, 'buscar_doces'])->whereNumber('id');
    });
});

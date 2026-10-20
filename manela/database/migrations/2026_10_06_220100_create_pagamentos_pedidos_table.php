<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pagamento_cliente_id')->constrained('pagamentos_clientes')->cascadeOnDelete();
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->nullOnDelete();
            $table->string('code')->unique();
            $table->text('description');
            $table->json('items')->nullable();
            $table->json('customer_data')->nullable();
            $table->json('delivery_data')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('payment_method', 40);
            $table->string('payment_channel', 20)->default('local');
            $table->string('asaas_payment_id')->nullable()->unique();
            $table->string('asaas_status')->nullable();
            $table->string('invoice_url')->nullable();
            $table->string('bank_slip_url')->nullable();
            $table->text('pix_payload')->nullable();
            $table->longText('pix_encoded_image')->nullable();
            $table->timestamp('pix_expiration_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['payment_method', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos_pedidos');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('asaas_customer_id')->nullable()->unique();
            $table->string('name');
            $table->string('cpf_cnpj', 20);
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('cpf_cnpj');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos_clientes');
    }
};

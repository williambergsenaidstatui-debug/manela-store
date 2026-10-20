<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios_funcionamento', function (Blueprint $table) {
            $table->id();
            $table->string('dias');
            $table->string('horario');
            $table->string('observacao')->nullable();
            $table->timestamps();
        });

        DB::table('horarios_funcionamento')->insert([
            'dias' => 'Segunda a Sabado',
            'horario' => 'Das 14:30 as 23:30',
            'observacao' => 'Nossa loja funciona todos os dias para melhor atender voce!',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_funcionamento');
    }
};

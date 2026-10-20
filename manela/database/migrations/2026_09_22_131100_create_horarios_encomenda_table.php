<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios_encomenda', function (Blueprint $table) {
            $table->id();
            $table->string('dia');
            $table->string('horario');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        foreach ([
            ['Segunda', '14:30 - 20:00'],
            ['Terca', '14:30 - 20:00'],
            ['Quarta', '14:30 - 20:00'],
            ['Quinta', '14:30 - 20:00'],
            ['Sexta', '14:30 - 21:00'],
            ['Sabado', '14:30 - 21:00'],
        ] as [$dia, $horario]) {
            DB::table('horarios_encomenda')->insert([
                'dia' => $dia,
                'horario' => $horario,
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_encomenda');
    }
};

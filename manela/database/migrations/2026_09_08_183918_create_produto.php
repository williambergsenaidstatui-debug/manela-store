<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('doces')) {
            Schema::table('doces', function (Blueprint $table) {
                if (! Schema::hasColumn('doces', 'imagem')) {
                    $table->string('imagem')->nullable()->after('descricao');
                }

                if (! Schema::hasColumn('doces', 'id_produto')) {
                    $table->unsignedBigInteger('id_produto')->nullable()->after('imagem');
                }

                if (! Schema::hasColumn('doces', 'peso')) {
                    $table->decimal('peso', 8, 2)->default(0)->after('preco');
                }
            });

            return;
        }

        Schema::create('doces', function (Blueprint $table) {
            $table->id();
            $table->string('categoria');
            $table->decimal('preco', 8, 2);
            $table->string('nome');
            $table->text('sobre');
            $table->text('descricao');
            $table->string('imagem')->nullable();
            $table->decimal('peso', 8, 2)->default(0);

            $table->unsignedBigInteger('id_produto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doces');
    }
};

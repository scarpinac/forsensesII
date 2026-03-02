<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produto', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 100);
            $table->string('codigo', 50);
            $table->integer('quantidadeVolumes');
            $table->double('peso', 10, 2);
            $table->double('altura', 10, 2);
            $table->double('largura', 10, 2);
            $table->double('comprimento', 10, 2);
            $table->decimal('precoUnitario', 10, 2);
            $table->decimal('descontoProduto', 5, 2);
            $table->foreignId('familia_id')->constrained('familia');
            $table->foreignId('acabamento_id')->constrained('acabamento');
            $table->foreignId('tela_id')->constrained('tela');
            $table->boolean('produtoBase');
            $table->foreignId('produtoBase_id')->nullable()->constrained('produto');
            $table->text('especificacao')->nullable();
            $table->text('observacao')->nullable();
            $table->boolean('permitirVenda');
            $table->boolean('permitirTela');
            $table->string('codigoBarras', 50)->nullable();
            $table->integer('ncm')->nullable();
            $table->integer('cst')->nullable();
            $table->integer('cest')->nullable();
            $table->foreignId('origem_id')->nullable()->constrained('origem_produto');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto');
    }
};

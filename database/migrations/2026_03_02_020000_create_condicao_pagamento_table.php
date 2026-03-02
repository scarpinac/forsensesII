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
        Schema::create('condicao_pagamento', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 50);
            $table->foreignId('revenda_id')->nullable()->constrained('revenda');
            $table->integer('diasEntreParcelas');
            $table->integer('quantidadeParcelas');
            $table->foreignId('situacao_id')->constrained('padrao_tipo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condicao_pagamento');
    }
};
